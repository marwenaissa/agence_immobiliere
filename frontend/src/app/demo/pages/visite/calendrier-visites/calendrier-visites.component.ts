import { Component, OnInit } from '@angular/core';
import { FullCalendarModule } from '@fullcalendar/angular';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import { VisiteService, Visite } from '../services/visite.service';

@Component({
  selector: 'app-calendrier-visites',
  standalone: true,
  imports: [FullCalendarModule],
  templateUrl: './calendrier-visites.component.html'
})
export class CalendrierVisitesComponent implements OnInit {
  calendarOptions: any = {
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    events: []
  };

  constructor(private visiteService: VisiteService) {}

  ngOnInit(): void {
    this.loadVisites();
  }

  loadVisites() {
    this.visiteService.getAllVisites().subscribe(visites => {
      this.calendarOptions.events = visites.map(v => ({
        id: v.id,
        title: v.visiteur ? `${v.visiteur.prenom} ${v.visiteur.nom}` : `Visite ${v.id}`,
        start: v.dateProgrammee,
        end: v.dateReelle || v.dateProgrammee,
        statut: v.statut,
        borderColor: this.getColorByStatut(v.statut)
      }));
    });
  }

  getColorByStatut(statut: string): string {
    switch (statut) {
      case 'programmee': return '#3498db';
      case 'effectuee': return '#2ecc71';
      case 'annulee': return '#e74c3c';
      default: return '#95a5a6';
    }
  }
}
