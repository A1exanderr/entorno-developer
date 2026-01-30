import { PrismaClient } from '../generated/prisma/client'

export async function seedRoles(prisma: PrismaClient) {
  console.log('Creando roles...')

  await prisma.role.createMany({
    data: [
      { rol: 'admin' },
      { rol: 'user' },
    ],
    skipDuplicates: true,
  })

  console.log('Roles creados')
}
