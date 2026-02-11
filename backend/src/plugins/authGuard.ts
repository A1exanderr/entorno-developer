import fp from 'fastify-plugin'

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
})