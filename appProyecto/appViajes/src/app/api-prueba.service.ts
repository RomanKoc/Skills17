import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class ApiPruebaService {

  constructor(private http: HttpClient) { }
  retornar() {
    return this.http.get('http://127.0.0.1:8000/usuario/') 
  }
  insertarUsuario(usuario: any) {
    return this.http.post('http://127.0.0.1:8000/usuario/new', usuario);
  }
  modificarUsuario(usuario: any) {
    return this.http.post('http://127.0.0.1:8000/usuario/edit', usuario);
  }
  borrar(usuario: any) {
    return this.http.post('http://127.0.0.1:8000/usuario/delete', usuario);
  }
}
