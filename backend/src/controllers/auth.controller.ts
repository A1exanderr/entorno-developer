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