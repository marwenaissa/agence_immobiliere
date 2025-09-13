// src/app/biens/services/bien-immobilier.service.ts
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { BienImmobilier } from '../models/bien-immobilier.model';

@Injectable({
  providedIn: 'root'
})
export class BienImmobilierService {
  private apiUrl = 'http://localhost:8000/api/biens'; // adapte selon ton backend

  constructor(private http: HttpClient) {}

  getAll(): Observable<BienImmobilier[]> {
    return this.http.get<BienImmobilier[]>(this.apiUrl);
  }

  create(payload: any): Observable<BienImmobilier> {
    return this.http.post<BienImmobilier>(this.apiUrl, payload);
  }

  update(id: number, payload: any): Observable<BienImmobilier> {
    return this.http.put<BienImmobilier>(`${this.apiUrl}/${id}`, payload);
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/${id}`);
  }
}
