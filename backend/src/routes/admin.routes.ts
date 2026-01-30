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
    async (request, reply) => {
      return { message: `Bienvenido admin ${request.user?.email}` }
    }
  )
}
