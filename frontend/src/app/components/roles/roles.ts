import { Component, inject, ChangeDetectorRef } from '@angular/core';
//importtar servicio
import { RolesService } from '../../servicios/features/roles.service';
//ngprime
import { TableModule } from 'primeng/table';
import { ButtonModule } from 'primeng/button';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-roles',
  imports: [CommonModule, TableModule, ButtonModule],
  templateUrl: './roles.html',
  styleUrl: './roles.scss',
})
export class Roles {
  private rolesService = inject(RolesService);
  private cd = inject(ChangeDetectorRef);

  public datos_roles: any[] = [];
  ngOnInit() {
    this.rolesService.listar().subscribe({
      next: (resp: any) => {
        //console.log(resp);
        this.datos_roles = resp;
        this.cd.detectChanges(); 
      },
      error: (err) => {
        console.log(err.error?.message || 'Datos incorrectos');
      }
    });
  }

  public editarRol(id:number)
  {}
  public eliminarRol(id:number)
  {
    console.log(id);
    this.rolesService.eliminar({id}).subscribe({
      next: (resp: any) => {
        //console.log(resp);
        this.datos_roles = resp;
        this.cd.detectChanges(); 
      },
      error: (err) => {
        console.log(err.error?.message || 'Datos incorrectos');
      }
    });
  }
}
