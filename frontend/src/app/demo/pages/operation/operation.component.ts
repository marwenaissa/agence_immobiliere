import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router, NavigationEnd } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { filter } from 'rxjs/operators';

import { Operation } from './models/operation.model';
import { OperationsService } from './services/operation.service';
import { BienImmobilier } from '../bien-immobilier/models/bien-immobilier.model';
import { BienImmobilierService } from '../bien-immobilier/services/bien-immobilier.service';

@Component({
  selector: 'app-operations',
  standalone: true,
  imports: [CommonModule, FormsModule, HttpClientModule],
  providers: [OperationsService, BienImmobilierService],
  templateUrl: './operation.component.html',
  styleUrls: ['./operation.component.scss']
})
export class OperationsComponent implements OnInit {
  operations: Operation[] = [];
  biens: BienImmobilier[] = [];

  showModal = false;
  editing = false;
  operationForm: any = { 
    id: 0, type: '', bien: undefined, dateOperation: '', montant: '', statut: '', 
    client: null, proprietaire: null,  commentaire: '' 
  };

  showDeleteModal = false;
  operationToDelete: Operation | null = null;

  operationType: 'vente' | 'location' = 'vente'; 

  clients: any[] = []; 
  proprietaires: any[] = [];

  constructor(
    private operationsService: OperationsService,
    private biensService: BienImmobilierService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.setOperationTypeFromUrl(this.router.url);

    this.router.events
      .pipe(filter(event => event instanceof NavigationEnd))
      .subscribe((event: any) => this.setOperationTypeFromUrl(event.urlAfterRedirects));

    
  }

  private setOperationTypeFromUrl(url: string) {
    this.operationType = url.includes('/location') ? 'location' : 'vente';
    this.loadOperations();
  }

  loadOperations() {
    this.operationsService.getAll().subscribe({
      next: data => {
        console.log('Operations reçues :', data);
        this.operations = data.filter(op => op.type.toLowerCase() === this.operationType);
      },
      error: err => console.error(err)
    });
  }

  loadBiens() {
    this.biensService.getAll().subscribe({
      next: data => this.biens = data,
      error: err => console.error(err)
    });
  }

  loadClients() {
    this.operationsService.getClient().subscribe({
      next: data => this.clients = data,
      error: err => console.error(err)
    });
  }

  loadProprietaires() {
    this.operationsService.getProprietaires().subscribe({
      next: data => this.proprietaires = data,
      error: err => console.error(err)
    });
  }

  openCreateModal() {
    this.loadBiens();
    this.loadClients();
    this.loadProprietaires();
    this.editing = false;
    this.operationForm = { 
      id: 0, type: this.operationType, bien: undefined, dateOperation: '', montant: '', statut: '',
      client: null, proprietaire: null ,  commentaire: '' 
    };
    this.showModal = true;
  }

  openEditModal(op: Operation) {
    this.editing = true;
    this.operationForm = { ...op };
    this.showModal = true;
  }

  closeModal() {
    this.showModal = false;
  }

  saveOperation(event?: Event) {
    if (event) event.preventDefault();

    const payload: any = {
      type: this.operationForm.type,
      bien_id: this.operationForm.bien?.id,
      dateOperation: this.operationForm.dateOperation,
      montant: this.operationForm.montant,
      statut: this.operationForm.statut,
      commentaire: this.operationForm.commentaire
    };

    if (this.operationType === 'vente') {
      payload.client_id = this.operationForm.client?.id;
      payload.proprietaire_id = this.operationForm.proprietaire?.id;
    } else {
      payload.client_id = this.operationForm.client?.id;
      payload.proprietaire_id = this.operationForm.proprietaire?.id;
      payload.dateDebut = this.operationForm.dateDebut;
      payload.dateFin = this.operationForm.dateFin;
    }

    const obs = this.editing
      ? this.operationsService.update(this.operationForm.id, payload)
      : this.operationsService.create(payload);

    obs.subscribe({
      next: (res: any) => {
        if (this.editing) {
          const index = this.operations.findIndex(o => o.id === res.id);
          if (index !== -1) this.operations[index] = res;
        } else if (res.type.toLowerCase() === this.operationType) {
          this.operations.push(res);
        }
        this.closeModal();
      },
      error: err => console.error(err)
    });
  }

  openDeleteModal(op: Operation) {
    this.operationToDelete = op;
    this.showDeleteModal = true;
  }

  closeDeleteModal() {
    this.showDeleteModal = false;
    this.operationToDelete = null;
  }

  confirmDelete() {
    if (!this.operationToDelete) return;
    this.operationsService.delete(this.operationToDelete.id!).subscribe({
      next: () => {
        this.operations = this.operations.filter(o => o.id !== this.operationToDelete!.id);
        this.closeDeleteModal();
      },
      error: err => console.error(err)
    });
  }

    // Méthode pour afficher le nom complet du client/propriétaire
    getClientName(op: any, role: 'client' | 'proprietaire'): string {
    if (role === 'client') {
        return `${op.client?.nom || ''} ${op.client?.prenom || ''}`;
    } else {
        return `${op.proprietaire?.nom || ''} ${op.proprietaire?.prenom || ''}`;
    }
    }


}
