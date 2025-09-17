import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Proprietaire } from '../models/proprietaire.model';

@Injectable({
  providedIn: 'root'
})
export class ProprietairesService {
  private apiUrl = 'http://127.0.0.1:8000/api/proprietaires';

  constructor(private http: HttpClient) {}

  getAll(): Observable<Proprietaire[]> {
    return this.http.get<Proprietaire[]>(this.apiUrl);
  }

  create(proprietaire: Proprietaire): Observable<Proprietaire> {
    return this.http.post<Proprietaire>(this.apiUrl, proprietaire);
  }

  update(id: number, proprietaire: Proprietaire): Observable<Proprietaire> {
    return this.http.put<Proprietaire>(`${this.apiUrl}/${id}`, proprietaire);
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/${id}`);
  }
}
