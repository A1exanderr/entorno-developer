//VERSION 1
/* import { FastifyReply, FastifyRequest } from 'fastify'
import bcrypt from 'bcrypt'

export async function login(
  request: FastifyRequest,
  reply: FastifyReply
) {
  const { email, password } = request.body as {
    email?: string
    password?: string
  }

  // Validación básica
  if (!email || !password) {
    return reply.code(400).send({
      message: 'Email y password son requeridos'
    })
  }

  const user = await request.server.prisma.usuario.findUnique({
    where: { email }
  })

  // Usuario no existe
  if (!user) {
    return reply.code(401).send({
      message: 'Credenciales inválidas'
    })
  }

  // Contraseña vacía en BD (seguridad extra)
  if (!user.password) {
    return reply.code(500).send({
      message: 'Usuario sin contraseña registrada'
    })
  }

  const isValid = await bcrypt.compare(password, user.password)

  if (!isValid) {
    return reply.code(401).send({
      message: 'Credenciales inválidas'
    })
  }

  // JWT
  const token = request.server.jwt.sign(
    { id: user.id, email: user.email },
    { expiresIn: '1d' }
  )

  // Cookie
  reply.setCookie('token', token, {
    httpOnly: true,
    sameSite: 'lax',
    path: '/',
  })

  return reply.send({
    message: 'Login exitoso',
    user: {
      id: user.id,
      email: user.email,
      nombre: user.nombre,
    }
  })
}
*/
//VERSION 2
import { FastifyRequest, FastifyReply } from 'fastify'
import bcrypt from 'bcrypt'

export async function login(
  request: FastifyRequest,
  reply: FastifyReply
) {
  const { email, password } = request.body as {
    email: string
    password: string
  }

  const prisma = request.server.prisma

  const user = await prisma.usuario.findUnique({
    where: { email },
    include: {
      roles: {
        include: {
          rol: true,
        },
      },
    },
  })

  if (!user) {
    return reply.status(401).send({ message: 'Credenciales inválidas' })
  }

  const isValid = await bcrypt.compare(password, user.password)

  if (!isValid) {
    return reply.status(401).send({ message: 'Credenciales inválidas' })
  }

  const roles = user.roles.map((r:any) => r.rol.rol)

  const token = request.server.jwt.sign(
    {
      sub: user.id,
      roles,
    },
    { expiresIn: '15m' }
  )

  reply
    .setCookie('token', token, {
      httpOnly: true,
      secure: false, // true en producción
      sameSite: 'lax',
      path: '/',
    })
    .send({
      message: 'Login correcto',
      user: {
        id: user.id,
        email: user.email,
        roles,
      },
    })
}


export async function status() {
  return { authenticated: true }
}

export async function logout(
  _: FastifyRequest,
  reply: FastifyReply
) {
  reply
    .clearCookie('token', { path: '/' })
    .send({ message: 'Logout exitoso' })
}

//VERSION 3
/* import { FastifyReply, FastifyRequest } from 'fastify'
import bcrypt from 'bcrypt'

export async function login(req: FastifyRequest, reply: FastifyReply) {
  const prisma = req.server.prisma
  const { email, password } = req.body as { email: string; password: string }

  const user = await prisma.usuario.findUnique({ where: { email } })
  if (!user) return reply.status(401).send({ message: 'Usuario no encontrado' })

  const isValid = await bcrypt.compare(password, user.password)
  if (!isValid) return reply.status(401).send({ message: 'Contraseña incorrecta' })

// Generar access token
const accessToken = req.server.jwt.sign(
  { userId: user.id, email: user.email, roles: user.roles?.map(r => r.rol.rol) || [] },
  { expiresIn: '15m' }
)

// Generar refresh token
const refreshToken = req.server.jwt.sign({ userId: user.id, email: user.email }, { expiresIn: '7d' })

// Guardar refresh token en DB
await prisma.usuario.update({
  where: { id: user.id },
  data: { hash_key: refreshToken },
})

// Guardar cookies
reply
  .setCookie('token', accessToken, {  // <--- ESTA ES LA PARTE NUEVA
    httpOnly: true,
    secure: false, // true en producción con HTTPS
    sameSite: 'strict',
    path: '/',
    maxAge: 15 * 60, // 15 minutos
  })
  .setCookie('refresh_token', refreshToken, {
    httpOnly: true,
    secure: false,
    sameSite: 'strict',
    path: '/',
    maxAge: 60 * 60 * 24 * 7, // 7 días
  })
  .send({
    message: 'Login correcto',
    user: { id: user.id, email: user.email },
  })
}

export async function refreshToken(req: FastifyRequest, reply: FastifyReply) {
  const prisma = req.server.prisma
  const token = req.cookies.refresh_token
  if (!token) return reply.status(401).send({ message: 'No token' })

  try {
    // Verificamos el token
    const payload = req.server.jwt.verify(token) as any

    // Buscamos al usuario y verificamos que el refresh token coincida con hash_key
    const user = await prisma.usuario.findUnique({ where: { id: payload.userId } })
    if (!user || user.hash_key !== token) {
      return reply.status(401).send({ message: 'Token inválido' })
    }

    // Generamos nuevos tokens
    const newAccessToken = req.server.jwt.sign(
      { userId: user.id, email: user.email },
      { expiresIn: '15m' }
    )
    const newRefreshToken = req.server.jwt.sign(
      { userId: user.id, email: user.email },
      { expiresIn: '7d' }
    )

    // Guardamos nuevo refresh token en DB
    await prisma.usuario.update({
      where: { id: user.id },
      data: { hash_key: newRefreshToken },
    })

    // Reemplazamos cookie
    reply
      .setCookie('refresh_token', newRefreshToken, {
        httpOnly: true,
        secure: false, // true en producción con HTTPS
        path: '/',
        sameSite: 'strict',
        maxAge: 60 * 60 * 24 * 7, // 7 días
      })
      .send({ accessToken: newAccessToken })
  } catch {
    return reply.status(401).send({ message: 'Token inválido' })
  }
}

export async function logout(req: FastifyRequest, reply: FastifyReply) {
  const prisma = req.server.prisma
  const token = req.cookies.refresh_token

  if (token) {
    try {
      const payload = req.server.jwt.verify(token) as any
      await prisma.usuario.update({
        where: { id: payload.userId },
        data: { hash_key: null }, // invalidamos el refresh token
      })
    } catch {}
  }

  reply
    .clearCookie('refresh_token', { path: '/' })
    .send({ message: 'Logout exitoso' })
} */