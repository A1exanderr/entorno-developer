import fp from 'fastify-plugin'
import cors from '@fastify/cors'

export default fp(async (fastify) => {
  const isProd = process.env.NODE_ENV === 'production'

  const allowedOrigins = (
    isProd
      ? process.env.CORS_PROD_URLS
      : process.env.CORS_DEV_URLS
  )?.split(',') || []

  await fastify.register(cors, {
    origin: (origin, cb) => {
      // Permitir requests sin origin (Postman, curl, etc.)
      if (!origin) {
        cb(null, true)
        return
      }

      if (allowedOrigins.includes(origin)) {
        cb(null, true)
      } else {
        cb(new Error('Origen no permitido por CORS'), false)
      }
    },
    credentials: true,
  })
})
