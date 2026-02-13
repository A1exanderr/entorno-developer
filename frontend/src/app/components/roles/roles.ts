import { Component, inject, ChangeDetectorRef, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
//importtar servicio
import { RolesService } from '../../servicios/features/roles.service';
//ngprime
import { TableModule } from 'primeng/table';
import { ButtonModule } from 'primeng/button';
import { CommonModule } from '@angular/common';
//ng-prime editar
import { Dialog, DialogModule } from 'primeng/dialog';
import { InputTextModule } from 'primeng/inputtext';

@Component({
  selector: 'app-roles',
  imports: [CommonModule, FormsModule, TableModule, ButtonModule, DialogModule, InputTextModule],
  templateUrl: './roles.html',
  styleUrl: './roles.scss',
})
export class Roles {
  private rolesService = inject(RolesService);
  private cd = inject(ChangeDetectorRef);

  //public datos_roles: any[] = [];
  public datos_roles = signal<any[]>([]);
  ngOnInit() {
    this.rolesService.listar().subscribe({
      next: (resp: any) => {
        //console.log(resp);
        //this.datos_roles = resp;
        this.datos_roles.set(resp);
        this.cd.detectChanges(); 
      },
      error: (err) => {
        console.log(err.error?.message || 'Datos incorrectos');
      }
    });
  }

  public eliminarRol(id:number)
  {
    //console.log(id);
    this.rolesService.eliminar({id}).subscribe({
      next: (resp: any) => {
        /* this.datos_roles = this.datos_roles.filter(r => r.id !== id);
        this.cd.detectChanges(); */
        this.datos_roles.update(roles =>
          roles.filter(r => r.id !== id)
        );
        //console.log(resp);
      },
      error: (err) => {
        console.log(err.error?.message || 'Datos incorrectos');
      }
    });
  }

  public modalEditar: boolean = false;
  public rolSeleccionado: any = {};
  public editarRol(rol:any)
  {
    this.rolSeleccionado = { ...rol }; 
    this.modalEditar = true;
  }
  actualizarRol() {

    this.datos_roles.update(lista =>
      lista.map(r =>
        r.id === this.rolSeleccionado.id
          ? { ...this.rolSeleccionado }
          : r
      )
    );

    this.modalEditar = false;
  }
}
/* 
Ricardo pollo 25
David  pollo 25
Noemi pollo 25
Alex pollo 25
Brayan chancho 40
Rodrigo chancho 40 cancelado
Carlos enrique 25 cancelado
Briamar salas 30 
====
*/