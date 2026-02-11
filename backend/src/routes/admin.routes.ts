import { FastifyInstance } from 'fastify'
import { roleGuard } from '../plugins/roleGuard'

import { menu } from '../controllers/menu.controller'

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
  app.get(
    '/menu',
    { preHandler: app.authGuard },
    menu
  )
}
