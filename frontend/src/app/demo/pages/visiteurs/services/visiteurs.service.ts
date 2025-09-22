import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Visiteur,ApiResponse } from '../models/visiteur.model';

@Injectable({
  providedIn: 'root'
})
export class VisiteursService {
  private apiUrl = 'http://127.0.0.1:8000/api/visiteurs';

  constructor(private http: HttpClient) {}

  getAll(page: number = 1, limit: number = 5): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}?page=${page}&limit=${limit}`);
  }
create(visiteur: Visiteur): Observable<ApiResponse> {
  return this.http.post<ApiResponse>(`${this.apiUrl}/create`, visiteur);
}

  update(id: number, visiteur: Visiteur): Observable<Visiteur> {
    return this.http.put<Visiteur>(`${this.apiUrl}/${id}`, visiteur);
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/${id}`);
  }
}
