import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class NotificationService {
  constructor() {}

  // Ici tu simules les notifications via Mercure ou autre API
  getVisiteNotifications(): Observable<any> {
    return new Observable(observer => {
      // Exemple : tu peux brancher ici EventSource (Mercure)
      const es = new EventSource('http://localhost:3000/.well-known/mercure?topic=visites');

      es.onmessage = (event) => {
        const data = JSON.parse(event.data);
        observer.next(data);
      };

      es.onerror = (err) => {
        observer.error(err);
      };

      return () => es.close();
    });
  }
}
