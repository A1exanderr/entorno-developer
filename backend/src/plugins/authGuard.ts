/* import fp from 'fastify-plugin'

export default fp(async (app) => {
  app.decorate(
    'authGuard',
    async (request: any, reply: any) => {
      try {
        const token = request.cookies.token

        if (!token) {
          return reply.status(401).send({ message: 'No autenticado' })
        }

        const payload = app.jwt.verify(token)

        request.user = payload
      } catch (err) {
        return reply.status(401).send({ message: 'Token inválido' })
      }
    }
  )
}) */

import fp from 'fastify-plugin'

export default fp(async (app) => {
  app.decorate(
    'authGuard',
    async (request: any, reply: any) => {
      try {
        const token = request.cookies?.token
        if (!token) return reply.status(401).send({ message: 'No autenticado' })

        const payload = app.jwt.verify(token) as any

        // Aquí nos aseguramos que roles existe
        request.user = {
          userId: payload.userId,
          email: payload.email,
          roles: payload.roles || [],
        }
      } catch {
        return reply.status(401).send({ message: 'Token inválido' })
      }
    }
  )
})