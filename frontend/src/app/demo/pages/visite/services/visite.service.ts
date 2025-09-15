import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface Visite {
  id: number;
  visiteurId: number | null;
  dateProgrammee: string;
  dateReelle: string | null;
  statut: string;
  commentaire: string | null;
}

@Injectable({
  providedIn: 'root'
})
export class VisiteService {
  private apiUrl = 'http://127.0.0.1:8000/api/biens';

  constructor(private http: HttpClient) {}

  getVisites(bienId: string): Observable<Visite[]> {
    return this.http.get<Visite[]>(`${this.apiUrl}/${bienId}/visites`);
  }

  addVisite(bienId: string, visite: Partial<Visite>): Observable<Visite> {
    return this.http.post<Visite>(`${this.apiUrl}/${bienId}/visites`, visite);
  }
  updateVisite(bienId: string, visiteId: number, changes: Partial<Visite>) {
    return this.http.put<Visite>(`http://127.0.0.1:8000/api/biens/${bienId}/visites/${visiteId}`, changes);
    }



}
