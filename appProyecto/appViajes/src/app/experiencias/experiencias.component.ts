import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { ReactiveFormsModule} from '@angular/forms';
import { Router } from '@angular/router';
import { ExperienciasService } from '../experiencias.service';
import { ApiImagenService } from '../api-imagen.service';
import { FormsModule } from '@angular/forms';


@Component({
  selector: 'app-experiencias',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, FormsModule],
  templateUrl: './experiencias.component.html',
  styleUrl: './experiencias.component.css'
})
export class ExperienciasComponent {

  userId = 'not';
  experiencias: any = [];
  imagenes: any = [];
  buscador = "";

  obtenerPrimeraImagenPorExperiencia(experienciaId: number): any {
    return this.imagenes.find((img: any) => img.experiencia_id === experienciaId);
  }

  ngOnInit(): void {
    if (localStorage.getItem('userId')) {
      this.userId = localStorage.getItem('userId') ?? '0';
    } else {
      this.userId = 'not';
    }
  }

  constructor(private router: Router, private experienciaServ: ExperienciasService,
    private apiImagen: ApiImagenService) {

    this.experienciaServ.retornar()
      .subscribe((result) => {
        /* console.log('result -> ', result); */
        this.experiencias = result;
      });

    this.apiImagen.retornar()
      .subscribe((resultado: any) => { 
        /* console.log('result -> ', resultado); */
        this.imagenes = resultado
      });
  }
  obtenerNombreImagenPorExperiencia(experiencia: any): string {
    const imagen = this.imagenes.find((img: any) => img.experiencia_id === experiencia.id);
    if (imagen && imagen.nombre) {
      return imagen.nombre;
    }
    return 'false';
  }
  prueba() {
    const id = 1;
    this.router.navigate(['/experiencia-nueva', id]).then(() => {
      window.location.reload();
    });
  }
  desactivarPorID() {
    if (this.userId != 'not') {
      return true;
    }
    return false;
  }

  comprobarExperiencia(usuario: string) {
    const usuariolw = usuario.toLowerCase();
    const buscadolw = this.buscador.toLowerCase();

    if (this.buscador == "") {
      return true;

    } else {
      if (usuariolw.includes(buscadolw)) {
        return true;
      }
    }
    return false;

  }

  generarIconosPuntuacion(puntuacion: string): any[] {
    const puntuacionNumero = parseInt(puntuacion, 10);
    return Array(puntuacionNumero).fill('');
  }

}
