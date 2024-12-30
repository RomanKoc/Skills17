import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { ExperienciasService } from '../experiencias.service';
import { ApiImagenService } from '../api-imagen.service';
import { ActivatedRoute, ParamMap } from '@angular/router';
import { ComentariosService } from '../comentarios-service.service';
import { FormControl, FormGroup } from '@angular/forms';


@Component({
  selector: 'app-experiencia-individual',
  standalone: true,
  imports: [ReactiveFormsModule, CommonModule],
  templateUrl: './experiencia-individual.component.html',
  styleUrl: './experiencia-individual.component.css'
})
export class ExperienciaIndividualComponent {

  /* id -> id de la experiencia */
  id = '0';
  experiencias: any = [];
  comentarios: any = [];
  experienciaUsuario: any;
  imagenes: any = [];
  userId: any;

  ngOnInit(): void {
    if (localStorage.getItem('userId')) {
      this.userId = localStorage.getItem('userId') ?? '0';
    } else {
      this.userId = 'not';
    }
  }

  longitudComentarios() {
    return this.comentarios.length;
  }

  constructor(private router: Router, private experienciaServ: ExperienciasService, private apiImagen: ApiImagenService, private parametroRuta: ActivatedRoute, private comentariosService: ComentariosService) {

    this.comentariosService.retornar()
      .subscribe((result) => {
        this.comentarios = result;
        /* console.log(this.comentarios); */
      })


    this.experienciaServ.retornar()
      .subscribe((result) => {
        /* console.log('resultExpr -> ', result); */
        this.experiencias = result;
      });

    this.apiImagen.retornar()
      .subscribe((resultado: any) => {
        this.imagenes = resultado
      });

    this.parametroRuta.paramMap.subscribe((params: ParamMap) => {
      /* console.log('paramETRO -> ', params.get('id')); */
      this.id = params.get('id')!
    });
    this.obtenerExperiencia(this.id);
  }

  /* ME QUEDA LA EXPERIENCIA */
  obtenerExperiencia(id: any) {
    const experieciaIndividual = this.experiencias.find((exp: any) => exp.usuario.id == id);
    if (experieciaIndividual) {
      this.experienciaUsuario = experieciaIndividual;
      return;
    }

    this.experienciaUsuario = '-1';
    return;
  }

  borrarComentario(id: any) {
    const comentario = {
      id: id
    }
    this.comentariosService.borrar(comentario)
      .subscribe((result) => {
        /* console.log('result -> ', result); */
        setTimeout(() => {
          window.location.reload();
        }, 100);
      });
  }

  formComent = new FormGroup({
    texto: new FormControl('')
  });

  registrarComentario() {
    const coment = {
      texto: this.formComent.value.texto,
      usuario_id: this.userId,
      experiencia_id: this.id
    };
    /* console.log('comentJSON -> ', coment); */
    this.comentariosService.insertarComentario(coment)
      .subscribe({
        next: (response) => {
          /* console.log('Comentario insertado correctamente:', response); */
          setTimeout(() => {
            window.location.reload();
          }, 100);
        },
        error: (error) => {
          /* console.error('Error al insertar comentario:', error); */
        }
      });
  }

  borrarExperiencia(id: any) {
    const experiencia = {
      id: id
    }
    this.experienciaServ.borrarExperiencia(experiencia)
      .subscribe({
        next: (response) => {
          /* console.log('Experiencia borrada correctamente:', response); */
          this.router.navigate(['/experiencias']);
        },
        error: (error) => {
          /* console.error('Error al borrar experiencia:', error); */
        }
      });
  }
  desactivarPorID() {
    if (this.userId != 'not') {
      return true;
    }
    return false;
  }
}

