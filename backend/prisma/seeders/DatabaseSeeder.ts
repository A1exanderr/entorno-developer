import { PrismaClient } from '../generated/prisma/client'
import { PrismaPg } from '@prisma/adapter-pg'
import 'dotenv/config';

import { seedUsuarios } from './usuarioSeeder'
import { seedRoles } from './roleSeeder'
import { seedPermisos } from './permisoSeeder'
import { seedRelaciones } from './relacioneSeeder'

const adapter = new PrismaPg({ 
  connectionString: process.env.DATABASE_URL 
});
const prisma = new PrismaClient({ adapter });


async function main() {
  await seedUsuarios(prisma)
  await seedRoles(prisma)
  await seedPermisos(prisma)
  await seedRelaciones(prisma)
}

main()
  .catch(e => {
    console.error('Error en seed:', e)
    process.exit(1)
  })
  .finally(async () => {
    await prisma.$disconnect()
  })
