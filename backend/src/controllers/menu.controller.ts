/* import { FastifyRequest, FastifyReply } from 'fastify'

//experimental controlador

function buildTree(items: any[], parentId: number | null = null) {
  return items
    .filter(item => item.parent_id === parentId)
    .map(item => ({
      label: item.label,
      icon: item.icon,
      routerLink: item.route ? [item.route] : undefined,
      items: buildTree(items, item.id)
    }))
}

export async function menu(request: FastifyRequest, reply: FastifyReply) {
  try {
    const user = (request as any).user
    console.log("=========================================");
    console.log("Hola como estas", user);
    console.log("id:",user.sub);
    console.log("rol:",user.roles);
    console.log("=========================================");

    return { authenticated: true }
    } catch (error) {
    console.error(error)
    return reply.status(500).send({ message: 'Error cargando menú' })
  }
} */

/* 
export async function menu(request: FastifyRequest, reply: FastifyReply) {
  try {
    const prisma = request.server.prisma

    const result = await prisma.$queryRaw<
      any[]
    >`SELECT * FROM "Usuario"`

    return reply.send(result)

  } catch (error) {
    console.error(error)
    return reply.status(500).send({ message: 'Error cargando menú' })
  }
}
*/
/* const result = await prisma.$queryRaw<any[]>`
      SELECT * FROM "Usuario"
      WHERE id = ${userId}
    `*/
    /* const result = await prisma.$queryRaw<any[]>`
      SELECT u.nombre FROM "Usuario" u
      WHERE u.id = ${user.sub}
    ` */
/* import { FastifyRequest, FastifyReply } from 'fastify'

export async function menu(request: FastifyRequest, reply: FastifyReply) {
  try {
    const prisma = request.server.prisma

    const user = (request as any).user
    const result = await prisma.$queryRaw<any[]>`
      SELECT DISTINCT
        mi.id,
        mi.label,
        mi.icon,
        mi.route,
        mi.parent_id,
        mi.order,
        mi.is_group
      FROM "Usuario" u
      JOIN "RolUsuario" ru ON u.id = ru.user_id
      JOIN "Role" r ON ru.rol_id = r.id 
      JOIN "PermisoRol" pr ON pr.rol_id = r.id 
      JOIN "Permiso" p ON p.id = pr.permiso_id 
      JOIN "MenuPermiso" mp ON mp.permiso_id = p.id 
      JOIN "MenuItem" mi ON mi.id = mp.menu_id 
      WHERE u.id = ${user.sub}
      ORDER BY mi.order
    `

    return reply.send(result)

  } catch (error) {
    console.error(error)
    return reply.status(500).send({ message: 'Error cargando menú' })
  }
} */
/* FUNCIONAL */
/* import { FastifyRequest, FastifyReply } from 'fastify'

interface MenuItem {
  id: number
  label: string
  icon: string
  route: string | null
  parent_id: number | null
  order: number
  is_group: boolean
  items?: MenuItem[]
}

export async function menu(request: FastifyRequest, reply: FastifyReply) {
  try {
    const prisma = request.server.prisma
    const user = (request as any).user

    const result = await prisma.$queryRaw<MenuItem[]>`
      SELECT DISTINCT
        mi.id,
        mi.label,
        mi.icon,
        mi.route,
        mi.parent_id,
        mi.order,
        mi.is_group
      FROM "Usuario" u
      JOIN "RolUsuario" ru ON u.id = ru.user_id
      JOIN "Role" r ON ru.rol_id = r.id 
      JOIN "PermisoRol" pr ON pr.rol_id = r.id 
      JOIN "Permiso" p ON p.id = pr.permiso_id 
      JOIN "MenuPermiso" mp ON mp.permiso_id = p.id 
      JOIN "MenuItem" mi ON mi.id = mp.menu_id 
      WHERE u.id = ${user.sub}
      ORDER BY mi.order
    `

    // 🔹 Convertir lista plana a árbol
    const map = new Map<number, MenuItem>()
    const tree: MenuItem[] = []

    result.forEach(item => {
      item.items = []
      map.set(item.id, item)
    })

    result.forEach(item => {
      if (item.parent_id) {
        const parent = map.get(item.parent_id)
        parent?.items?.push(item)
      } else {
        tree.push(item)
      }
    })

    return reply.send(tree)

  } catch (error) {
    console.error(error)
    return reply.status(500).send({ message: 'Error cargando menú' })
  }
}
 */
