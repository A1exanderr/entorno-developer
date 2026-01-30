/* import { PrismaClient } from '../generated/prisma/client'
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
 */
import { PrismaClient, Estado } from '../generated/prisma/client'
import bcrypt from 'bcrypt'

export async function seedUsuarios(prisma: PrismaClient) {
  console.log('Creando usuarios...')
  const salt = 12
  const usuariosRaw = [
    {
      nombre: 'Alex',
      a_paterno: 'Mamani',
      a_materno: 'Llojlla',
      ci: '12345678',
      email: 'developer@gmail.com',
      password: 'password',
      foto: 'avatar.png',
      estado: Estado.activo,
    },
    {
      nombre: 'María',
      a_paterno: 'López',
      a_materno: 'Martínez',
      ci: '87654321',
      email: 'b@b.com',
      password: 'password',
      foto: 'avatar.png',
      estado: Estado.activo,
    },
  ]

  const usuarios = await Promise.all(
    usuariosRaw.map(async u => ({
      nombre: u.nombre,
      a_paterno: u.a_paterno,
      a_materno: u.a_materno,
      ci: u.ci,
      email: u.email,
      password: await bcrypt.hash(u.password, salt),
      foto: u.foto,
      estado: u.estado,
    }))
  )

  await prisma.usuario.createMany({ 
    data: usuarios,
    skipDuplicates: true,
  })
  console.log('Usuarios creados')
}
