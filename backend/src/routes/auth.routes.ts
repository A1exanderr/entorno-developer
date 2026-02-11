import { FastifyInstance } from 'fastify'
import { login, status, logout } from '../controllers/auth.controller'

export default async function (app: FastifyInstance) {
  app.post('/login', login)

  app.post('/logout', logout)

  app.get(
    '/status',
    { preHandler: app.authGuard },
    status
  )
}