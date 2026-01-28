import { Injectable, NgZone } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
@Injectable({
  providedIn: 'root',
})
export class Websocket {
  private ws!: WebSocket;
  private carritoSubject = new BehaviorSubject<any[]>([]);
  carrito$ = this.carritoSubject.asObservable();

  constructor(private zone: NgZone) {}

  conectar() {
    this.ws = new WebSocket('ws://localhost:3000');

    this.ws.onmessage = (event) => {
      const data = JSON.parse(event.data);

      if (data.type === 'carrito') {
        this.zone.run(() => {
          // 🔥 nueva referencia
          this.carritoSubject.next([...data.carrito]);
        });
      }
    };
  }

  agregarProducto(item: any) {
    this.ws.send(JSON.stringify({ type: 'agregar', item }));
  }

  eliminarProducto(item: any) {
    this.ws.send(JSON.stringify({ type: 'eliminar', item }));
  }
}
