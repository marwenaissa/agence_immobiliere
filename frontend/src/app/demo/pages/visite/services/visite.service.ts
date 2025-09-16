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

export interface Visiteur {
  id: number;
  nom: string;
  prenom: string;
}

export interface Bien {
  id: number;
  titre: string;
}

@Injectable({
  providedIn: 'root'
})
export class VisiteService {
  private apiUrl = 'http://127.0.0.1:8000/api';

  constructor(private http: HttpClient) {}

  getVisitesByBien(bienId: number) {
    return this.http.get<any[]>(`${this.apiUrl}/visites/bien/${bienId}`);
  }

  validerVisite(visiteId: number) {
    return this.http.put(`${this.apiUrl}/visites/${visiteId}/valider`, {});
  }


  getAllVisites(): Observable<Visite[]> {
    return this.http.get<Visite[]>(`${this.apiUrl}/visites`);
  }

  addVisite(visite: Partial<Visite>): Observable<Visite> {
    return this.http.post<Visite>(`${this.apiUrl}/visites`, visite);
  }

  updateVisite(visiteId: number, changes: Partial<Visite>): Observable<Visite> {
    return this.http.put<Visite>(`${this.apiUrl}/visites/${visiteId}`, changes);
  }

  getAllVisiteurs(): Observable<Visiteur[]> {
    return this.http.get<Visiteur[]>(`${this.apiUrl}/visiteurs`);
  }

  getAllBiens(): Observable<Bien[]> {
    return this.http.get<Bien[]>(`${this.apiUrl}/biens`);
  }
}
