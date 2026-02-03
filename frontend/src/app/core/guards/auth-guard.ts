import { CanActivateFn, Router } from '@angular/router';
import { inject } from '@angular/core'
import { AuthService } from '../../servicios/auth/auth.service';
import { catchError, map, of } from 'rxjs'


/* export const authGuard: CanActivateFn = (route, state) => {
  //return true;
  const authService = inject(AuthService)
  const router = inject(Router)
  return authService.status().pipe(
    map(() => true), // si responde OK → pasa
    catchError(() => {
      router.navigate(['/login'])
      return of(false)
    })
  )
}; */
export const authGuard: CanActivateFn = (_, state) => {
  const authService = inject(AuthService)
  const router = inject(Router)

  return authService.status().pipe(
    map(() => true),
    catchError(() => {
      if (state.url !== '/login') {
        router.navigate(['/login'])
      }
      return of(false)
    })
  )
}
