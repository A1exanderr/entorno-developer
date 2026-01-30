import { FastifyInstance } from 'fastify'
import { login } from '../controllers/auth.controller'

export default async function (fastify: FastifyInstance) {
  fastify.post('/login', login)

  fastify.post('/logout', async (_, reply) => {
    reply
      .clearCookie('token', { path: '/' })
      .send({ message: 'Logout exitoso' })
  })
}
