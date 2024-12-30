import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { ExperienciasService } from '../experiencias.service';
import { ApiImagenService } from '../api-imagen.service';


@Component({
  selector: 'app-bienvenida',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './bienvenida.component.html',
  styleUrl: './bienvenida.component.css'
})
export class BienvenidaComponent {

  experiencias: any = [];
  imagenes: any = [];
  experienciasImprir: any = [];
  experienciasP = ['Mejor puntuada', 'Última ingresada'];

  obtenerPrimeraImagenPorExperiencia(experienciaId: number): any {
    return this.imagenes.find((img: any) => img.experiencia_id === experienciaId);
  }

  constructor(private router: Router, private experienciaServ: ExperienciasService,
    private apiImagen: ApiImagenService) {

    this.experienciaServ.retornar()
      .subscribe((result) => {
        this.experiencias = result;
        this.obtenerExperiencias(this.experiencias);
      });

    this.apiImagen.retornar()
      .subscribe((resultado: any) => { 
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

  obtenerExperiencias(experiencias: any) {
    var mejorValorada = experiencias[0];
    var ultimaIngresada = experiencias[0];

    experiencias.forEach((experiencia: any) => {
      
      if (experiencia.puntuacion > mejorValorada.puntuacion) {
        mejorValorada = experiencia;
      }
      
      if (experiencia.fecha > ultimaIngresada.fecha) {
        ultimaIngresada = experiencia;
      }
    });
    this.experienciasImprir.push(mejorValorada);
    this.experienciasImprir.push(ultimaIngresada);
  }
  generarIconosPuntuacion(puntuacion: string): any[] {
    const puntuacionNumero = parseInt(puntuacion, 10);
    return Array(puntuacionNumero).fill('');
  }

}
