import { Routes } from '@angular/router';
import { Login } from './components/login/login';
import { Panel } from './components/panel/panel';
import { Layout } from './components/estructura/layout/layout';
import { authGuard } from './core/guards/auth-guard'
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
            // { path: 'roles', component: Roles },
            { path: '', redirectTo: 'panel', pathMatch: 'full' }
        ]
    },

    // fallback
    {
        path: '**',
        redirectTo: 'login'
    }
];
