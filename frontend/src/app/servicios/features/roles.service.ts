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
    return this.http.get(`${this.urlBase}/admin/menu`, { withCredentials: true });
  }
  
}
