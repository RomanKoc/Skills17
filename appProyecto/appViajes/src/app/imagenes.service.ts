import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class ImagenesService {

  constructor(private http: HttpClient) { }

  insertarImagen(imagen: any) {
    return this.http.post('http://127.0.0.1:8000/imagen/new', imagen);
  }
}
