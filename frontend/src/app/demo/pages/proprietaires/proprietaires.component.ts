import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { ProprietairesService } from './services/proprietaires.service';
import { Proprietaire } from './models/proprietaire.model';

@Component({
  selector: 'app-proprietaires',
  standalone: true,
  imports: [CommonModule, FormsModule, HttpClientModule],
  providers: [ProprietairesService],
  templateUrl: './proprietaires.component.html',
  styleUrls: ['./proprietaires.component.scss']
})
export class ProprietairesComponent implements OnInit {
  proprietaires: Proprietaire[] = [];
  showModal = false;
  editing = false;
  proprietaireForm: Proprietaire = {
    id: 0,
    nom: '',
    prenom: '',
    email: '',
    cin: '',
    telephone: '',
    profession: ''
  };
  showDeleteModal = false;
  proprietaireToDelete: Proprietaire | null = null;

  constructor(private proprietairesService: ProprietairesService) {}

  ngOnInit(): void {
    this.loadProprietaires();
  }

  loadProprietaires() {
    this.proprietairesService.getAll().subscribe({
      next: data => this.proprietaires = data,
      error: err => console.error('Erreur API:', err)
    });
  }

  openCreateModal() {
    this.editing = false;
    this.proprietaireForm = {
      id: 0,
      nom: '',
      prenom: '',
      email: '',
      cin: '',
      telephone: '',
      profession: ''
    };  
    this.showModal = true;
  }

  openEditModal(proprietaire: Proprietaire) {
    this.editing = true;
    this.proprietaireForm = { ...proprietaire };
    this.showModal = true;
  }

  closeModal() {
    this.showModal = false;
  }

  openDeleteModal(proprietaire: Proprietaire) {
    this.proprietaireToDelete = proprietaire;
    this.showDeleteModal = true;
  }

  closeDeleteModal() {
    this.showDeleteModal = false;
    this.proprietaireToDelete = null;
  }

  confirmDelete() {
    if (this.proprietaireToDelete) {
      this.proprietairesService.delete(this.proprietaireToDelete.id!).subscribe({
        next: () => {
          this.loadProprietaires();
          this.closeDeleteModal();
        },
        error: err => console.error('Erreur suppression:', err)
      });
    }
  }

  saveProprietaire(event?: Event) {
    if (event) event.preventDefault();

    if (this.editing) {
      this.proprietairesService.update(this.proprietaireForm.id!, this.proprietaireForm).subscribe({
        next: updated => {
          const index = this.proprietaires.findIndex(p => p.id === updated.id);
          if (index !== -1) this.proprietaires[index] = updated;
          this.closeModal();
        },
        error: err => console.error('Erreur modification:', err)
      });
    } else {
      this.proprietairesService.create(this.proprietaireForm).subscribe({
        next: newProprietaire => {
          this.proprietaires.push(newProprietaire);
          this.closeModal();
        },
        error: err => console.error('Erreur ajout:', err)
      });
    }
  }
}
