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

  const roles = user.roles.map(r => r.rol.rol)

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
