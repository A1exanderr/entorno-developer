import { FastifyRequest, FastifyReply } from 'fastify'
import bcrypt from 'bcrypt'

/* export async function login(
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
    //{ expiresIn: '15m' }
    { expiresIn: '1h' }
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
} */

export async function login(
  request: FastifyRequest,
  reply: FastifyReply
) {
  try {
    const { email, password } = request.body as {
      email: string
      password: string
    }

    const prisma = request.server.prisma

    // Buscar usuario con roles
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

    // Usuario no existe
    if (!user) {
      return reply.status(401).send({
        message: 'Credenciales inválidas',
      })
    }

    // Validar contraseña
    const isValid = await bcrypt.compare(password, user.password)

    if (!isValid) {
      return reply.status(401).send({
        message: 'Credenciales inválidas',
      })
    }

    // Extraer roles
    //const roles = user.roles.map((r: any) => r.rol.rol)
    const roles = user.roles.map((r: any) => r.rol.id)

    // 1 hora en segundos
    const ONE_HOUR = 60 * 60

    // Crear JWT
    const token = request.server.jwt.sign(
      {
        sub: user.id,
        roles,
      },
      { expiresIn: '1h' }
    )

    // Enviar cookie segura
    reply
      .setCookie('token', token, {
        httpOnly: true,
        secure: process.env.NODE_ENV === 'production', // true en prod
        sameSite: 'lax',
        path: '/',
        maxAge: ONE_HOUR, // importante para que coincida con JWT
      })
      .status(200)
      .send({
        message: 'Login correcto',
        user: {
          id: user.id,
          email: user.email,
          roles,
        },
      })
  } catch (error) {
    console.error(error)

    return reply.status(500).send({
      message: 'Error interno del servidor',
    })
  }
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