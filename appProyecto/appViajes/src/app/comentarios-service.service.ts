import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})

export class ComentariosService {

  constructor(private http: HttpClient) { }

  retornar() {
    return this.http.get('http://127.0.0.1:8000/comentario/')
  }

  borrar(comentario: any) {
    return this.http.post('http://127.0.0.1:8000/comentario/borrar', comentario);
  }

  insertarComentario(comentario: any) {
    return this.http.post('http://127.0.0.1:8000/comentario/new', comentario);
  }

}