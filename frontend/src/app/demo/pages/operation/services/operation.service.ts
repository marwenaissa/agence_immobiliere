import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Operation } from '../models/operation.model';

@Injectable({
  providedIn: 'root'
})
export class OperationsService {
   private apiBase = 'http://127.0.0.1:8000/api'; // URL de base
    private apiUrl = `${this.apiBase}/operations`; // /api/operations

  constructor(private http: HttpClient) {}

    // ✅ Récupérer toutes les opérations
    getAll(): Observable<Operation[]> {
        return this.http.get<Operation[]>(this.apiUrl); // GET /api/operations
    }

    // ✅ Créer une nouvelle opération
    create(payload: any): Observable<Operation> {
        return this.http.post<Operation>(this.apiUrl, payload); // POST /api/operations
    }

    // ✅ Mettre à jour une opération existante
    update(id: number, payload: Partial<Operation>): Observable<Operation> {
        return this.http.put<Operation>(`${this.apiUrl}/${id}`, payload); // PUT /api/operations/{id}
    }

    // ✅ Supprimer une opération
    delete(id: number): Observable<void> {
        return this.http.delete<void>(`${this.apiUrl}/${id}`); // DELETE /api/operations/{id}
    }

    getProprietaires(): Observable<any[]> {
        return this.http.get<any[]>(`${this.apiBase}/proprietaires`);
    }

   getClient(): Observable<any[]> {
    return this.http.get<any[]>('http://127.0.0.1:8000/api/clients/');
    }



}
