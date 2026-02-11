import { PrismaClient } from '../generated/prisma/client'

export async function seedPermisos(prisma: PrismaClient) {
  console.log('Creando permisos...')

  const permisosData = [
    { permiso: 'user.create', descripcion: 'Permiso para crear usuarios' },
    { permiso: 'user.eliminar', descripcion: 'Permiso para eliminar usuarios' },
    { permiso: 'user.editar', descripcion: 'Permiso para editar usuarios' },
    { permiso: 'user.index', descripcion: 'Permiso para ver usuarios' },
    // ... agrega todos los demás permisos aquí
  ]

  await prisma.permiso.createMany({
    data: permisosData,
    skipDuplicates: true,
  })

  console.log('Permisos creados')
}
