// src/app/biens/models/bien-immobilier.model.ts
export interface BienImmobilier {
  id: number;
  titre: string;
  description: string;
  surface?: number;
  nbreChambres?: number;
  adresse: string;
  statut: string;
  offreType: string;
  mantant: string;
}
