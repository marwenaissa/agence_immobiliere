import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule, HttpClient } from '@angular/common/http';
import { Ville } from './models/ville.model';
import { VillesService } from './services/villes.service';

@Component({
  selector: 'app-villes',
  standalone: true,
  imports: [CommonModule, FormsModule, HttpClientModule],
  providers: [VillesService], // <-- ajoute ici pour résoudre le NullInjectorError
  templateUrl: './villes.component.html',
  styleUrls: ['./villes.component.scss']
})
export class VillesComponent implements OnInit {
  villes: Ville[] = [];
  showModal = false;
  editing = false;
  villeForm: Ville = { id: 0, nom: '' };

  constructor(private villesService: VillesService) {}

  ngOnInit(): void {
    this.loadVilles();
  }

  loadVilles() {
    this.villesService.getAll().subscribe({
      next: data => this.villes = data,
      error: err => console.error('Erreur API:', err)
    });
  }

  deleteVille(id: number) {
    if (confirm('Voulez-vous vraiment supprimer cette ville ?')) {
      this.villesService.delete(id).subscribe({
        next: () => this.loadVilles(),
        error: err => console.error('Erreur suppression:', err)
      });
    }
  }

  openCreateModal() {
    this.editing = false;
    this.villeForm = { id: 0, nom: '' };
    this.showModal = true;
  }

  openEditModal(ville: Ville) {
    this.editing = true;
    this.villeForm = { ...ville };
    this.showModal = true;
  }

  closeModal() {
    this.showModal = false;
  }

  saveVille(event?: Event) {
    if (event) event.preventDefault();

    if (this.editing) {
      this.villesService.update(this.villeForm.id, this.villeForm).subscribe({
        next: updatedVille => {
          const index = this.villes.findIndex(v => v.id === updatedVille.id);
          if (index !== -1) this.villes[index] = updatedVille;
          this.closeModal();
        },
        error: err => console.error('Erreur modification:', err)
      });
    } else {
      this.villesService.create(this.villeForm).subscribe({
        next: newVille => {
          this.villes.push(newVille);
          this.closeModal();
        },
        error: err => console.error('Erreur ajout:', err)
      });
    }
  }
}
