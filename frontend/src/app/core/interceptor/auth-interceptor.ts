import { HttpInterceptorFn } from '@angular/common/http';
/* import { catchError } from 'rxjs/operators';
import { EMPTY, throwError } from 'rxjs'; */
import { EMPTY, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';

export const authInterceptor: HttpInterceptorFn = (req, next) => {
//return next(req);
  return next(req).pipe(
    catchError(err => {
      //Solo silenciamos el status
      //if (err.status === 401 && req.url.includes('/auth/status')) {
      if (err.status === 401) {
        //console.log("holas esrtas aqui");
        return EMPTY;
      }
      // otros errores sí se propagan
      return throwError(() => err);
    })
  );
};
