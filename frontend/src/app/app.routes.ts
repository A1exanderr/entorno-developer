import { Routes } from '@angular/router';
import { Login } from './components/login/login';
import { Panel } from './components/panel/panel';
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
        path: '**',
        redirectTo: 'login',
    },
];
