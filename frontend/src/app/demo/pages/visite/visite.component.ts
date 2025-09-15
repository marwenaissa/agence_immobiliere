import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FullCalendarModule } from '@fullcalendar/angular';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { VisiteService } from './services/visite.service';
import { Visite } from './models/visite.model';
import { Visiteur } from './models/visite.model';

@Component({
  selector: 'app-visite',
  standalone: true,
  imports: [
    CommonModule,
    FullCalendarModule,
    HttpClientModule,
    FormsModule
  ],
  providers: [VisiteService],
  templateUrl: './visite.component.html',
  styleUrls: ['./visite.component.scss']
})
export class VisiteComponent implements OnInit {
  bienId!: string;
  newVisite: Partial<Visite> = {};
  visiteurs: Visiteur[] = []; // <-- déplacer ici, pas dans calendarOptions

  calendarOptions: any = {
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    events: []
  };

  constructor(
    private route: ActivatedRoute,
    private visiteService: VisiteService
  ) {}

  ngOnInit(): void {
    this.bienId = this.route.snapshot.paramMap.get('id')!;
    this.loadVisites();
    this.loadVisiteurs();
  }

  loadVisiteurs(): void {
    this.visiteService.getAllVisiteurs().subscribe({
      next: (data: Visiteur[]) => this.visiteurs = data,
      error: (err) => console.error(err)
    });
  }

loadVisites(): void {
  this.visiteService.getVisites(this.bienId).subscribe({
    next: (visites: Visite[]) => {
      this.calendarOptions.events = visites.map(v => ({
        id: v.id,
        // Utilisation du visiteur renvoyé par l'API si présent
        title: v.visiteur ? `${v.visiteur.prenom} ${v.visiteur.nom}` : `Visite ${v.id}`,
        start: v.dateProgrammee,
        end: v.dateReelle || v.dateProgrammee,
        statut: v.statut,
        borderColor: this.getColorByStatut(v.statut)
      }));
    },
    error: (err) => console.error(err)
  });
}
addVisite(): void {
  if (!this.newVisite.dateProgrammee || !this.newVisite.visiteurId) return;

  this.visiteService.addVisite(this.bienId, this.newVisite).subscribe({
    next: (visite: Visite) => {
      // Recharge toutes les visites depuis l'API pour mettre à jour le calendrier
      this.loadVisites();

      // Réinitialise le formulaire
      this.newVisite = {};
    },
    error: (err) => console.error(err)
  });
}




  changeStatut(visiteId: number, statut: string): void {
    this.visiteService.updateVisite(this.bienId, visiteId, { statut }).subscribe({
      next: (updated: Visite) => {
        const event = this.calendarOptions.events.find((e: any) => e.id === visiteId);
        if (event) {
          event.statut = updated.statut;
          event.borderColor = this.getColorByStatut(updated.statut);
        }
      },
      error: (err) => console.error(err)
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
