import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Operation } from '../models/operation.model';

@Injectable({
  providedIn: 'root'
})
export class OperationsService {
  private apiUrl = 'http://127.0.0.1:8000/api/operations';

  constructor(private http: HttpClient) {}

  getAll(): Observable<Operation[]> {
    return this.http.get<Operation[]>(this.apiUrl);
  }

  create(payload: any): Observable<Operation> {
    return this.http.post<Operation>(this.apiUrl, payload);
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/${id}`);
  }
  
  // Mettre à jour une opération existante
  update(id: number, payload: Partial<Operation>): Observable<Operation> {
    return this.http.put<Operation>(`${this.apiUrl}/${id}`, payload);
  }

}
