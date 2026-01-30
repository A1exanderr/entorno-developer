import { PrismaClient } from '../generated/prisma/client'

export async function seedRelaciones(prisma: PrismaClient) {
  console.log('Creando relaciones roles-usuarios y permisos-roles...')

  const alex = await prisma.usuario.findUnique({ where: { email: 'looveyouuu10@gmail.com' } })
  const maria = await prisma.usuario.findUnique({ where: { email: 'b@b.com' } })

  const adminRole = await prisma.role.findUnique({ where: { rol: 'admin' } })
  const userRole = await prisma.role.findUnique({ where: { rol: 'user' } })

  if (alex && maria && adminRole && userRole) {
    await prisma.rolUsuario.createMany({
      data: [
        { user_id: alex.id, rol_id: adminRole.id },
        { user_id: maria.id, rol_id: userRole.id },
      ],
      skipDuplicates: true,
    })
  }

  const permisos = await prisma.permiso.findMany()

  if (adminRole && permisos.length) {
    const data = permisos.map(p => ({ rol_id: adminRole.id, permiso_id: p.id }))
    await prisma.permisoRol.createMany({ data, skipDuplicates: true })
  }

  console.log('Relaciones creadas')
}
