import { FastifyRequest, FastifyReply } from 'fastify'
//listar roles
export async function listar(request: FastifyRequest, reply: FastifyReply) {
  try {
    const prisma = request.server.prisma;

    const roles = await prisma.role.findMany();

    return reply.send(roles);

  } catch (error) {
    console.error(error)
    return reply.status(500).send({ message: 'Error listando roles' })
  }
}
//crear roles
export async function crear(request: FastifyRequest, reply: FastifyReply) {
  try {
    const prisma = request.server.prisma;
    const { rol } = request.body as { rol: string };

    if (!rol) {
      return reply.status(400).send({ message: 'El nombre del rol es obligatorio' });
    }

    const nuevoRol = await prisma.role.create({
      data: {
        //rol: rol
        rol: rol.trim().toLowerCase()
      }
    });

    return reply.status(201).send(nuevoRol);

  } catch (error: any) {

    // Manejo de error por duplicado (unique)
    if (error.code === 'P2002') {
      return reply.status(400).send({ message: 'El rol ya existe' });
    }

    console.error(error);
    return reply.status(500).send({ message: 'Error creando rol' });
  }
}
//Editar
export async function editar(request: FastifyRequest, reply: FastifyReply) {
  try {
    const prisma = request.server.prisma;

    //const { id } = request.params as { id: string };
    const { id } = request.body as { id: string };
    const { rol } = request.body as { rol: string };

    if (!rol) {
      return reply.status(400).send({ message: 'El nombre del rol es obligatorio' });
    }
    //verificacion
    const existe = await prisma.role.findUnique({
      where: { id: Number(id) }
    });

    if (!existe) {
      return reply.status(404).send({ message: 'Rol no encontrado--' });
    }
    //continuando con el actualizacion
    const rolActualizado = await prisma.role.update({
      where: {
        id: Number(id)
      },
      data: {
        rol: rol.trim().toLowerCase()
      }
    });

    return reply.send(rolActualizado);

  } catch (error: any) {

    if (error.code === 'P2025') {
      return reply.status(404).send({ message: 'Rol no encontrado' });
    }

    if (error.code === 'P2002') {
      return reply.status(400).send({ message: 'Ya existe un rol con ese nombre' });
    }

    console.error(error);
    return reply.status(500).send({ message: 'Error actualizando rol' });
  }
}
//Eliminar
/* export async function eliminar(request: FastifyRequest, reply: FastifyReply) {
  try {
    const prisma = request.server.prisma;
    const { id } = request.body as { id: string };

    const roleId = Number(id);

    // Verificar que exista
    const existe = await prisma.role.findUnique({
      where: { id: roleId }
    });

    if (!existe) {
      return reply.status(404).send({ message: 'Rol no encontrado' });
    }

    // Transacción para eliminar relaciones primero
    await prisma.$transaction([
      prisma.rolUsuario.deleteMany({
        where: { rol_id: roleId }
      }),
      prisma.permisoRol.deleteMany({
        where: { rol_id: roleId }
      }),
      prisma.role.delete({
        where: { id: roleId }
      })
    ]);

    return reply.send({ message: 'Rol eliminado correctamente' });

  } catch (error) {
    console.error(error);
    return reply.status(500).send({ message: 'Error eliminando rol' });
  }
} */
//con esquema cascade

export async function eliminar(request: FastifyRequest, reply: FastifyReply) {
  try {
    const prisma = request.server.prisma;
    //const { id } = request.params as { id: string };
    const { id } = request.body as { id: string };

    const roleId = Number(id);

    if (isNaN(roleId)) {
      return reply.status(400).send({ message: 'ID inválido' });
    }

    // Buscar rol con conteo de usuarios
    const role = await prisma.role.findUnique({
      where: { id: roleId },
      include: {
        _count: {
          select: { users: true }
        }
      }
    });

    if (!role) {
      return reply.status(404).send({ message: 'Rol no encontrado' });
    }

    // 🔐 Proteger admin
    if (role.rol.toLowerCase() === 'admin') {
      return reply.status(403).send({ message: 'No se puede eliminar el rol admin' });
    }

    // 👥 Validar que no tenga usuarios
    if (role._count.users > 0) {
      return reply.status(400).send({
        message: 'No se puede eliminar el rol porque tiene usuarios asignados'
      });
    }

    // Eliminar
    await prisma.role.delete({
      where: { id: roleId }
    });

    return reply.send({ message: 'Rol eliminado correctamente' });

  } catch (error) {
    console.error(error);
    return reply.status(500).send({ message: 'Error eliminando rol' });
  }
}
