import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { VisiteursService } from './services/visiteurs.service';
import { Visiteur } from './models/visiteur.model';

@Component({
  selector: 'app-visiteurs',
  standalone: true,
  imports: [CommonModule, FormsModule, HttpClientModule],
  providers: [VisiteursService],
  templateUrl: './visiteurs.component.html',
  styleUrls: ['./visiteurs.component.scss']
})
export class VisiteursComponent implements OnInit {
  visiteurs: Visiteur[] = [];
  showModal = false;
  editing = false;
  visiteurForm: Visiteur = {
    id: 0,
    nom: '',
    prenom: '',
    email: '',
    cin: '',
    telephone: '',
    profession: ''
  };
  showDeleteModal = false;
  visiteurToDelete: Visiteur | null = null;

  constructor(private visiteursService: VisiteursService) {}

  ngOnInit(): void {
    this.loadVisiteurs();
  }

  loadVisiteurs() {
    this.visiteursService.getAll().subscribe({
      next: data => this.visiteurs = data,
      error: err => console.error('Erreur API:', err)
    });
  }

  openCreateModal() {
    this.editing = false;
    this.visiteurForm = {
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

  openEditModal(visiteur: Visiteur) {
    this.editing = true;
    this.visiteurForm = { ...visiteur };
    this.showModal = true;
  }

  closeModal() {
    this.showModal = false;
  }

  openDeleteModal(visiteur: Visiteur) {
    this.visiteurToDelete = visiteur;
    this.showDeleteModal = true;
  }

  closeDeleteModal() {
    this.showDeleteModal = false;
    this.visiteurToDelete = null;
  }

  confirmDelete() {
    if (this.visiteurToDelete) {
      this.visiteursService.delete(this.visiteurToDelete.id!).subscribe({
        next: () => {
          this.loadVisiteurs();
          this.closeDeleteModal();
        },
        error: err => console.error('Erreur suppression:', err)
      });
    }
  }

  saveVisiteur(event?: Event) {
    if (event) event.preventDefault();

    if (this.editing) {
      this.visiteursService.update(this.visiteurForm.id!, this.visiteurForm).subscribe({
        next: updated => {
          const index = this.visiteurs.findIndex(v => v.id === updated.id);
          if (index !== -1) this.visiteurs[index] = updated;
          this.closeModal();
        },
        error: err => console.error('Erreur modification:', err)
      });
    } else {
      this.visiteursService.create(this.visiteurForm).subscribe({
        next: newVisiteur => {
          this.visiteurs.push(newVisiteur);
          this.closeModal();
        },
        error: err => console.error('Erreur ajout:', err)
      });
    }
  }
}
