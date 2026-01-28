import { Injectable, NgZone } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
@Injectable({
  providedIn: 'root',
})
export class Websocket {
  private ws!: WebSocket;

  // Estado del carrito
  carrito$ = new BehaviorSubject<any[]>([]);

  constructor(private zone: NgZone) {}

  conectar() {
    this.ws = new WebSocket('ws://localhost:3000');

    this.ws.onmessage = (msg) => {
      const data = JSON.parse(msg.data);

      // 🔥 Forzar actualización de Angular
      this.zone.run(() => {
        this.carrito$.next(data.carrito);
      });
    };
  }

  agregarProducto(item: any) {
    this.ws.send(JSON.stringify({ action: 'agregar', item }));
  }

  eliminarProducto(item: any) {
    this.ws.send(JSON.stringify({ action: 'eliminar', item }));
  }
}
