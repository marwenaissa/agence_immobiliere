import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Departement } from '../models/departement.model';

@Injectable({
  providedIn: 'root'
})
export class DepartementsService {
  private apiUrl = 'http://localhost:8000/api/departements'; // adapte selon ton backend

  constructor(private http: HttpClient) {}

  getAll(): Observable<Departement[]> {
    return this.http.get<Departement[]>(this.apiUrl);
  }

  create(payload: any): Observable<Departement> {
    return this.http.post<Departement>(this.apiUrl, payload);
  }

  update(id: number, payload: any): Observable<Departement> {
    return this.http.put<Departement>(`${this.apiUrl}/${id}`, payload);
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/${id}`);
  }
}
