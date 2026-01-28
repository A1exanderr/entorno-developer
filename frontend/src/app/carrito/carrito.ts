import { Component, OnInit } from '@angular/core';
import { Websocket } from '../websocket';
import { CommonModule } from '@angular/common';
import { Observable } from 'rxjs';
@Component({
  selector: 'app-carrito',
  imports: [CommonModule],
  templateUrl: './carrito.html',
  styleUrl: './carrito.scss',
})
export class Carrito implements OnInit{
  carrito$!: Observable<any[]>;

  constructor(private ws: Websocket) {}

  ngOnInit(): void {
    this.ws.conectar();
    this.carrito$ = this.ws.carrito$;
  }

  agregar() {
    this.ws.agregarProducto({
      id: Date.now(),
      nombre: 'Producto ' + Date.now()
    });
  }

  eliminar(item: any) {
    this.ws.eliminarProducto(item);
  }
}
