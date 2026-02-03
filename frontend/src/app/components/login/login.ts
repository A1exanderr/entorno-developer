import { Component, signal, inject } from '@angular/core';
import { Router } from '@angular/router'
//ng prime
import { FormsModule } from '@angular/forms';
import { CardModule } from 'primeng/card';
import { FloatLabelModule } from 'primeng/floatlabel';
import { InputTextModule } from 'primeng/inputtext';
import { PasswordModule } from 'primeng/password';
import { ButtonModule } from 'primeng/button';
import { CheckboxModule } from 'primeng/checkbox';

import { InputGroupModule } from 'primeng/inputgroup';
import { InputGroupAddonModule } from 'primeng/inputgroupaddon';
//importamos el servicio
import { AuthService } from '../../servicios/auth/auth.service';

@Component({
  selector: 'app-login',
  imports: [
    FormsModule,
    CardModule,
    FloatLabelModule,
    InputTextModule,
    PasswordModule,
    ButtonModule,
    CheckboxModule,
    InputGroupModule,
    InputGroupAddonModule
  ],
  templateUrl: './login.html',
  styleUrl: './login.scss',
})
export class Login {
  protected readonly title = signal('Login');
  public email:string = '';
  public password:string = '';
  public remember:boolean = false;
  public mensaje:string = '';

  private authService = inject(AuthService);
  private router = inject(Router);

  public login() {
    /* console.log('Email:', this.email);
    console.log('Password:', this.password);
    console.log('Recordar:', this.remember); */
    this.authService.auth({"email": this.email, "password": this.password}).subscribe({
      next: (resp: any) => {
        this.mensaje = 'Login exitoso';
        //console.log(resp);
        // navegar al dashboard
        this.router.navigate(['/panel']);
      },
      error: (err) => {
        //console.log("alkey",err.error?.message);
        this.mensaje = err.error?.message || 'Datos incorrectos';
      }
    });
  }
}
