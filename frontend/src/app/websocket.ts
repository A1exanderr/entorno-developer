import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
@Injectable({
  providedIn: 'root',
})
export class Websocket {
  private ws!: WebSocket;

  // Estado del carrito
  carrito$ = new BehaviorSubject<any[]>([]);

  conectar() {
    this.ws = new WebSocket('ws://localhost:3000');

    this.ws.onopen = () => {
      console.log('WebSocket conectado');
    };

    this.ws.onmessage = (msg) => {
      const data = JSON.parse(msg.data);
      this.carrito$.next(data.carrito);
    };

    this.ws.onerror = (err) => {
      console.error('Error WebSocket', err);
    };
  }

  agregarProducto(producto: any) {
    this.ws.send(JSON.stringify({
      action: 'agregar',
      item: producto
    }));
  }

  eliminarProducto(producto: any) {
    this.ws.send(JSON.stringify({
      action: 'eliminar',
      item: producto
    }));
  }
}
