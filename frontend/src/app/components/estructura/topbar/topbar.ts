import { Component, inject } from '@angular/core';
import { RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
//prime
import { MenuItem } from 'primeng/api';
import { StyleClassModule } from 'primeng/styleclass';
//componente
import { Configuracion } from '../configuracion/configuracion';
//servicios
import { EstructuraService } from '../../../servicios/estructura/estructura.service';
@Component({
  selector: 'app-topbar',
  imports: [RouterModule, CommonModule, StyleClassModule ], //Configuracion
  templateUrl: './topbar.html',
  styleUrl: './topbar.scss',
})
export class Topbar {

  items!: MenuItem[];

  estructuraService = inject(EstructuraService);

  toggleDarkMode() {
    this.estructuraService.layoutConfig.update((state) => ({
      ...state,
      darkTheme: !state.darkTheme
    }));
  }
}
