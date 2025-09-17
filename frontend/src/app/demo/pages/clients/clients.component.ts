import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { ClientsService } from './services/clients.service';
import { Client } from './models/client.model';

@Component({
  selector: 'app-clients',
  standalone: true,
  imports: [CommonModule, FormsModule, HttpClientModule],
  providers: [ClientsService],
  templateUrl: './clients.component.html',
  styleUrls: ['./clients.component.scss']
})
export class ClientsComponent implements OnInit {
  clients: Client[] = [];
  showModal = false;
  editing = false;
 clientForm: Client = {
  id: 0,
  nom: '',
  prenom: '',
  email: '',
  cin: '',
  telephone: '',
  profession: ''
};

  showDeleteModal = false;
  clientToDelete: Client | null = null;

  constructor(private clientsService: ClientsService) {}

  ngOnInit(): void {
    this.loadClients();
  }

  loadClients() {
    this.clientsService.getAll().subscribe({
      next: data => this.clients = data,
      error: err => console.error('Erreur API:', err)
    });
  }

  openCreateModal() {
    this.editing = false;
    this.clientForm = {
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

  openEditModal(client: Client) {
    this.editing = true;
    this.clientForm = { ...client };
    this.showModal = true;
  }

  closeModal() {
    this.showModal = false;
  }

  openDeleteModal(client: Client) {
    this.clientToDelete = client;
    this.showDeleteModal = true;
  }

  closeDeleteModal() {
    this.showDeleteModal = false;
    this.clientToDelete = null;
  }

  confirmDelete() {
    if (this.clientToDelete) {
      this.clientsService.delete(this.clientToDelete.id!).subscribe({
        next: () => {
          this.loadClients();
          this.closeDeleteModal();
        },
        error: err => console.error('Erreur suppression:', err)
      });
    }
  }

  saveClient(event?: Event) {
    if (event) event.preventDefault();

    if (this.editing) {
      this.clientsService.update(this.clientForm.id!, this.clientForm).subscribe({
        next: updated => {
          const index = this.clients.findIndex(c => c.id === updated.id);
          if (index !== -1) this.clients[index] = updated;
          this.closeModal();
        },
        error: err => console.error('Erreur modification:', err)
      });
    } else {
      this.clientsService.create(this.clientForm).subscribe({
        next: newClient => {
          this.clients.push(newClient);
          this.closeModal();
        },
        error: err => console.error('Erreur ajout:', err)
      });
    }
  }
}
