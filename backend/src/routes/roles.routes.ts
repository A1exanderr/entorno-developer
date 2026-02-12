import { FastifyInstance } from 'fastify'
import { roleGuard } from '../plugins/roleGuard'

import { listar, crear, editar, eliminar, obtenerRoleConPermisos } from '../controllers/roles.controller'

export default async function rolesRoutes(app: FastifyInstance) {
  
  app.get(
    '/listar',
    { preHandler: app.authGuard },
    listar
  )
  app.post(
    '/crear',
    { preHandler: app.authGuard },
    crear
  )
  //app.put('/roles/:id', editar)
  app.post(
    '/editar',
    { preHandler: app.authGuard },
    editar
  )
  app.post(
    '/eliminar',
    { preHandler: app.authGuard },
    eliminar
  )
  app.post(
    '/obtenerRolesPermisos',
    { preHandler: app.authGuard },
    obtenerRoleConPermisos
  )
}
