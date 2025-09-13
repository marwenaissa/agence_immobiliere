// src/app/biens/services/piece-jointe.service.ts
import { Injectable } from '@angular/core';
import { HttpClient, HttpEvent, HttpRequest } from '@angular/common/http';
import { Observable } from 'rxjs';
import { PieceJointe } from '../models/piece-jointe.model';

@Injectable({
  providedIn: 'root'
})
export class PieceJointeService {
  private apiUrl = 'http://localhost:8000/api/piece-jointes';

  constructor(private http: HttpClient) {}

  getByBien(bienId: number): Observable<PieceJointe[]> {
    return this.http.get<PieceJointe[]>(`${this.apiUrl}?bien_id=${bienId}`);
  }

  uploadFiles(bienId: number, files: File[], descriptions: string[] = []): Observable<HttpEvent<any>> {
    const formData = new FormData();
    files.forEach((file, index) => {
      formData.append('files[]', file);
      if (descriptions[index]) {
        formData.append('descriptions[]', descriptions[index]);
      }
    });
    formData.append('bien_id', bienId.toString());

    const req = new HttpRequest('POST', this.apiUrl, formData, { reportProgress: true, responseType: 'json' });
    return this.http.request(req);
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/${id}`);
  }
}
