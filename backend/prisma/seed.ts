import { PrismaClient } from '../generated/prisma/client'; // Path exacto

const prisma = new PrismaClient();

async function main() {
  await prisma.user.create({
    data: {
      email: 'hola@ejemplo.com',
      name: 'Hola Mundo',
    },
  });
}

main()
  .then(() => console.log('Seed completado ✅'))
  .catch((e) => console.error(e))
  .finally(async () => await prisma.$disconnect());
