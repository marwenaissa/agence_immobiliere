piece-jointe.component.ts// src/app/biens/piece-jointe/piece-jointe.component.ts
import { Component, Input, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule, HttpEventType } from '@angular/common/http';
import { PieceJointeService } from './services/piece-jointe.service';
import { PieceJointe } from './models/piece-jointe.model';

@Component({
  selector: 'app-piece-jointe',
  standalone: true,
  imports: [CommonModule, FormsModule, HttpClientModule],
  templateUrl: './piece-jointe.component.html',
  styleUrls: ['./piece-jointe.component.scss'],
  providers: [PieceJointeService]
})
export class PieceJointeComponent implements OnInit {
  @Input() bienId!: number;

  pieceJointes: PieceJointe[] = [];
  selectedFiles: File[] = [];
  descriptions: string[] = [];
  uploadProgress = 0;

  constructor(private pieceService: PieceJointeService) {}

  ngOnInit(): void {
    this.loadPieces();
  }

  loadPieces() {
    if (this.bienId) {
      this.pieceService.getByBien(this.bienId).subscribe({
        next: data => this.pieceJointes = data,
        error: err => console.error('Erreur chargement pièces:', err)
      });
    }
  }

  onFilesSelected(event: any) {
    this.selectedFiles = Array.from(event.target.files);
    this.descriptions = this.selectedFiles.map(() => '');
  }

  uploadFiles() {
    if (!this.selectedFiles.length) return;

    this.pieceService.uploadFiles(this.bienId, this.selectedFiles, this.descriptions).subscribe({
      next: event => {
        if (event.type === HttpEventType.UploadProgress && event.total) {
          this.uploadProgress = Math.round(100 * event.loaded / event.total);
        } else if (event.type === HttpEventType.Response) {
          this.selectedFiles = [];
          this.descriptions = [];
          this.uploadProgress = 0;
          this.loadPieces();
        }
      },
      error: err => console.error('Erreur upload:', err)
    });
  }

  deletePiece(piece: PieceJointe) {
    if (confirm('Voulez-vous vraiment supprimer cette pièce jointe ?')) {
      this.pieceService.delete(piece.id).subscribe({
        next: () => this.loadPieces(),
        error: err => console.error('Erreur suppression:', err)
      });
    }
  }
}
