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

/* import { FastifyRequest, FastifyReply } from 'fastify'

export async function menu(request: FastifyRequest, reply: FastifyReply) {
  try {

    const data = await request.server.prisma.$queryRaw`
      SELECT u.* FROM public."Usuario" AS u
    `
    console.log("alkeyyy", data);

    const user = (request as any).user
    const userId = user.sub

    const prisma = request.server.prisma

    const menus = await prisma.menuItem.findMany()

    return reply.send(menus)

  } catch (error) {
    console.error(error)
    return reply.status(500).send({ message: 'Error cargando menú' })
  }
} */
/* import { FastifyRequest, FastifyReply } from 'fastify'

function buildTree(items: any[], parentId: number | null = null):any {
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
    const prisma = request.server.prisma

    const user = (request as any).user
    const userId = user.sub
    const userRoles = user.roles || []

    console.log("Usuario:", userId)
    console.log("Roles:", userRoles)

    // 1️⃣ Obtener permisos de los roles del usuario
    const permisosRol = await prisma.permisoRol.findMany({
      where: {
        rol_id: {
          in: userRoles
        }
      },
      select: {
        permiso_id: true
      }
    })

    const permisosIds = permisosRol.map(p => p.permiso_id)

    // 2️⃣ Obtener todos los menús con sus permisos
    const menuItems = await prisma.menuItem.findMany({
      include: {
        permisos: true
      },
      orderBy: {
        order: 'asc'
      }
    })

    // 3️⃣ Filtrar menús según permisos
    const menuFiltrado = menuItems.filter(item => {
      // si es grupo o no tiene permisos asociados, se muestra
      if (!item.permisos || item.permisos.length === 0) return true

      return item.permisos.some(p =>
        permisosIds.includes(p.permiso_id)
      )
    })

    // 4️⃣ Construir árbol jerárquico
    const tree = buildTree(menuFiltrado)

    return reply.send(tree)

  } catch (error) {
    console.error(error)
    return reply.status(500).send({ message: 'Error cargando menú' })
  }
} */




/* 
const result: any = await prisma.$queryRawUnsafe(
  `
  WITH RECURSIVE menu_permitido AS (
    SELECT DISTINCT mi.*
    FROM "RolUsuario" ru
    JOIN "PermisoRol" pr ON pr.rol_id = ru.rol_id
    JOIN "MenuPermiso" mp ON mp.permiso_id = pr.permiso_id
    JOIN "MenuItem" mi ON mi.id = mp.menu_id
    WHERE ru.user_id = $1

    UNION

    SELECT parent.*
    FROM "MenuItem" parent
    JOIN menu_permitido child ON child.parent_id = parent.id
  )

  SELECT COALESCE(
    jsonb_agg(build_subtree(id)),
    '[]'::jsonb
  ) AS menu
  FROM menu_permitido
  WHERE parent_id IS NULL;
  `,
  userId
)
return reply.send(result[0].menu)
*/

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
import { FastifyRequest, FastifyReply } from 'fastify'



export async function menu(request: FastifyRequest, reply: FastifyReply) {
  try {
    const prisma = request.server.prisma

    const user = (request as any).user
    /* const result = await prisma.$queryRaw<any[]>`
      SELECT * FROM "Usuario"
      WHERE id = ${userId}
    `*/
    /* const result = await prisma.$queryRaw<any[]>`
      SELECT u.nombre FROM "Usuario" u
      WHERE u.id = ${user.sub}
    ` */
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
}