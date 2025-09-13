import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { Departement } from './models/departement.model';
import { DepartementsService } from './services/departements.service';
import { Ville } from '../villes/models/ville.model';
import { VillesService } from '../villes/services/villes.service';

@Component({
  selector: 'app-departements',
  standalone: true,
  imports: [CommonModule, FormsModule, HttpClientModule],
  providers: [DepartementsService, VillesService],
  templateUrl: './departements.component.html',
  styleUrls: ['./departements.component.scss']
})
export class DepartementsComponent implements OnInit {
  departements: Departement[] = [];
  villes: Ville[] = [];
  showModal = false;
  editing = false;
  departementForm: Departement = { id: 0, nom: '', ville: undefined };

  showDeleteModal = false;
  departementToDelete: Departement | null = null;

  constructor(
    private departementsService: DepartementsService,
    private villesService: VillesService
  ) {}

  ngOnInit(): void {
    this.loadDepartements();
    this.loadVilles();
  }

  loadDepartements() {
    this.departementsService.getAll().subscribe({
      next: data => this.departements = data,
      error: err => console.error('Erreur API:', err)
    });
  }

  loadVilles() {
    this.villesService.getAll().subscribe({
      next: data => this.villes = data,
      error: err => console.error('Erreur chargement villes:', err)
    });
  }

  openCreateModal() {
    this.editing = false;
    this.departementForm = { id: 0, nom: '', ville: undefined };
    this.showModal = true;
  }

  openEditModal(departement: Departement) {
    this.editing = true;
    this.departementForm = { ...departement };
    this.showModal = true;
  }

  closeModal() {
    this.showModal = false;
  }

  openDeleteModal(departement: Departement) {
    this.departementToDelete = departement;
    this.showDeleteModal = true;
  }

  closeDeleteModal() {
    this.showDeleteModal = false;
    this.departementToDelete = null;
  }

  confirmDelete() {
    if (this.departementToDelete) {
      this.departementsService.delete(this.departementToDelete.id).subscribe({
        next: () => {
          this.loadDepartements();
          this.closeDeleteModal();
        },
        error: err => console.error('Erreur suppression:', err)
      });
    }
  }

  saveDepartement(event?: Event) {
    if (event) event.preventDefault();

    const payload = {
      nom: this.departementForm.nom,
      ville_id: this.departementForm.ville?.id
    };

    if (this.editing) {
      this.departementsService.update(this.departementForm.id, payload).subscribe({
        next: updated => {
          const index = this.departements.findIndex(d => d.id === updated.id);
          if (index !== -1) this.departements[index] = updated;
          this.closeModal();
        },
        error: err => console.error('Erreur modification:', err)
      });
    } else {
      this.departementsService.create(payload).subscribe({
        next: newDep => {
          this.departements.push(newDep);
          this.closeModal();
        },
        error: err => console.error('Erreur ajout:', err)
      });
    }
  }



}
