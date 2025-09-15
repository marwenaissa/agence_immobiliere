import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { Router } from '@angular/router';

import { BienImmobilierService } from './services/bien-immobilier.service';
import { BienImmobilier } from './models/bien-immobilier.model';

@Component({
  selector: 'app-bien-immobilier',
  standalone: true,
  imports: [CommonModule, FormsModule, HttpClientModule],
  templateUrl: './bien-immobilier.component.html',
  styleUrls: ['./bien-immobilier.component.scss'],
  providers: [BienImmobilierService]
})
export class BienImmobilierComponent implements OnInit {
  biens: BienImmobilier[] = [];

  constructor(
    private bienService: BienImmobilierService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.loadBiens();
  }

  loadBiens() {
    this.bienService.getAll().subscribe({
      next: data => this.biens = data,
      error: err => console.error('Erreur chargement biens:', err)
    });
  }

  // Redirection vers le calendrier de visites
  planifierVisite(bien: BienImmobilier) {
    this.router.navigate(['/visites', bien.id]);
  }
}
