// src/app/biens/models/piece-jointe.model.ts
export interface PieceJointe {
  id: number;
  urlFichier: string;
  description?: string;
  bien?: {
    id: number;
    titre: string;
  };
}
