import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class ExperienciasService {

  constructor(private http: HttpClient) { }
  retornar() {
    return this.http.get('http://127.0.0.1:8000/experiencia/')
  }
  insertarExperiencia(experiencia: any) {
    return this.http.post('http://127.0.0.1:8000/experiencia/new', experiencia);
  }
  borrarExperiencia(experiencia: any) {
    return this.http.post('http://127.0.0.1:8000/experiencia/borrar', experiencia);
  }
}
