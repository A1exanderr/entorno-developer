/* export function roleGuard(rolesPermitidos: string[]) {
  return async (request: any, reply: any) => {
    const userRoles = request.user.roles

    const autorizado = rolesPermitidos.some(r =>
      userRoles.includes(r)
    )

    if (!autorizado) {
      return reply.status(403).send({ message: 'Acceso denegado' })
    }
  }
} */
import { FastifyReply, FastifyRequest } from 'fastify'

export function roleGuard(roles: string[]) {
  return async (request: FastifyRequest, reply: FastifyReply) => {
    if (!request.user) {
      return reply.status(401).send({ message: 'No autenticado' })
    }

    const userRoles: string[] = request.user.roles || []

    const hasRole = roles.some(role => userRoles.includes(role))
    if (!hasRole) {
      return reply.status(403).send({ message: 'No autorizadoaa aqui' })
    }
  }
}