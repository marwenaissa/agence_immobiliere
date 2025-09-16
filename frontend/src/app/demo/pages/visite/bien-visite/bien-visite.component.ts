import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { VisiteService } from '../services/visite.service';

@Component({
  selector: 'app-bien-visite',
  templateUrl: './bien-visite.component.html',
  styleUrls: ['./bien-visite.component.scss']
})
export class BienVisiteComponent implements OnInit {
  bienId!: number;
  visites: any[] = [];
  loading = true;
  error: string | null = null;

  constructor(
    private route: ActivatedRoute,
    private visiteService: VisiteService
  ) {}

  ngOnInit(): void {
    // Récupérer l'id du bien depuis l'URL
    this.bienId = +this.route.snapshot.paramMap.get('bienId')!;

    // Charger les visites pour ce bien
    this.visiteService.getVisitesByBien(this.bienId).subscribe({
      next: (data) => {
        this.visites = data;
        this.loading = false;
      },
      error: () => {
        this.error = "Impossible de charger les visites pour ce bien.";
        this.loading = false;
      }
    });
  }

  validerVisite(visiteId: number) {
    this.visiteService.validerVisite(visiteId).subscribe({
      next: () => {
        // Mettre à jour l'état local après validation
        this.visites = this.visites.map(v =>
          v.id === visiteId ? { ...v, statut: 'Validée' } : v
        );
      },
      error: () => {
        this.error = "Échec de la validation.";
      }
    });
  }
}
