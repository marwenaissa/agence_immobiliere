export interface Visiteur {
  id?: number;         // identifiant unique
  nom?: string;        // nom du visiteur
  prenom?: string;     // prénom du visiteur
  email?: string;      // email du visiteur
  cin?: string;        // CIN du visiteur
  telephone?: string;  // numéro de téléphone
  profession?: string; // profession du visiteur
}

export interface ApiResponse {
  message: string;
  data: Visiteur;
}

