import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { VisitePlanifier, Visiteur, Bien, Visite } from './services/visite-planifier.service';

@Component({
  selector: 'app-planifier-visite',
  standalone: true,
  imports: [CommonModule, FormsModule, HttpClientModule],
  templateUrl: './planifier-visite.component.html',
  styleUrls: ['./planifier-visite.component.scss'],
  providers: [VisitePlanifier]
  
})
export class PlanifierVisiteComponent implements OnInit {
  biens: Bien[] = [];
  visiteurs: Visiteur[] = [];
  newVisite: Partial<Visite> = {};

  constructor(private visiteService: VisitePlanifier) {}

  ngOnInit(): void {
    this.loadBiens();
    this.loadVisiteurs();
  }

  loadBiens(): void {
    this.visiteService.getAllBiens().subscribe({
      next: (b) => (this.biens = b),
      error: (err) => console.error('Erreur lors du chargement des biens :', err)
    });
  }

  loadVisiteurs(): void {
    this.visiteService.getAllVisiteurs().subscribe({
      next: (v) => (this.visiteurs = v),
      error: (err) => console.error('Erreur lors du chargement des visiteurs :', err)
    });
  }

  addVisite(): void {
    if (!this.newVisite.bienId || !this.newVisite.visiteurId || !this.newVisite.dateProgrammee) {
      alert('Veuillez remplir tous les champs requis !');
      return;
    }

    this.visiteService.addVisiteBien(this.newVisite).subscribe({
      next: (v) => {
        alert('Visite ajoutée avec succès !');
        this.newVisite = {}; // Reset du formulaire
      },
      error: (err) => console.error('Erreur lors de l\'ajout de la visite :', err)
    });
  }
}
