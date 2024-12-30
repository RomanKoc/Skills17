import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterOutlet } from '@angular/router';
import { ApiPruebaService } from './api-prueba.service';
import { Router } from '@angular/router';
import Swal from 'sweetalert2';


@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, RouterOutlet],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {

  userId = 'not';
  usuarios: any;
  usuario: any;

  /* comprobar si hay sesion en storage */
  ngOnInit(): void {
    if (localStorage.getItem('userId')) {
      this.userId = localStorage.getItem('userId') ?? '0';
    } else {
      this.userId = 'not';
    }
  }

  cerrarSesion() {
    localStorage.removeItem('userId');
    this.userId = 'not';
    /* this.alertaSimple(); corregir no funciona como debe */
    this.router.navigate(['/']);

  }



  /* busco usuario en api, le comparo con id del sotrage y  */
  constructor(private usuariosService: ApiPruebaService, private router: Router) {
    this.usuariosService.retornar()
      .subscribe((result) => {
        /* console.log('result -> ', result); */
        this.usuarios = result;
        if (this.userId != 'not') {
          this.usuarios.forEach((user: any) => {
            if (user.id == this.userId) {
              this.usuario = user;
            }
          });
        }
      });
  }
  /* si no tenemos iniciada la sesion desactivar un campo devolviendo false, si no true */
  desactivarPorID() {
    if (this.userId != 'not') {
      return true;
    }
    return false;
  }
  alertaSimple() {
    Swal.fire('Session cerrada', 'Hasta pronto', 'success');
  }
}
