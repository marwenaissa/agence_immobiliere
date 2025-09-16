import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { VisiteService, Visiteur, Bien, Visite } from './services/visite.service';

@Component({
  selector: 'app-planifier-visite',
  standalone: true,
  imports: [FormsModule, HttpClientModule],
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
    this.visiteService.getAllBiens().subscribe(b => this.biens = b);
  }

  loadVisiteurs() {
    this.visiteService.getAllVisiteurs().subscribe(v => this.visiteurs = v);
  }

  addVisite() {
    if (!this.newVisite.bienId || !this.newVisite.visiteurId || !this.newVisite.dateProgrammee) return;

    this.visiteService.addVisiteBien(this.newVisite).subscribe({
      next: v => {
        alert('Visite ajoutée !');
        this.newVisite = {};
      },
      error: err => console.error(err)
    });
  }
}
