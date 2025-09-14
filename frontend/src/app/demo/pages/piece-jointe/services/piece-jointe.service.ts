import { Injectable } from '@angular/core';
import { HttpClient, HttpRequest, HttpEvent } from '@angular/common/http';
import { Observable } from 'rxjs';
import { PieceJointe } from '../models/piece-jointe.model';

@Injectable()
export class PieceJointeService {
  private apiUrl = 'http://127.0.0.1:8000/api';

  constructor(private http: HttpClient) {}

  getByBien(bienId: number): Observable<PieceJointe[]> {
    return this.http.get<PieceJointe[]>(`${this.apiUrl}/biens/${bienId}/pieces`);
  }

  uploadFiles(bienId: number, files: File[], descriptions: string[]): Observable<HttpEvent<any>> {
    const formData = new FormData();
    files.forEach((file, i) => formData.append('files[]', file));
    descriptions.forEach(desc => formData.append('descriptions[]', desc));

    const req = new HttpRequest('POST', `${this.apiUrl}/biens/${bienId}/pieces`, formData, {
      reportProgress: true,
    });
    return this.http.request(req);
  }

  delete(pieceId: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/pieces/${pieceId}`);
  }
}
