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
  routerLink?: string[]
  path?: string | null
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

    const cleanOutput = (items: MenuItem[]): MenuOutput[] => {
      return items.map(item => {
        const clean: MenuOutput = {
          //id: item.id,
          label: item.label,
          icon: item.icon ?? undefined,
          //routerLink: item.routerLink ?? undefined
        }
        // Aquí está la lógica que quieres
        if (item.route) {
          if (item.is_group) {
            clean.path = item.route      // grupos usan path
          } else {
            clean.routerLink = [item.route] //item.route // hijos usan routerLink
          }
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
