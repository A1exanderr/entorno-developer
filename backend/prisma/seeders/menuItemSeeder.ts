/* import { PrismaClient } from '../generated/prisma/client'

export async function seedMenuItem(prisma: PrismaClient) {
  console.log('Creando Menu...')

  await prisma.menuItem.createMany({
    data: [
      {
        label: 'Reportes',
        icon: 'pi pi-fw pi-briefcase',
        route: null,
        order: 1
      }
    ],
    skipDuplicates: true,
  })

  console.log('Menu creado')
}
 */
import { PrismaClient } from '../generated/prisma/client'
import { menuData } from './menuSeed'

export async function seedMenuItem(prisma: PrismaClient) {
  console.log('Creando menú...')

  async function createMenuItems(
    items: any[],
    parentId: number | null = null
  ) {
    for (let index = 0; index < items.length; index++) {
      const item = items[index]

      // Crear el item
      const created = await prisma.menuItem.create({
        data: {
          label: item.label,
          icon: item.icon,
          route: item.route ?? null,
          parent_id: parentId,
          order: index,
          is_group: item.is_group ?? false,
        }
      })

      // Crear permisos si existen
      if (item.permisos && item.permisos.length > 0) {
        for (const permisoNombre of item.permisos) {
          const permiso = await prisma.permiso.findUnique({
            where: { permiso: permisoNombre }
          })

          if (permiso) {
            await prisma.menuPermiso.create({
              data: {
                menu_id: created.id,
                permiso_id: permiso.id
              }
            })
          }
        }
      }

      // Recursividad para hijos
      if (item.children && item.children.length > 0) {
        await createMenuItems(item.children, created.id)
      }
    }
  }

  await createMenuItems(menuData)

  console.log('Menú creado correctamente')
}