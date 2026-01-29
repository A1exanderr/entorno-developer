import { Component, signal } from '@angular/core';
//ng prime
import { FormsModule } from '@angular/forms';
import { CardModule } from 'primeng/card';
import { FloatLabelModule } from 'primeng/floatlabel';
import { InputTextModule } from 'primeng/inputtext';
import { PasswordModule } from 'primeng/password';
import { ButtonModule } from 'primeng/button';
import { CheckboxModule } from 'primeng/checkbox';

import { InputGroupModule } from 'primeng/inputgroup';
import { InputGroupAddonModule } from 'primeng/inputgroupaddon';

@Component({
  selector: 'app-login',
  imports: [
    FormsModule,
    CardModule,
    FloatLabelModule,
    InputTextModule,
    PasswordModule,
    ButtonModule,
    CheckboxModule,
    InputGroupModule,
    InputGroupAddonModule
  ],
  templateUrl: './login.html',
  styleUrl: './login.scss',
})
export class Login {
  protected readonly title = signal('Login');
  email = '';
  password = '';
  remember = false;

  login() {
    console.log('Email:', this.email);
    console.log('Password:', this.password);
    console.log('Recordar:', this.remember);

    // Aquí luego conectas con tu backend
  }
}
