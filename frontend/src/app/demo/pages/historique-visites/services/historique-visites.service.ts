// src/app/demo/pages/services/historique-visites.service.ts
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface Visite {
  id: number;
  bienId: number;
  visiteurId: number | null;
  dateProgrammee: string;
  dateReelle: string | null;
  statut: string;
  commentaire: string | null;
  visiteur?: any;
  bien?: any;
}

@Injectable({
  providedIn: 'root'
})
export class HistoriqueVisitesService {
  private apiUrl = 'http://127.0.0.1:8000/api';

  constructor(private http: HttpClient) {}

  getAllVisites(): Observable<Visite[]> {
    return this.http.get<Visite[]>(`${this.apiUrl}/visites`);
  }
}
  