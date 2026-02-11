export function roleGuard(rolesPermitidos: string[]) {
  return async (request: any, reply: any) => {
    const userRoles = request.user.roles

    const autorizado = rolesPermitidos.some(r =>
      userRoles.includes(r)
    )

    if (!autorizado) {
      return reply.status(403).send({ message: 'Acceso denegado' })
    }
  }
}