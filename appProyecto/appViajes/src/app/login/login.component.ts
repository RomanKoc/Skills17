import { Component } from '@angular/core';
import { ApiPruebaService } from '../api-prueba.service';
import { ReactiveFormsModule, FormGroup, FormControl, Validators } from '@angular/forms';
import * as CryptoJS from 'crypto-js'; // Importar crypto-js
import { Router } from '@angular/router';
import { NgClass } from '@angular/common';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [ReactiveFormsModule, NgClass],
  templateUrl: './login.component.html',
  styleUrl: './login.component.css'
})
export class LoginComponent {

  id = 0;
  usuarios: any;
  tipoPasswd: string = 'password';


  constructor(private usuarioService: ApiPruebaService, private router: Router) {
    this.usuarioService.retornar()
      .subscribe((result) => {
        /*  console.log('result -> ', result); */
        this.usuarios = result
      });
  }

  formularioRegistro = new FormGroup({
    mail: new FormControl('', [Validators.required]),
    password: new FormControl('', [Validators.required]),
  });

  /* cifrar el id del usuario para almacenarlo en localsotrage y usarlo de sesion */
  cifrar(id: number): string {
    const cifrado = CryptoJS.SHA256(id.toString());
    return cifrado.toString(CryptoJS.enc.Hex); // Devuelve el cifrado en formato hexadecimal
  }

  /* el usuario que introduce el pass se debe comparar con el de la bdd y hay que cifrarlo */
  encriptarPasswd() {
    const passwordValue = this.formularioRegistro.value.password;
    if (passwordValue) {
      const passwordEncriptada = CryptoJS.SHA256(passwordValue).toString();
      // Actualiza el valor del passwd en el formulario:
      this.formularioRegistro.patchValue({ password: passwordEncriptada });
    }
  }
  desencriptarPasswd(passwordEncriptada: string) {
    return CryptoJS.SHA256(passwordEncriptada).toString();
  }

  /* buscar usaurio y mirar si tiene la misma pass, se busca por correo */
  comprobarUsuario() {
    this.usuarios.forEach((usuario: any) => {
      if (usuario.mail === this.formularioRegistro.value.mail) {
        /* console.log('Usuario encontrado:', usuario); */
        this.encriptarPasswd();
        const passwordDesencriptada = this.desencriptarPasswd(usuario.password);
        if (this.formularioRegistro.value.password == passwordDesencriptada) {
          this.id = usuario.id;
          localStorage.setItem('userId', this.id.toString());
          this.alertaSimple();
          setTimeout(() => {
            this.router.navigate(['/']).then(() => {
                window.location.reload();
              return;
            });
          }, 1200);
        }

      }else{
        this.alertaError();
      }
    });
  }

  iniciarSesion() {
    this.encriptarPasswd();
    this.comprobarUsuario();
    this.formularioRegistro.reset();
  }

  mostrarPassw() {
    this.tipoPasswd = (this.tipoPasswd === 'password') ? 'text' : 'password';
  }
  determinarInputs(inputControl: any) {
    return inputControl.errors?.["required"] ? 'is-invalid' : 'is-valid';
  }
  alertaSimple() {
    Swal.fire('Sesion iniciada correctamente', 'Bienvenido', 'success');
  }
  alertaError() {
    Swal.fire('Error', 'Contraseña incorrecta', 'error');
  }
}
/*  */