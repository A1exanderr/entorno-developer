import { Routes } from '@angular/router';
import { authGuard } from './core/guards/auth-guard'
import { Login } from './components/login/login';
import { Layout } from './components/estructura/layout/layout';
import { Panel } from './components/panel/panel';
import { Roles } from './components/roles/roles';

export const routes: Routes = [
    //Login sin layout
    {
        path: 'login',
        component: Login
    },
    //Todo lo protegido usa Layout
    {
        path: '',
        component: Layout,
        canActivate: [authGuard],
        children: [
            { path: 'panel', component: Panel },
            // aquí irán todas tus páginas protegidas
            // { path: 'usuarios', component: Usuarios },
            { path: 'roles', component: Roles },
            { path: '', redirectTo: 'panel', pathMatch: 'full' }
        ]
    },
    // raiz
    //{ path: '', redirectTo: 'panel', pathMatch: 'full', },
    { path: '', redirectTo: 'login', pathMatch: 'full', },
    // fallback
    { path: '**', redirectTo: 'login' }
];
