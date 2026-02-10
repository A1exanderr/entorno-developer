import { Routes } from '@angular/router';
import { Login } from './components/login/login';
import { Panel } from './components/panel/panel';
import { Layout } from './components/estructura/layout/layout';
import { authGuard } from './core/guards/auth-guard'
export const routes: Routes = [
    {
        path: '',
        redirectTo: 'login',
        pathMatch: 'full',
    },
    {
        path: 'login',
        component: Login,
    },
    {
        path: 'panel',
        component: Panel,
        canActivate: [authGuard],
    },
    {
        path: 'base',
        component: Layout,
        canActivate: [authGuard],
    },
    {
        path: '**',
        redirectTo: 'login',
    },
];
