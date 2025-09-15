export interface Visiteur {
  prenom: string;
  nom: string;
}

export interface Visite {
  id: number;
  visiteurId: number | null;
  visiteur?: Visiteur | null;   // peut être null si l'utilisateur n'existe pas
  dateProgrammee: string;
  dateReelle: string | null;
  statut: string;
  commentaire: string | null;
}
