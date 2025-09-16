import { Component, OnInit } from '@angular/core';
import { VisiteService, Visite } from '../services/visite.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-historique-visites',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './historique-visites.component.html'
})
export class HistoriqueVisitesComponent implements OnInit {
  visites: Visite[] = [];
  page = 1;
  pageSize = 10;

  constructor(private visiteService: VisiteService) {}

  ngOnInit(): void {
    this.loadVisites();
  }

  loadVisites() {
    this.visiteService.getAllVisites().subscribe(v => this.visites = v);
  }

  get paginatedVisites() {
    const start = (this.page - 1) * this.pageSize;
    return this.visites.slice(start, start + this.pageSize);
  }

  nextPage() { if ((this.page) * this.pageSize < this.visites.length) this.page++; }
  prevPage() { if (this.page > 1) this.page--; }
}
