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
/* export async function crear(request: FastifyRequest, reply: FastifyReply) {
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
} */
//verion normal con permisos
export async function crear(request: FastifyRequest, reply: FastifyReply) {
  try {
    const prisma = request.server.prisma;

    const { rol, permisos } = request.body as {
      rol: string;
      permisos?: number[];
    };

    if (!rol) {
      return reply.status(400).send({ message: 'El nombre del rol es obligatorio' });
    }

    if (permisos && !Array.isArray(permisos)) {
      return reply.status(400).send({ message: 'Permisos debe ser un array de IDs' });
    }

    const nuevoRol = await prisma.$transaction(async (tx) => {

      // Crear rol
      const role = await tx.role.create({
        data: {
          rol: rol.trim().toLowerCase()
        }
      });

      // Si vienen permisos, crear relaciones
      if (permisos && permisos.length > 0) {

        // Opcional: validar que los permisos existan
        const permisosExistentes = await tx.permiso.findMany({
          where: {
            id: { in: permisos }
          },
          select: { id: true }
        });

        if (permisosExistentes.length !== permisos.length) {
          throw new Error('Uno o más permisos no existen');
        }

        await tx.permisoRol.createMany({
          data: permisos.map((permisoId) => ({
            rol_id: role.id,
            permiso_id: permisoId
          }))
        });
      }

      return role;
    });

    return reply.status(201).send({
      message: 'Rol creado correctamente',
      role: nuevoRol
    });

  } catch (error: any) {

    if (error.code === 'P2002') {
      return reply.status(400).send({ message: 'El rol ya existe' });
    }

    if (error.message === 'Uno o más permisos no existen') {
      return reply.status(400).send({ message: error.message });
    }

    console.error(error);
    return reply.status(500).send({ message: 'Error creando rol' });
  }
}
//version pro pero no recomentado no tiene validacion de ver los permisos si sexixisten o no 
/* export async function crear(request: FastifyRequest, reply: FastifyReply) {
  try {
    const prisma = request.server.prisma;

    const { rol, permisos } = request.body as {
      rol: string;
      permisos?: number[];
    };

    if (!rol) {
      return reply.status(400).send({ message: 'El nombre del rol es obligatorio' });
    }

    const nuevoRol = await prisma.role.create({
      data: {
        rol: rol.trim().toLowerCase(),

        permisos: permisos && permisos.length > 0
          ? {
              create: permisos.map((permisoId) => ({
                permiso: {
                  connect: { id: permisoId }
                }
              }))
            }
          : undefined
      },
      include: {
        permisos: {
          include: {
            permiso: true
          }
        }
      }
    });

    return reply.status(201).send(nuevoRol);

  } catch (error: any) {

    if (error.code === 'P2002') {
      return reply.status(400).send({ message: 'El rol ya existe' });
    }

    console.error(error);
    return reply.status(500).send({ message: 'Error creando rol' });
  }
} */

