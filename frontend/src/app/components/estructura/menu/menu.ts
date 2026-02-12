import { Component, inject, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
//prime
import { MenuItem } from 'primeng/api';
//componente
import { Menuitem } from '../menuitem/menuitem';
//importtar servicio
import { MenuService } from '../../../servicios/features/menu.service';
@Component({
  selector: 'app-menu',
  imports: [CommonModule, RouterModule, Menuitem],
  templateUrl: './menu.html',
  styleUrl: './menu.scss',
})
export class Menu {

    model: MenuItem[] = [];
    private menuService = inject(MenuService);
    private cd = inject(ChangeDetectorRef);

    ngOnInit() {
        this.menuService.menu().subscribe({
        next: (resp: any) => {
            //console.log(resp);
            this.model = resp;
            this.cd.detectChanges(); 
        },
        error: (err) => {
            console.log(err.error?.message || 'Datos incorrectos');
        }
        });
    }
}
