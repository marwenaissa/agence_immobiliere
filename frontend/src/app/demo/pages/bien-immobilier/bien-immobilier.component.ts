// src/app/biens/bien-immobilier/bien-immobilier.component.ts
import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { BienImmobilierService } from './services/bien-immobilier.service';
import { BienImmobilier } from './models/bien-immobilier.model';
import { PieceJointeComponent } from '../piece-jointe/piece-jointe.component';
import { Router } from '@angular/router';

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
    statut: '', offreType: '', montant: ''
  };
  selectedBienIdForPieces?: number;

  proprietaires: any[] = [];
  types: any[] = [];
  departements: any[] = [];

  // ✅ Toujours tableau de fichiers
  selectedFiles: File[] = [];

  constructor(private bienService: BienImmobilierService, private router: Router) {}


  ngOnInit(): void {
    this.loadBiens();
  }

  // Sélection de fichiers
  onFilesSelected(event: Event) {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files.length > 0) {
      this.selectedFiles = Array.from(input.files); // ✅ convertit FileList en File[]
    }
  }

  loadBiens() {
    this.bienService.getAll().subscribe({
      next: data => this.biens = data,
      error: err => console.error('Erreur chargement biens:', err)
    });
  }

  openCreateModal() {
    this.editing = false;
    this.bienForm = { id: 0, titre: '', description: '', adresse: '', statut: '', offreType: '', montant: '' };

    // Charger les listes
    this.loadProprietaires();
    this.loadTypes();
    this.loadDepartements();

    this.selectedFiles = []; // ✅ reset fichiers
    this.showModal = true;
  }

  openEditModal(bien: BienImmobilier) {
    this.editing = true;
    this.bienForm = { ...bien };

    // Mapper les objets pour le select
    this.bienForm.proprietaire = this.proprietaires.find(p => p.id === bien.proprietaire?.id);
    this.bienForm.type = this.types.find(t => t.id === bien.type?.id);
    this.bienForm.departement = this.departements.find(d => d.id === bien.departement?.id);

    // Charger les listes si vides
    if (this.proprietaires.length === 0) this.loadProprietaires();
    if (this.types.length === 0) this.loadTypes();
    if (this.departements.length === 0) this.loadDepartements();

    this.selectedFiles = []; // ✅ reset fichiers
    this.showModal = true;
  }

  closeModal() {
    this.showModal = false;
  }

  loadProprietaires() {
    this.bienService.getProprietaires().subscribe(data => this.proprietaires = data);
  }

  loadTypes() {
    this.bienService.getTypes().subscribe(data => this.types = data);
  }

  loadDepartements() {
    this.bienService.getDepartements().subscribe(data => this.departements = data);
  }

  saveBien(event?: Event) {
    if (event) event.preventDefault();

    const formData = new FormData();
    formData.append('titre', this.bienForm.titre);
    formData.append('description', this.bienForm.description);
    formData.append('adresse', this.bienForm.adresse);
    formData.append('statut', this.bienForm.statut);
    formData.append('montant', this.bienForm.montant);
    formData.append('offreType', this.bienForm.offreType);
    formData.append('type_id', this.bienForm.type.id.toString());
    formData.append('proprietaire_id', this.bienForm.proprietaire.id.toString());
    formData.append('departement_id', this.bienForm.departement.id.toString());

    // ✅ Ajouter plusieurs fichiers
    this.selectedFiles.forEach(file => {
      formData.append('files[]', file);
    });

    if (this.editing) {
      this.bienService.updateWithFile(this.bienForm.id, formData).subscribe({
        next: updated => {
          const index = this.biens.findIndex(b => b.id === updated.id);
          if (index !== -1) this.biens[index] = updated;
          this.selectedFiles = [];
          this.closeModal();
        },
        error: err => console.error('Erreur modification:', err)
      });
    } else {
      this.bienService.createWithFile(formData).subscribe({
        next: () => {
            this.loadBiens();   // 🔹 recharge toute la liste depuis l’API
            this.selectedFiles = [];
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



   planifierVisite(bien: any) {
    this.router.navigate(['/bien-visite', bien.id]);
  }


}