/* VERISON PRO */
import { FastifyRequest, FastifyReply } from 'fastify'

interface MenuItem {
  id: number
  label: string
  icon: string | null
  route: string | null
  parent_id: number | null
  order: number
  is_group: boolean
  items?: MenuItem[]
  routerLink?: string
}

interface MenuOutput {
  //id: number
  label: string
  icon?: string | null
  routerLink?: string
  items?: MenuOutput[]
}


export async function menu(request: FastifyRequest, reply: FastifyReply) {
  try {
    const prisma = request.server.prisma
    const user = (request as any).user

    const result = await prisma.$queryRaw<MenuItem[]>`
      SELECT DISTINCT
        mi.id,
        mi.label,
        mi.icon,
        mi.route,
        mi.parent_id,
        mi.order,
        mi.is_group
      FROM "Usuario" u
      JOIN "RolUsuario" ru ON u.id = ru.user_id
      JOIN "Role" r ON ru.rol_id = r.id 
      JOIN "PermisoRol" pr ON pr.rol_id = r.id 
      JOIN "Permiso" p ON p.id = pr.permiso_id 
      JOIN "MenuPermiso" mp ON mp.permiso_id = p.id 
      JOIN "MenuItem" mi ON mi.id = mp.menu_id 
      WHERE u.id = ${user.sub}
      ORDER BY mi.order
    `

    // 🔹 Crear mapa
    const map = new Map<number, MenuItem>()
    const tree: MenuItem[] = []

    result.forEach(item => {
      if (item.is_group) {
        item.items = []
      }

      // Adaptación directa para PrimeNG
      if (!item.is_group && item.route) {
        item.routerLink = item.route
      }

      map.set(item.id, item)
    })

    // 🔹 Construir jerarquía
    result.forEach(item => {
      if (item.parent_id) {
        const parent = map.get(item.parent_id)
        if (parent && parent.is_group) {
          parent.items!.push(item)
        }
      } else {
        tree.push(item)
      }
    })

    // 🔹 Ordenar recursivamente
    const sortRecursive = (items: MenuItem[]) => {
      items.sort((a, b) => a.order - b.order)
      items.forEach(i => {
        if (i.items && i.items.length) {
          sortRecursive(i.items)
        }
      })
    }

    sortRecursive(tree)

    // 🔹 Eliminar grupos vacíos
    const removeEmptyGroups = (items: MenuItem[]): MenuItem[] => {
      return items
        .filter(item => {
          if (item.is_group) {
            item.items = removeEmptyGroups(item.items || [])
            return item.items.length > 0
          }
          return true
        })
    }

    const cleanedTree = removeEmptyGroups(tree)

    // 🔹 Limpiar propiedades innecesarias
    /* const cleanOutput = (items: MenuItem[]) => {
      return items.map(item => {
        const { is_group, parent_id, order, route, ...clean } = item
        if (item.items && item.items.length) {
          clean.items = cleanOutput(item.items)
        }
        return clean
      })
    } */
    const cleanOutput = (items: MenuItem[]): MenuOutput[] => {
      return items.map(item => {
        const clean: MenuOutput = {
          //id: item.id,
          label: item.label,
          icon: item.icon ?? undefined,
          routerLink: item.routerLink ?? undefined
        }

        if (item.items && item.items.length) {
          clean.items = cleanOutput(item.items)
        }

        return clean
      })
    }
    return reply.send(cleanOutput(cleanedTree))

  } catch (error) {
    console.error(error)
    return reply.status(500).send({ message: 'Error cargando menú' })
  }
}
