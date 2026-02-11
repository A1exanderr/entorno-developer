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

import { FastifyRequest, FastifyReply } from 'fastify'

export async function menu(request: FastifyRequest, reply: FastifyReply) {
  try {
    const user = (request as any).user
    const userId = user.sub

    const prisma = request.server.prisma

    const menus = await prisma.menuItem.findMany()

    return reply.send(menus)

  } catch (error) {
    console.error(error)
    return reply.status(500).send({ message: 'Error cargando menú' })
  }
}
