import { FastifyReply, FastifyRequest } from 'fastify'
import bcrypt from 'bcrypt'

export async function login(
  request: FastifyRequest,
  reply: FastifyReply
) {
  const { email, password } = request.body as {
    email: string
    password: string
  }

  const user = await request.server.prisma.usuario.findUnique({
    where: { email },
  })

  if (!user) {
    return reply.status(401).send({ message: 'Credenciales inválidas' })
  }

  const valid = await bcrypt.compare(password, user.contraceña)

  if (!valid) {
    return reply.status(401).send({ message: 'Credenciales inválidas' })
  }

  const token = request.server.jwt.sign({
    id: user.id,
    email: user.email,
  })

  reply
    .setCookie('token', token, {
      httpOnly: true,
      secure: process.env.NODE_ENV === 'production',
      sameSite: 'lax',
      path: '/',
    })
    .send({ message: 'Login exitoso' })
}
