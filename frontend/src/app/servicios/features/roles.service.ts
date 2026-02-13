import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root',
})
export class RolesService {
  private http = inject(HttpClient);
  private urlBase = environment.apiUrl;

  listar(){
    return this.http.get(`${this.urlBase}/roles/listar`, { withCredentials: true });
  }
  crear(data:any){
    return this.http.post(`${this.urlBase}/roles/crear`, data, { withCredentials: true });
  }
  editar(data:any){
    return this.http.post(`${this.urlBase}/roles/editar`, data, { withCredentials: true });
  }
  eliminar(data:any){
    return this.http.post(`${this.urlBase}/roles/eliminar`, data, { withCredentials: true });
  }
  obtenerRolesPermisos(data:any){
    return this.http.post(`${this.urlBase}/roles/obtenerRolesPermisos`, data, { withCredentials: true });
  }
  obtenerPermisos(){
    return this.http.get(`${this.urlBase}/roles/listarPermisos`, { withCredentials: true });
  }
}
