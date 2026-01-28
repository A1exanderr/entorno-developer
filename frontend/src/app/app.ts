import { Component, signal } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import {Carrito} from './carrito/carrito';

@Component({
  selector: 'app-root',
  imports: [RouterOutlet, Carrito],
  templateUrl: './app.html',
  styleUrl: './app.scss'
})
export class App {
  protected readonly title = signal('frontend');
}
