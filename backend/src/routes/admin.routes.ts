import { FastifyInstance } from 'fastify'
import { roleGuard } from '../plugins/roleGuard'

export default async function adminRoutes(app: FastifyInstance) {
  app.get(
    '/dashboard',
    {
      preHandler: [
        app.authGuard,
        roleGuard(['admin']),
      ],
    },
    async () => {
      return { message: 'Bienvenido admin' }
    }
  )
}