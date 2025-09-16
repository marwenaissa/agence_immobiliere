// src/app/demo/pages/historique-visites/historique-visites.component.ts
import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { HistoriqueVisitesService, Visite } from './services/historique-visites.service';

@Component({
  selector: 'app-historique-visites',
  standalone: true,
  imports: [
    CommonModule,
    HttpClientModule // <-- obligatoire pour que HttpClient soit disponible
  ],
  templateUrl: './historique-visites.component.html',
  styleUrls: ['./historique-visites.component.scss'],
  providers: [HistoriqueVisitesService] // <-- s'assure que le service est injecté
})
export class HistoriqueVisitesComponent implements OnInit {
  visites: Visite[] = [];
  page = 1;
  pageSize = 10;

  constructor(private historiqueService: HistoriqueVisitesService) {}

  ngOnInit(): void {
    this.loadVisites();
  }

  loadVisites() {
    this.historiqueService.getAllVisites().subscribe(v => {
      console.log('visites reçues:', v);
      this.visites = v;
    });
  }

  get paginatedVisites() {
    const start = (this.page - 1) * this.pageSize;
    return this.visites.slice(start, start + this.pageSize);
  }

  nextPage() { if ((this.page) * this.pageSize < this.visites.length) this.page++; }
  prevPage() { if (this.page > 1) this.page--; }
}
