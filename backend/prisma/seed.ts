import { PrismaPg } from '@prisma/adapter-pg'
import { PrismaClient } from './generated/prisma/client'
import 'dotenv/config';

const adapter = new PrismaPg({ 
  connectionString: process.env.DATABASE_URL 
});
const prisma = new PrismaClient({ adapter });

async function main() {
  console.log('Iniciando seed...');

  const user = await prisma.user.create({
    data: {
      email: 'hola@ejemplo.com',
      name: 'Hola Mundo',
    },
  });

  console.log('Usuario creado:', user);
}

main()
  .catch((e) => {
    console.error('Error en seed:', e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });