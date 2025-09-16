import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { VisiteService, Visiteur, Bien, Visite } from '../services/visite.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-planifier-visite',
  standalone: true,
  imports: [FormsModule, HttpClientModule, CommonModule],
  templateUrl: './planifier-visite.component.html'
})
export class PlanifierVisiteComponent implements OnInit {
  biens: Bien[] = [];
  visiteurs: Visiteur[] = [];
  newVisite: Partial<Visite> = {};

  constructor(private visiteService: VisiteService) {}

  ngOnInit(): void {
    this.loadBiens();
    this.loadVisiteurs();
  }

  loadBiens() {
    this.visiteService.getAllBiens().subscribe({
      next: b => this.biens = b,
      error: err => console.error(err)
    });
  }

  loadVisiteurs() {
    this.visiteService.getAllVisiteurs().subscribe({
      next: v => this.visiteurs = v,
      error: err => console.error(err)
    });
  }

  addVisite() {
    if (!this.newVisite.bienId || !this.newVisite.visiteurId || !this.newVisite.dateProgrammee) {
      alert('Veuillez remplir tous les champs requis !');
      return;
    }

    this.visiteService.addVisiteBien(this.newVisite).subscribe({
      next: v => {
        alert('Visite ajoutée avec succès !');
        this.newVisite = {}; // Reset le formulaire
      },
      error: err => console.error(err)
    });
  }
}
