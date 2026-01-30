import fp from 'fastify-plugin'
import { PrismaPg } from '@prisma/adapter-pg'
import { PrismaClient } from '../../prisma/generated/prisma/client'
import 'dotenv/config'

export default fp(async (fastify) => {
  const adapter = new PrismaPg({
    connectionString: process.env.DATABASE_URL,
  })

  const prisma = new PrismaClient({ adapter })

  await prisma.$connect()

  fastify.decorate('prisma', prisma)

  fastify.addHook('onClose', async () => {
    await prisma.$disconnect()
  })
})
