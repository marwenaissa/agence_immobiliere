// src/app/demo/pages/villes/services/villes.service.ts
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Ville } from '../models/ville.model';

@Injectable({
  providedIn: 'root' // important pour que Angular puisse l'injecter partout
})
export class VillesService {
  private apiUrl = 'http://127.0.0.1:8000/api/villes';

  constructor(private http: HttpClient) {}

  getAll(): Observable<Ville[]> {
    return this.http.get<Ville[]>(this.apiUrl);
  }

  create(ville: Ville): Observable<Ville> {
    return this.http.post<Ville>(this.apiUrl, ville);
  }

  update(id: number, ville: Ville): Observable<Ville> {
    return this.http.put<Ville>(`${this.apiUrl}/${id}`, ville);
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/${id}`);
  }

  
}