//Editar solo role
/* export async function editar(request: FastifyRequest, reply: FastifyReply) {
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
} */
//Editar role y permisos seleccionados
export async function editar(request: FastifyRequest, reply: FastifyReply) {
  try {
    const prisma = request.server.prisma;
    //const { id } = request.params as { id: string };
    const { id } = request.body as { id: string };

    const { rol, permisos } = request.body as {
      rol: string;
      permisos: number[];
    };

    const roleId = Number(id);

    if (isNaN(roleId)) {
      return reply.status(400).send({ message: 'ID inválido' });
    }

    if (!rol) {
      return reply.status(400).send({ message: 'El nombre del rol es obligatorio' });
    }

    if (!Array.isArray(permisos)) {
      return reply.status(400).send({ message: 'Permisos debe ser un array' });
    }

    const resultado = await prisma.$transaction(async (tx) => {

      // Verificar que el rol exista
      const roleExistente = await tx.role.findUnique({
        where: { id: roleId },
        include: {
          permisos: true
        }
      });

      if (!roleExistente) {
        throw new Error('ROL_NO_EXISTE');
      }

      // Proteger admin
      if (roleExistente.rol.toLowerCase() === 'admin') {
        throw new Error('NO_EDITAR_ADMIN');
      }

      // 2️⃣ Actualizar nombre
      await tx.role.update({
        where: { id: roleId },
        data: {
          rol: rol.trim().toLowerCase()
        }
      });

      // Obtener permisos actuales
      const permisosActuales = roleExistente.permisos.map(p => p.permiso_id);

      // Determinar cuáles agregar
      const permisosAgregar = permisos.filter(
        (id) => !permisosActuales.includes(id)
      );

      // Determinar cuáles eliminar
      const permisosEliminar = permisosActuales.filter(
        (id) => !permisos.includes(id)
      );

      // Eliminar permisos sobrantes
      if (permisosEliminar.length > 0) {
        await tx.permisoRol.deleteMany({
          where: {
            rol_id: roleId,
            permiso_id: { in: permisosEliminar }
          }
        });
      }

      // Agregar nuevos permisos
      if (permisosAgregar.length > 0) {
        await tx.permisoRol.createMany({
          data: permisosAgregar.map((permisoId) => ({
            rol_id: roleId,
            permiso_id: permisoId
          }))
        });
      }

      return { message: 'Rol actualizado correctamente' };
    });

    return reply.send(resultado);

  } catch (error: any) {

    if (error.message === 'ROL_NO_EXISTE') {
      return reply.status(404).send({ message: 'Rol no encontrado' });
    }

    if (error.message === 'NO_EDITAR_ADMIN') {
      return reply.status(403).send({ message: 'No se puede modificar el rol admin' });
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
// obtenerRoleConPermisos
export async function obtenerRoleConPermisos(
  request: FastifyRequest,
  reply: FastifyReply
) {
  try {
    const prisma = request.server.prisma;
    //const { id } = request.params as { id: string };
    const { id } = request.body as { id: string };

    const roleId = Number(id);

    if (isNaN(roleId)) {
      return reply.status(400).send({ message: 'ID inválido' });
    }

    // Obtener rol con sus permisos actuales
    const role = await prisma.role.findUnique({
      where: { id: roleId },
      include: {
        permisos: {
          select: {
            permiso_id: true
          }
        }
      }
    });

    if (!role) {
      return reply.status(404).send({ message: 'Rol no encontrado' });
    }

    // Obtener todos los permisos del sistema
    const todosLosPermisos = await prisma.permiso.findMany({
      orderBy: { id: 'asc' }
    });

    // Convertir permisos del rol a array simple
    const permisosAsignados = role.permisos.map(p => p.permiso_id);

    // Marcar cuáles están asignados
    const permisosFormateados = todosLosPermisos.map(p => ({
      id: p.id,
      permiso: p.permiso,
      descripcion: p.descripcion,
      asignado: permisosAsignados.includes(p.id)
    }));

    return reply.send({
      id: role.id,
      rol: role.rol,
      permisos: permisosFormateados
    });

  } catch (error) {
    console.error(error);
    return reply.status(500).send({ message: 'Error obteniendo rol' });
  }
}

/* export async function obtenerRoleConPermisos(
  request: FastifyRequest,
  reply: FastifyReply
) {
  try {
    const prisma = request.server.prisma;
    const { id } = request.body as { id: string };

    const roleId = Number(id);

    if (isNaN(roleId)) {
      return reply.status(400).send({ message: 'ID inválido' });
    }

    // Obtener rol con permisos asignados
    const role = await prisma.role.findUnique({
      where: { id: roleId },
      include: {
        permisos: {
          select: { permiso_id: true }
        }
      }
    });

    if (!role) {
      return reply.status(404).send({ message: 'Rol no encontrado' });
    }

    const permisosAsignados = role.permisos.map(p => p.permiso_id);

    // Obtener módulos (menús grupo) con permisos
    const modulos = await prisma.menuItem.findMany({
      where: {
        is_group: true
      },
      include: {
        children: {
          include: {
            permisos: {
              include: {
                permiso: true
              }
            }
          }
        }
      },
      orderBy: { order: 'asc' }
    });

    // Formatear respuesta
    const resultado = modulos.map(modulo => ({
      id: modulo.id,
      label: modulo.label,
      icon: modulo.icon,
      permisos: modulo.children.flatMap(child =>
        child.permisos.map(mp => ({
          id: mp.permiso.id,
          permiso: mp.permiso.permiso,
          descripcion: mp.permiso.descripcion,
          asignado: permisosAsignados.includes(mp.permiso.id)
        }))
      )
    }));

    return reply.send({
      id: role.id,
      rol: role.rol,
      modulos: resultado
    });

  } catch (error) {
    console.error(error);
    return reply.status(500).send({ message: 'Error obteniendo rol enterprise' });
  }
}
 */