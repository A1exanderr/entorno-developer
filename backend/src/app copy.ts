import Fastify from 'fastify'
import "dotenv/config";
const fastify = Fastify({ logger: true })

fastify.get('/', async (request, reply) => {
  return { mensaje: 'Hola Mundo desde Fastify + TypeScript 👋' }
})
/* fastify.get('/', async () => {
  return { mensaje: 'Hola desde alkey' }
}) */
const start = async () => {
  try {
    const port = Number(process.env.PORT) || 3000
    await fastify.listen({ port, host: '0.0.0.0' })
    console.log('Servidor corriendo en http://localhost:3000')
  } catch (err) {
    fastify.log.error(err)
    process.exit(1)
  }
}

start()
