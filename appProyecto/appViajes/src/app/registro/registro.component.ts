import { Component } from '@angular/core';
import { ApiPruebaService } from '../api-prueba.service';
import { ReactiveFormsModule, FormGroup, FormControl, Validators } from '@angular/forms';
import * as CryptoJS from 'crypto-js'; // Importar crypto-js
import { Router } from '@angular/router';
import { NgClass } from '@angular/common';
import Swal from 'sweetalert2';


@Component({
  selector: 'app-registro',
  standalone: true,
  imports: [ReactiveFormsModule, NgClass],
  templateUrl: './registro.component.html',
  styleUrl: './registro.component.css'
})
export class RegistroComponent {
  tipoPasswd: string = 'password';

  constructor(private usuarioService: ApiPruebaService, private router: Router) { }

  formularioRegistro = new FormGroup({
    nombre: new FormControl('',[Validators.required]),
    apellidos: new FormControl('',[Validators.required]),
    mail: new FormControl('',[Validators.required]),
    ciudad: new FormControl('',[Validators.required]),
    password: new FormControl('',[Validators.required]),
  });

  encriptarPasswd() {
    const passwordValue = this.formularioRegistro.value.password;
    if (passwordValue) {
      const passwordEncriptada = CryptoJS.SHA256(passwordValue).toString();
      // Actualiza el valor del campo password en el formulario:
      this.formularioRegistro.patchValue({ password: passwordEncriptada });
    }
  }
  registrarUsuario() {
    this.encriptarPasswd();
    const usuario = {
      nombre: this.formularioRegistro.value.nombre,
      apellidos: this.formularioRegistro.value.apellidos,
      mail: this.formularioRegistro.value.mail,
      ciudad: this.formularioRegistro.value.ciudad,
      password: this.formularioRegistro.value.password
    };
    this.usuarioService.insertarUsuario(usuario)
      .subscribe({
        next: (response) => {
          this.alertaSimple();
          setTimeout(() => {
            this.router.navigate(['/login']).then(() => {
              // Recargar la página
                window.location.reload();
              return;
            });
          }, 1200);
        },
        error: (error) => {
          console.error('Error al insertar usuario:', error);
          /* alert('Error al insertar usuario'); */
        }
      });

    this.formularioRegistro.reset();
  }
  mostrarPassw() {
    this.tipoPasswd = (this.tipoPasswd === 'password') ? 'text' : 'password';
  }
  determinarInputs(inputControl: any) {
    return inputControl.errors?.["required"] ? 'is-invalid' : 'is-valid';
  }
  alertaSimple() {
    Swal.fire('Usuario registrado correctamente', 'Puede iniciar sesión', 'success');
  }
}