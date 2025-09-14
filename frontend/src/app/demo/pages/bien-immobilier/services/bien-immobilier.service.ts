// src/app/biens/services/bien-immobilier.service.ts
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { BienImmobilier } from '../models/bien-immobilier.model';
@Injectable({
  providedIn: 'root'
})
export class BienImmobilierService {
  private apiUrl = 'http://127.0.0.1:8000/api';

  constructor(private http: HttpClient) {}

  getAll(): Observable<BienImmobilier[]> {
    return this.http.get<BienImmobilier[]>(`${this.apiUrl}/biens`);
  }

  create(payload: any): Observable<BienImmobilier> {
    return this.http.post<BienImmobilier>(`${this.apiUrl}/biens`, payload);
  }

  update(id: number, payload: any): Observable<BienImmobilier> {
    return this.http.put<BienImmobilier>(`${this.apiUrl}/biens/${id}`, payload);
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/biens/${id}`);
  }

  getProprietaires(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/proprietaires`);
  }

  getTypes(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/types`);
  }

  getDepartements(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/departements`);
  }

  // Création avec fichier
  createWithFile(formData: FormData) {
    return this.http.post<BienImmobilier>(`${this.apiUrl}/biens`, formData);
  }

  // Mise à jour avec fichier
  updateWithFile(id: number, formData: FormData) {
    return this.http.post<BienImmobilier>(`${this.apiUrl}/biens/${id}`, formData);
  }

}
