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
export const menuData = [
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
  }
]
