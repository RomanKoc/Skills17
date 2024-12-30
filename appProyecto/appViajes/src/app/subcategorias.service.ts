import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http'; // Import the HttpClient module

@Injectable({
  providedIn: 'root'
})
export class SubcategoriasService {

  constructor(private http: HttpClient) { }
  retornar() {
    return this.http.get('http://127.0.0.1:8000/subcategoria/')
  }
}
