import { PrismaClient } from '../generated/prisma/client'

export async function seedPermisos(prisma: PrismaClient) {
  console.log('Creando permisos...')

  const permisosData = [
    { permiso: 'crear_usuarios', descripcion: 'Permiso para crear usuarios' },
    { permiso: 'eliminar_usuarios', descripcion: 'Permiso para eliminar usuarios' },
    { permiso: 'editar_usuarios', descripcion: 'Permiso para editar usuarios' },
    { permiso: 'ver_usuarios', descripcion: 'Permiso para ver usuarios' },
    // ... agrega todos los demás permisos aquí
  ]

  await prisma.permiso.createMany({
    data: permisosData,
    skipDuplicates: true,
  })

  console.log('Permisos creados')
}
