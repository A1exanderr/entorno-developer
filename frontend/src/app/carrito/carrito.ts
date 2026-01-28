import { Component, OnInit } from '@angular/core';
import { Websocket } from '../websocket';
import { CommonModule } from '@angular/common';
@Component({
  selector: 'app-carrito',
  imports: [CommonModule],
  templateUrl: './carrito.html',
  styleUrl: './carrito.scss',
})
export class Carrito implements OnInit{
  carrito: any[] = [];
  constructor(private ws: Websocket) {}

  ngOnInit(): void {
    this.ws.conectar();
    this.ws.carrito$.subscribe(data => {
      this.carrito = data;
    });
  }

  agregar() {
    const producto = {
      id: Date.now(),
      nombre: 'Producto ' + this.carrito.length
    };
    this.ws.agregarProducto(producto);
  }

  eliminar(item: any) {
    this.ws.eliminarProducto(item);
  }
}
