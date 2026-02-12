import { Component, inject, ChangeDetectorRef } from '@angular/core';
//importtar servicio
import { RolesService } from '../../servicios/features/roles.service';

@Component({
  selector: 'app-roles',
  imports: [],
  templateUrl: './roles.html',
  styleUrl: './roles.scss',
})
export class Roles {
  private rolesService = inject(RolesService);
  private cd = inject(ChangeDetectorRef);

  public datos_roles: any[] = [];
  ngOnInit() {
        /* this.menuService.menu().subscribe({
        next: (resp: any) => {
            console.log(resp);
            this.model = resp;
            this.cd.detectChanges(); 
        },
        error: (err) => {
            console.log(err.error?.message || 'Datos incorrectos');
        }
        }); */
      console.log("aqui");
    }
}
