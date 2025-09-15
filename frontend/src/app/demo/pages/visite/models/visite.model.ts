export interface Visite {
  id: number;
  visiteurId: number | null;
  dateProgrammee: string;
  dateReelle: string | null;
  statut: string;
  commentaire: string | null;
}
