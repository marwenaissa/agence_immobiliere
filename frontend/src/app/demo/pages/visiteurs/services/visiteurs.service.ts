import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Visiteur } from '../models/visiteur.model';

@Injectable({
  providedIn: 'root'
})
export class VisiteursService {
  private apiUrl = 'http://127.0.0.1:8000/api/visiteurs';

  constructor(private http: HttpClient) {}

  getAll(): Observable<Visiteur[]> {
    return this.http.get<Visiteur[]>(this.apiUrl);
  }

  create(visiteur: Visiteur): Observable<Visiteur> {
    return this.http.post<Visiteur>(this.apiUrl, visiteur);
  }

  update(id: number, visiteur: Visiteur): Observable<Visiteur> {
    return this.http.put<Visiteur>(`${this.apiUrl}/${id}`, visiteur);
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/${id}`);
  }
}
