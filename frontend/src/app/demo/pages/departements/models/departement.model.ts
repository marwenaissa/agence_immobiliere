import { Ville } from '../../villes/models/ville.model';

export interface Departement {
  id: number;
  nom: string;
  ville?: Ville;   // objet complet
  ville_id?: number; // id seul
}