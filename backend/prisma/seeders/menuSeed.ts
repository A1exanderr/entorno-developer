/* export const menuData = [
  {
    label: "Reportes",
    icon: "pi pi-fw pi-briefcase",
    is_group: true,
    children: [
      {
        label: "Auth",
        icon: "pi pi-fw pi-user",
        is_group: true,
        children: [
          {
            label: "Login",
            icon: "pi pi-fw pi-sign-in",
            route: "/auth/login",
            permisos: ["auth.login"]
          },
          {
            label: "Error",
            icon: "pi pi-fw pi-times-circle",
            route: "/auth/error",
            permisos: ["auth.error"]
          },
          {
            label: "Access Denied",
            icon: "pi pi-fw pi-lock",
            route: "/auth/access",
            permisos: ["auth.access"]
          }
        ]
      }
    ]
  }
]
 */
/* FUNCIONAL OPTIMO */
/* export const menuData = [
  {
    label: "Panel",
    icon: "pi pi-fw pi-briefcase",
    route: "/panel",
    is_group: false,
    permisos: ["panel.index"]
  },
  {
    label: "Reportes",
    icon: "pi pi-fw pi-briefcase",
    is_group: true,
    permisos: ["reporte.submenu"],
    children: [
      {
        label: "Ventas",
        icon: "pi pi-fw pi-sign-in",
        route: "/reporte/ventas",
        permisos: ["reporte.ventas"]
      },
      {
        label: "Personas",
        icon: "pi pi-fw pi-times-circle",
        route: "/reporte/personas",
        permisos: ["reporte.personas"]
      },
      {
        label: "inventarios",
        icon: "pi pi-fw pi-lock",
        route: "/reporte/inventarios",
        permisos: ["reporte.inventarios"]
      }
    ]
  },
  {
    label: "Usuarios",
    icon: "pi pi-fw pi-briefcase",
    is_group: true,
    permisos: ["user.submenu"],
    children: [
      {
        label: "Crear",
        icon: "pi pi-fw pi-sign-in",
        route: "/user/create",
        permisos: ["user.create"]
      },
      {
        label: "Eliminar",
        icon: "pi pi-fw pi-times-circle",
        route: "/user/eliminar",
        permisos: ["user.eliminar"]
      },
      {
        label: "Editar",
        icon: "pi pi-fw pi-lock",
        route: "/user/editar",
        permisos: ["user.editar"]
      }
    ]
  }
] */
/* Version pro */
export const menuData = [
  {
    label: "MENU",
    is_group: true,
    permisos: ["panel.index"],
    children: [
      {
        label: "Panel",
        icon: "pi pi-fw pi-sign-in",
        route: "/panel/index",
        permisos: ["panel.index"]
      },
      {
        label: "Usuarios",
        icon: "pi pi-fw pi-users",
        route: "/usuarios",
        permisos: ["user.listar"]
      },
      {
        label: "Roles",
        icon: "pi pi-fw pi-key",
        route: "/roles",
        permisos: ["rol.listar"]
      },
      {
        label: "Reportes",
        icon: "pi pi-fw pi-briefcase",
        is_group: true,
        permisos: ["reporte.submenu"],
        route: "/reporte",
        children: [
          {
            label: "Ventas",
            icon: "pi pi-fw pi-sign-in",
            route: "/reporte/ventas",
            permisos: ["reporte.ventas"]
          },
          {
            label: "Personas",
            icon: "pi pi-fw pi-times-circle",
            route: "/reporte/personas",
            permisos: ["reporte.personas"]
          },
          {
            label: "inventarios",
            icon: "pi pi-fw pi-lock",
            route: "/reporte/inventarios",
            permisos: ["reporte.inventarios"]
          }
        ]
      },  
    ]
  },
  {
    label: "Usuarios",
    icon: "pi pi-fw pi-briefcase",
    is_group: true,
    permisos: ["user.submenu"],
    children: [
      {
        label: "Crear",
        icon: "pi pi-fw pi-sign-in",
        route: "/user/create",
        permisos: ["user.create"]
      },
      {
        label: "Eliminar",
        icon: "pi pi-fw pi-times-circle",
        route: "/user/eliminar",
        permisos: ["user.eliminar"]
      },
      {
        label: "Editar",
        icon: "pi pi-fw pi-lock",
        route: "/user/editar",
        permisos: ["user.editar"]
      }
    ]
  }
]