import Fastify from 'fastify'
import "dotenv/config";
// Plugins
import prisma from './plugins/prisma'
import jwt from './plugins/jwt'
import cookies from './plugins/cookies'
import authGuard from './plugins/authGuard'
// Rutas
import authRoutes from './routes/auth.routes'
import adminRoutes from './routes/admin.routes'

const app = Fastify({ logger: true })
// Registrar plugins
await app.register(cookies)
await app.register(jwt)
await app.register(prisma)
await app.register(authGuard)
// Registrar rutas
const prefix = process.env.API_PREFIX || '/api';
//await app.register(authRoutes, { prefix: '/auth' })
await app.register(authRoutes, { prefix: `${prefix}/auth` })
await app.register(adminRoutes, { prefix: `${prefix}/admin` })
// Ruta de prueba
app.get('/', async () => {
  return { mensaje: 'Hola Mundo desde Fastify + TypeScript' }
})

// Arranque

const start = async () => {
  try {
    const port = Number(process.env.PORT) || 3000

    await app.listen({ port, host: '0.0.0.0' })
    console.log(`Servidor corriendo en http://localhost:${port}`)
  } catch (err) {
    app.log.error(err)
    process.exit(1)
  }
}

start()
