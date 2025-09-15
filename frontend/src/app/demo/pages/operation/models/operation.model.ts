import { BienImmobilier } from '../../bien-immobilier/models/bien-immobilier.model';
import { Client } from '../../clients/models/client.model';
import { Proprietaire } from '../../proprietaires/models/proprietaire.model';

export interface Operation {
  id: number;
  type: string;
  bien?: BienImmobilier;
  bien_id?: number;
  acheteur?: Client;
  acheteur_id?: number;
  vendeur?: Proprietaire;
  vendeur_id?: number;
  locataire?: Client;
  locataire_id?: number;
  bailleur?: Proprietaire;
  bailleur_id?: number;
  dateOperation: string;
  montant: string;
  statut: string;
  caution?: string;
  commentaire?: string;
}
