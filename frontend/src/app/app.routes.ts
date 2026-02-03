import { Routes } from '@angular/router';
import { Login } from './components/login/login';
import { Panel } from './components/panel/panel';
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
    /* {
        path: 'dashboard',
        component: Dashboard,
        canActivate: [authGuard],
    }, */
    {
        path: 'panel',
        component: Panel
    },
    {
        path: '**',
        redirectTo: 'login',
    },
];
