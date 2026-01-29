import { Component, signal } from '@angular/core';
import { RouterOutlet } from '@angular/router';

import { ButtonModule } from 'primeng/button';
//importar componentes
import { Login } from './components/login/login';

@Component({
  selector: 'app-root',
  imports: [RouterOutlet, ButtonModule, Login],
  templateUrl: './app.html',
  styleUrl: './app.scss'
})
export class App {
  protected readonly title = signal('Principal');
}
