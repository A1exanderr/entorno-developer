import { PrismaClient } from '../generated/prisma/client'
import bcrypt from 'bcrypt'

export async function seedUsuarios(prisma: PrismaClient) {
  console.log('Creando usuarios...')

  const passwordPlano = 'password'
  const passwordHash = await bcrypt.hash(passwordPlano, 12)

  const usuarios = await prisma.usuario.createMany({
    data: [
      {
        nombre: 'Alex',
        a_paterno: 'Mamani',
        a_materno: 'Llojlla',
        ci: '12345678',
        email: 'developer@gmail.com',
        password: passwordHash,
        foto: 'avatar.png',
        estado: 'activo',
      },
      {
        nombre: 'María',
        a_paterno: 'López',
        a_materno: 'Martínez',
        ci: '87654321',
        email: 'b@b.com',
        password: passwordHash,
        foto: 'avatar.png',
        estado: 'activo',
      },
    ],
    skipDuplicates: true,
  })

  console.log('Usuarios creados')
}
