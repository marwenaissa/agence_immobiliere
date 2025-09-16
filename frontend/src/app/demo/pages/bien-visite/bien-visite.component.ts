    import { Component, OnInit } from '@angular/core';
    import { ActivatedRoute } from '@angular/router';
    import { VisiteService } from './services/visite.service';
    import { Visite, Visiteur } from './models/visite.model';
    import { CommonModule } from '@angular/common';
    import { FormsModule } from '@angular/forms';
    import { FullCalendarModule } from '@fullcalendar/angular';
    import dayGridPlugin from '@fullcalendar/daygrid';
    import interactionPlugin from '@fullcalendar/interaction';
    import { HttpClientModule } from '@angular/common/http';

    @Component({
    selector: 'app-bien-visite',
    templateUrl: './bien-visite.component.html',
    styleUrls: ['./bien-visite.component.scss'],
    standalone: true,
    imports: [
        CommonModule,
        FormsModule,
        FullCalendarModule,
        HttpClientModule
    ],
    providers: [VisiteService]
    })
    export class BienVisiteComponent implements OnInit {
    bienId!: string;
    newVisite: Partial<Visite> = {};
    visiteurs: Visiteur[] = [];
    calendarOptions: any = {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        events: []
    };
    error: string | null = null;

    constructor(private route: ActivatedRoute, private visiteService: VisiteService) {}

    ngOnInit(): void {
        this.bienId = this.route.snapshot.paramMap.get('id')!;
        this.loadVisiteurs();
        this.loadVisites();
    }

    loadVisiteurs(): void {
        this.visiteService.getAllVisiteurs().subscribe({
        next: (data) => (this.visiteurs = data),
        error: (err) => console.error(err)
        });
    }

    loadVisites(): void {
    this.visiteService.getVisitesByBien(this.bienId).subscribe({
        next: (visites: Visite[]) => {
        this.calendarOptions.events = visites.map(v => ({
            id: v.id,
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
        if (!this.newVisite.visiteurId || !this.newVisite.dateProgrammee) return;

        // Méthode alignée avec le service
        this.visiteService.addVisiteBien( this.newVisite).subscribe({
        next: () => {
            this.loadVisites();
            this.newVisite = {};
        },
        error: (err) => console.error(err)
        });
    }

    changeStatut(visiteId: number, statut: string): void {
        this.visiteService.updateVisiteBien(visiteId, statut).subscribe({
        next: () => {
            const event = this.calendarOptions.events.find((e: any) => e.id === visiteId);
            if (event) {
            event.statut = statut;
            event.borderColor = this.getColorByStatut(statut);
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
