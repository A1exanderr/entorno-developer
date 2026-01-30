import { PrismaClient } from '../generated/prisma/client'

export async function seedUsuarios(prisma: PrismaClient) {
  console.log('Creando usuarios...')

  const usuarios = await prisma.usuario.createMany({
    data: [
      {
        nombre: 'Alex',
        a_paterno: 'Mamani',
        a_materno: 'Llojlla',
        ci: '12345678',
        email: 'looveyouuu10@gmail.com',
        password: '$2a$10$j4YA5toZ9Tu8.M2J7TnRuOFUynpiP52pRKtGOVLqX7PFyYB4F1U6q',
        foto: 'avatar.png',
        estado: 'activo',
      },
      {
        nombre: 'María',
        a_paterno: 'López',
        a_materno: 'Martínez',
        ci: '87654321',
        email: 'b@b.com',
        password: '$2a$10$j4YA5toZ9Tu8.M2J7TnRuOFUynpiP52pRKtGOVLqX7PFyYB4F1U6q',
        foto: 'avatar.png',
        estado: 'activo',
      },
    ],
    skipDuplicates: true,
  })

  console.log('Usuarios creados')
}
