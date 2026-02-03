import { Component, inject } from '@angular/core';
import { Router } from '@angular/router'
//importar ng prime
import { ButtonModule } from 'primeng/button';
//importacion de servicios
import { AuthService } from '../../servicios/auth/auth.service';

@Component({
  selector: 'app-panel',
  imports: [ButtonModule],
  templateUrl: './panel.html',
  styleUrl: './panel.scss',
})
export class Panel {
  
  private authService = inject(AuthService);
  private router = inject(Router);
  public logout(){
    this.authService.logout().subscribe(() => {
      this.router.navigate(['/login'])
    })
  }
}
