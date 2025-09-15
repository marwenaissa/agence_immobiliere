import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
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
  operationForm: Operation = { id: 0, type: '', bien: undefined, dateOperation: '', montant: '', statut: '' };

  showDeleteModal = false;
  operationToDelete: Operation | null = null;

  constructor(private operationsService: OperationsService, private biensService: BienImmobilierService) {}

  ngOnInit(): void {
    this.loadOperations();
    this.loadBiens();
  }

  loadOperations() {
    this.operationsService.getAll().subscribe({
      next: data => this.operations = data,
      error: err => console.error(err)
    });
  }

  loadBiens() {
    this.biensService.getAll().subscribe({
      next: data => this.biens = data,
      error: err => console.error(err)
    });
  }

  openCreateModal() {
    this.editing = false;
    this.operationForm = { id: 0, type: '', bien: undefined, dateOperation: '', montant: '', statut: '' };
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

    const payload = {
      type: this.operationForm.type,
      bien_id: this.operationForm.bien?.id,
      dateOperation: this.operationForm.dateOperation,
      montant: this.operationForm.montant,
      statut: this.operationForm.statut
    };

    if (this.editing) {
      this.operationsService.update(this.operationForm.id!, payload).subscribe({
        next: updatedOp => {
          const index = this.operations.findIndex(o => o.id === updatedOp.id);
          if (index !== -1) this.operations[index] = updatedOp;
          this.closeModal();
        },
        error: err => console.error(err)
      });
    } else {
      this.operationsService.create(payload).subscribe({
        next: newOp => {
          this.operations.push(newOp);
          this.closeModal();
        },
        error: err => console.error(err)
      });
    }
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
}
