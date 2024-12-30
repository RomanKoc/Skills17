import { Component } from '@angular/core';
import { ReactiveFormsModule, FormControl, FormGroup, Validators, FormsModule, MaxLengthValidator } from '@angular/forms';
import { ExperienciasService } from '../experiencias.service';
import { LocalizacionService } from '../localizacion.service';
import { ImagenesService } from '../imagenes.service';
import { SubcategoriasService } from '../subcategorias.service';
import { NgClass } from '@angular/common';
import { Router } from '@angular/router';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-experiencia-nueva',
  standalone: true,
  imports: [ReactiveFormsModule, FormsModule, NgClass],
  templateUrl: './experiencia-nueva.component.html',
  styleUrl: './experiencia-nueva.component.css'
})
export class ExperienciaNuevaComponent {

  userId: any;
  localizaciones: any;
  categorias: any;
  subcategorias: any;
  categoriaSeleccionada: any;

  formularioExperiencia = new FormGroup({
    titulo: new FormControl('', [Validators.required]),
    texto: new FormControl('', [Validators.required]),
    puntuacion: new FormControl('', [Validators.required]),
    fecha: new FormControl('', [Validators.required]),
    localizacion: new FormControl(''),
    subcategoria: new FormControl(''),
  });

  determinarInputs(inputControl: any) {
    return inputControl.errors?.["required"] ? 'is-invalid' : 'is-valid';
  }

  constructor(private localizacionService: LocalizacionService, private experienciaService: ExperienciasService,
    private subcategoriaService: SubcategoriasService, private router: Router) {
    this.localizacionService.retornar()
      .subscribe((result) => {
        this.localizaciones = result;
      });

    this.subcategoriaService.retornar()
      .subscribe((result) => {
        this.subcategorias = result;
      });
  }

  ngOnInit(): void {
    this.userId = localStorage.getItem('userId');
  }

  sacarIdLocalizacion(localizacionFORM: any) {
    /* console.log('localizacionFORM -> ', localizacionFORM); */
    const localizacionEncontrada = this.localizaciones.find((local: any) => localizacionFORM == local.nombre);
    if (localizacionEncontrada) {
      return localizacionEncontrada.id;
    }
    return 1;
  }

  registrarExperiencia() {
    let idloc = 1;
    let subcategoriaId: any = 1;

    if (this.formularioExperiencia.value.localizacion) {
      idloc = this.sacarIdLocalizacion(this.formularioExperiencia.value.localizacion);
    }
    if (this.formularioExperiencia.value.subcategoria) {
      subcategoriaId = this.formularioExperiencia.value.subcategoria;
    }

    const experiencia = {
      titulo: this.formularioExperiencia.value.titulo,
      texto: this.formularioExperiencia.value.texto,
      puntuacion: this.formularioExperiencia.value.puntuacion,
      fecha: this.formularioExperiencia.value.fecha,
      usuarioId: this.userId,
      localizacionId: idloc,
      subcategoriaId: subcategoriaId,
    };

    this.experienciaService.insertarExperiencia(experiencia)
      .subscribe({
        next: (response) => {
          this.alertaSimple();
          this.router.navigate(['/experiencias']).then(() => {
          });
          this.alertaSimple();
        },
        error: (error) => {
          console.error('Error al insertar experiencia:', error);
        }
      });

    this.formularioExperiencia.reset();
  }

  onSelectCategoria(categoriaId: number) {
    const categoria = this.categorias.find((c: any) => c.id === categoriaId);
    this.subcategorias = categoria ? categoria.subcategorias : [];
  }
  alertaSimple() {
    Swal.fire('Experiencia registrada', 'La experiencia ha sido registrada con éxito', 'success');
  }
}