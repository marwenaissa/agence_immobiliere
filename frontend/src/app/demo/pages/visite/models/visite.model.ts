export interface Visiteur {
  id: number;
  prenom: string;
  nom: string;
}

export interface Visite {
  id: number;
  visiteurId: number;
  visiteur?: Visiteur; // facultatif si l'API renvoie le visiteur complet
  dateProgrammee: string;
  dateReelle?: string | null;
  statut: string;
  commentaire?: string | null;
}
