import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root',
})
export class AuthService {

  http = inject(HttpClient);
  urlBase = environment.apiUrl;
  
  auth(data:any){
    return this.http.post(`${this.urlBase}/auth/login`, data, { withCredentials: true });
  }
  status(){
    return this.http.get(`${this.urlBase}/auth/status`, { withCredentials: true });
  }
  logout(){
    return this.http.post(`${this.urlBase}/auth/logout`, {}, { withCredentials: true });
  }
}
