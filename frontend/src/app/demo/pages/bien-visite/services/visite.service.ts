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

  

  // Valider une visite en envoyant l'ID dans le corps
  validerVisite(visiteId: number) {
    return this.http.put(`${this.apiUrl}/visites/valider`, { visiteId });
}

  

  
  

  updateVisite(visiteId: number, changes: Partial<Visite>): Observable<Visite> {
    return this.http.put<Visite>(`${this.apiUrl}/visites/${visiteId}`, changes);
  }


  getAllBiens(): Observable<Bien[]> {
    return this.http.get<Bien[]>(`${this.apiUrl}/biens`);
  }

  
 // Récupérer toutes les visites d'un bien spécifique (ID en body)
  // Récupérer toutes les visites d'un bien spécifique
  getVisitesByBien(bienId: number | string): Observable<Visite[]> {
    return this.http.get<Visite[]>(`${this.apiUrl}/bien/${bienId}`);
  }

  

  // Ajouter une visite (tous biens, bienId à l'intérieur de visite)
  addVisiteBien(visite: Partial<Visite>): Observable<Visite> {
    return this.http.post<Visite>(`http://127.0.0.1:8000/api/visites`, visite);
  }

  /* addVisite(visite: Partial<Visite>): Observable<Visite> {
    return this.http.post<Visite>(`${this.apiUrl}/visites`, visite);
  } */

  // Modifier le statut d'une visite
  updateVisiteBien(visiteId: number, statut: string): Observable<Visite> {
    return this.http.put<Visite>(`http://127.0.0.1:8000/api/visites/${visiteId}`, { statut });
  }

  // Récupérer tous les visiteurs
  getAllVisiteurs(): Observable<Visiteur[]> {
    return this.http.get<Visiteur[]>('http://127.0.0.1:8000/api/visiteurs');
  }

}
