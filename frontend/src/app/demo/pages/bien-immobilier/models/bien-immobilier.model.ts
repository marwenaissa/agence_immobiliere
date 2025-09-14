// src/app/biens/models/bien-immobilier.model.ts
export interface BienImmobilier {
  id: number;
  titre: string;
  description: string;
  surface?: number;
  nbreChambres?: number;
  adresse: string;
  statut: string;
  offreType?: string;      // garder si nécessaire pour affichage texte
  montant: string;
  type?: { id: number; libelle: string };
  departement?: { id: number; nom: string; ville?: { id: number; nom: string } };
  proprietaire?: {
    id: number;
    nomUtilisateur?: string;
    prenomUtilisateur?: string;
    profession?: string;
    nomBanque?: string;
    adresseBanque?: string;
    rib?: string;
    iban?: string;
  };
}
