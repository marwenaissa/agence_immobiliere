// src/app/biens/bien-immobilier/bien-immobilier.component.ts
import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { BienImmobilierService } from './services/bien-immobilier.service';
import { BienImmobilier } from './models/bien-immobilier.model';
import { PieceJointeComponent } from '../piece-jointe/piece-jointe.component';

@Component({
  selector: 'app-bien-immobilier',
  standalone: true,
  imports: [CommonModule, FormsModule, HttpClientModule, PieceJointeComponent],
  templateUrl: './bien-immobilier.component.html',
  styleUrls: ['./bien-immobilier.component.scss'],
  providers: [BienImmobilierService]
})
export class BienImmobilierComponent implements OnInit {
  biens: BienImmobilier[] = [];
  showModal = false;
  editing = false;
  bienForm: BienImmobilier = {
    id: 0, titre: '', description: '', adresse: '',
    statut: '', offreType: '', mantant: ''
  };
  selectedBienIdForPieces?: number;

  constructor(private bienService: BienImmobilierService) {}

  ngOnInit(): void {
    this.loadBiens();
  }

  loadBiens() {
    this.bienService.getAll().subscribe({
      next: data => this.biens = data,
      error: err => console.error('Erreur chargement biens:', err)
    });
  }

  openCreateModal() {
    this.editing = false;
    this.bienForm = { id: 0, titre: '', description: '', adresse: '', statut: '', offreType: '', mantant: '' };
    this.showModal = true;
  }

  openEditModal(bien: BienImmobilier) {
    this.editing = true;
    this.bienForm = { ...bien };
    this.showModal = true;
  }

  closeModal() {
    this.showModal = false;
  }

  saveBien(event?: Event) {
    if (event) event.preventDefault();

    if (this.editing) {
      this.bienService.update(this.bienForm.id, this.bienForm).subscribe({
        next: updated => {
          const index = this.biens.findIndex(b => b.id === updated.id);
          if (index !== -1) this.biens[index] = updated;
          this.closeModal();
        },
        error: err => console.error('Erreur modification:', err)
      });
    } else {
      this.bienService.create(this.bienForm).subscribe({
        next: newBien => {
          this.biens.push(newBien);
          this.closeModal();
        },
        error: err => console.error('Erreur ajout:', err)
      });
    }
  }

  deleteBien(id: number) {
    if (confirm('Voulez-vous vraiment supprimer ce bien ?')) {
      this.bienService.delete(id).subscribe({
        next: () => this.loadBiens(),
        error: err => console.error('Erreur suppression:', err)
      });
    }
  }

  managePieces(bienId: number) {
    this.selectedBienIdForPieces = bienId;
  }
}
