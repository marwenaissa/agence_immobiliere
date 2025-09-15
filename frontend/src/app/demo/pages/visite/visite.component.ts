    import { Component, OnInit } from '@angular/core';
    import { ActivatedRoute } from '@angular/router';
    import { CommonModule } from '@angular/common';
    import { FullCalendarModule } from '@fullcalendar/angular';
    import dayGridPlugin from '@fullcalendar/daygrid';
    import interactionPlugin from '@fullcalendar/interaction';
    import { FormsModule } from '@angular/forms';
    import { HttpClientModule } from '@angular/common/http';
    import { VisiteService, Visite } from './services/visite.service';

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
    }

    loadVisites(): void {
        this.visiteService.getVisites(this.bienId).subscribe({
            next: (visites) => {
            this.calendarOptions.events = visites.map(v => ({
                id: v.id,
                title: `Visite ${v.id}`,
                start: v.dateProgrammee,
                end: v.dateReelle || v.dateProgrammee,
                statut: v.statut,
                color: this.getColorByStatut(v.statut) // 🎨 couleur selon statut
            }));
            },
            error: (err) => console.error(err)
        });
    }


    getColorByStatut(statut: string): string {
        switch (statut) {
            case 'programmee': return '#3498db'; // bleu
            case 'effectuee': return '#2ecc71'; // vert
            case 'annulee': return '#e74c3c';  // rouge
            default: return '#95a5a6';          // gris
        }
    }



    changeStatut(visiteId: number, statut: string) {
        this.visiteService.updateVisite(this.bienId, visiteId, { statut }).subscribe({
            next: (updated) => {
            const event = this.calendarOptions.events.find((e: any) => e.id === visiteId);
            if (event) event.statut = updated.statut;
            },
            error: (err) => console.error(err)
        });
        }

    addVisite(): void {
        if (!this.newVisite.dateProgrammee) return;

        this.visiteService.addVisite(this.bienId, this.newVisite).subscribe({
            next: (visite) => {
            // Crée un nouveau tableau pour forcer le rafraîchissement
            this.calendarOptions.events = [
                ...this.calendarOptions.events,
                {
                title: `Visite ${visite.id}`,
                start: visite.dateProgrammee,
                end: visite.dateReelle || visite.dateProgrammee
                }
            ];

            this.newVisite = {};
            },
            error: (err) => console.error(err)
        });
        }

    }
