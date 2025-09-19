import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

// Interfaces
export interface Visite {
  id: number;
  bienId: number;
  visiteurId: number | null;
  dateProgrammee: string;
  dateReelle: string | null;
  statut: string;
  commentaire: string | null;
  visiteur?: Visiteur;
  bien?: Bien;
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
export class VisitePlanifier {
  private apiUrl = 'http://127.0.0.1:8000/api';

  constructor(private http: HttpClient) {}

  // Récupérer toutes les visites
  getAllVisites(): Observable<Visite[]> {
    return this.http.get<Visite[]>(`${this.apiUrl}/visites`);
  }

  // Récupérer toutes les visites d'un bien spécifique
  getVisitesByBien(bienId: number | string): Observable<Visite[]> {
    return this.http.get<Visite[]>(`${this.apiUrl}/bien/${bienId}`);
  }

  // Ajouter une visite pour un bien
  addVisiteBien(visite: Partial<Visite>): Observable<Visite> {
    return this.http.post<Visite>(`${this.apiUrl}/visites/add`, visite);
  }

  // Modifier une visite
  updateVisite(visiteId: number, changes: Partial<Visite>): Observable<Visite> {
    return this.http.put<Visite>(`${this.apiUrl}/visites/${visiteId}`, changes);
  }

  // Modifier le statut d'une visite
  updateVisiteBien(visiteId: number, statut: string): Observable<Visite> {
    return this.http.put<Visite>(`${this.apiUrl}/visites/${visiteId}`, { statut });
  }

  // Valider une visite (envoi uniquement de l'ID)
  validerVisite(visiteId: number): Observable<any> {
    return this.http.put(`${this.apiUrl}/visites/valider`, { visiteId });
  }

  // Récupérer tous les biens
  getAllBiens(): Observable<Bien[]> {
    return this.http.get<Bien[]>(`${this.apiUrl}/biens`);
  }

  // Récupérer tous les visiteurs
  getAllVisiteurs(): Observable<Visiteur[]> {
    return this.http.get<Visiteur[]>(`${this.apiUrl}/visiteurs`);
  }
}
