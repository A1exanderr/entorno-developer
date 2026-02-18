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
//crear
import { CheckboxModule } from 'primeng/checkbox';

@Component({
  selector: 'app-roles',
  imports: [CommonModule, FormsModule, TableModule, ButtonModule, DialogModule, InputTextModule, CheckboxModule],
  templateUrl: './roles.html',
  styleUrl: './roles.scss',
})
export class Roles {
  private rolesService = inject(RolesService);
  private cd = inject(ChangeDetectorRef);

  //public datos_roles: any[] = [];
  public datos_roles = signal<any[]>([]);
  ngOnInit() {
    this.cargarRoles();
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

  public modalCrear = false;
  public permisos: any[] = [];
  public rol:string = '';

  public crear(){
    this.rol = '';
    this.modalCrear = true;
    this.rolesService.obtenerPermisos().subscribe({
      next: (resp: any) => {
        //console.log(resp);
        this.permisos = resp;
        this.cd.detectChanges(); 
      },
      error: (err) => {
        console.log(err.error?.message || 'Datos incorrectos');
      }
    });
  }
  public guardarRol(){

    if (!this.rol || this.rol.trim() === '') {
      console.log("Debe ingresar un nombre de rol");
      return;
    }

    const permisosSeleccionados = this.permisos
      .filter(p => p.asignado)
      .map(p => p.id);

    this.rolesService.crear({
      rol: this.rol.trim(),
      permisos: permisosSeleccionados
    }).subscribe({
      next: (resp: any) => {
        this.cargarRoles();
      },
      error: (err) => {
        console.log(err.error?.message || 'Datos incorrectos');
      }
    });
    this.rol = "";
    this.modalCrear = false;
  }

  private cargarRoles() {
    this.rolesService.listar().subscribe({
      next: (resp: any) => {
        this.datos_roles.set(resp);
      },
      error: (err) => {
        console.log(err.error?.message || 'Error cargando roles');
      }
    });
  }

  public modalEditar: boolean = false;
  public rolSeleccionado: any = {};
  public permisos_user: any[] = [];
  public editarRol(rol:any)
  {
    this.rolSeleccionado = { ...rol };
    this.modalEditar = true;
    this.rolesService.obtenerRolesPermisos({"id":rol.id}).subscribe({
      next: (resp: any) => {
        //console.log(resp);
        this.permisos_user = resp;
        console.log(resp);
        //this.permisos = resp;
        
        this.cd.detectChanges(); 
      },
      error: (err) => {
        console.log(err.error?.message || 'Datos incorrectos');
      }
    });
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
this.rolesService.crear().subscribe({
  next: (resp: any) => {
    console.log(resp); 
  },
  error: (err) => {
    console.log(err.error?.message || 'Datos incorrectos');
  }
}); 
*/
/* 
Ricardo pollo 25 + 10 cancelado
David  pollo 25 cancelado
Alex pollo 25 cancelado
Brayan chancho 40 cancelado
Briamar salas 30 cancelado
Rodrigo chancho 40 cancelado
Carlos enrique 25 cancelado
Noemi pollo 25 cancelado
====
*/