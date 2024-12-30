import { Component } from '@angular/core';
import { ApiPruebaService } from '../api-prueba.service';
import { Router } from '@angular/router';
import { ReactiveFormsModule, FormGroup, FormControl } from '@angular/forms';
import * as CryptoJS from 'crypto-js';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-info-usuario',
  standalone: true,
  imports: [ReactiveFormsModule],
  templateUrl: './info-usuario.component.html',
  styleUrl: './info-usuario.component.css'
})

export class InfoUsuarioComponent {

  userId = '0';
  usuario: any = {};
  usuarios: any;

  ngOnInit(): void {
    if (localStorage.getItem('userId')) {
      this.userId = localStorage.getItem('userId') ?? '0';
    } else {
      this.userId = 'not';
    }
  }

  constructor(private usuariosService: ApiPruebaService, private router: Router) {
    this.usuariosService.retornar()
      .subscribe((result) => {
        /* console.log('result -> ', result); */
        this.usuarios = result;
        this.usuarios.forEach((user: any) => {
          if (user.id == this.userId) {
            this.usuario = user;
            return;
          }
        });

      });
  }
  cerrarSesion() {
    localStorage.removeItem('userId');
    this.userId = 'not';
  }
  borrarUsuario() {
    const usuario = {
      id: this.userId,
    };
    this.usuariosService.borrar(usuario)
      .subscribe({
        next: (response) => {
          this.cerrarSesion();
          this.alertaBorrar();
          setTimeout(() => {
            this.router.navigate(['/']).then(() => {
              window.location.reload();
              return;
            });
          }, 1500);
        },
        error: (error) => {
          console.log('Error al BORRAR usuario:', error);
        }
      })
  };

  formularioRegistro = new FormGroup({
    nombre: new FormControl(''),
    apellidos: new FormControl(''),
    mail: new FormControl(''),
    ciudad: new FormControl(''),
    password: new FormControl(''),
  });
  encriptarPasswd() {
    const passwordValue = this.formularioRegistro.value.password;
    if (passwordValue) {
      const passwordEncriptada = CryptoJS.SHA256(passwordValue).toString();
      this.formularioRegistro.patchValue({ password: passwordEncriptada });
    }
  }

  modificarUsuario() {
    this.encriptarPasswd();
    const usuario = {
      id: this.userId,
      nombre: this.formularioRegistro.get('nombre')?.value ?? '',
      apellidos: this.formularioRegistro.get('apellidos')?.value ?? '',
      mail: this.formularioRegistro.get('mail')?.value ?? '',
      ciudad: this.formularioRegistro.get('ciudad')?.value ?? '',
      password: this.formularioRegistro.get('password')?.value ?? ''
    };
    this.usuariosService.modificarUsuario(usuario)
      .subscribe({
        next: (response) => {
          this.alertaSimple();
          setTimeout(() => {
            this.router.navigate(['/info-usuario']).then(() => {
              window.location.reload();
              return;
            });
          }, 1200);
        },
        error: (error) => {
          console.error('Error al ACTUALIZAR usuario:', error);
          /* alert('Error al actualizar usuario'); */
        }
      });

    this.formularioRegistro.reset();
  }
  alertaSimple() {
    Swal.fire('Informacion actualizada correctamente', 'Puede continuar', 'success');
  }
  alertaBorrar() {
    Swal.fire('Se ha borrado el usuario', 'Hasta la próxima', 'success');
  }

}
