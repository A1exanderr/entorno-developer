import { PrismaClient } from '../generated/prisma/client'

export async function seedPermisos(prisma: PrismaClient) {
  console.log('Creando permisos...')

  const permisosData = [
    { permiso: 'panel.index', descripcion: 'Permiso para ver el panel' },
    { permiso: 'reporte.submenu', descripcion: 'Permiso para ver el sub menu'},
    { permiso: 'reporte.ventas', descripcion: 'Permiso para ver ventas'},
    { permiso: 'reporte.personas', descripcion: 'Permiso para ver personas'},
    { permiso: 'reporte.inventarios', descripcion: 'Permiso para ver inventarios'},
    { permiso: 'user.submenu', descripcion: 'Permiso para ver sub menu' },
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
