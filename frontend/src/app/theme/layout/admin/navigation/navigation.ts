export interface NavigationItem {
  id: string;
  title: string;
  type: 'item' | 'collapse' | 'group';
  translate?: string;
  icon?: string;
  hidden?: boolean;
  url?: string;
  classes?: string;
  exactMatch?: boolean;
  external?: boolean;
  target?: boolean;
  breadcrumbs?: boolean;
  children?: NavigationItem[];
}

export const NavigationItems: NavigationItem[] = [

  // ----------------- Navigation -----------------
  {
    id: 'navigation',
    title: 'Navigation',
    type: 'group',
    icon: 'icon-navigation',
    children: [
      {
        id: 'dashboard',
        title: 'Dashboard',
        type: 'item',
        url: '/dashboard',
        icon: 'feather icon-home',
        classes: 'nav-item'
      }
    ]
  },

  // ----------------- Utilisateurs -----------------
  {
    id: 'users-group',
    title: 'Utilisateurs',
    type: 'group',
    icon: 'feather icon-users',
    children: [
      {
        id: 'utilisateurs',
        title: 'Gestion utilisateurs',
        type: 'collapse', // le menu qui s'ouvre
        children: [
          { id: 'visiteurs', title: 'Visiteurs', type: 'item', url: '/utilisateurs/visiteurs/list' },
          { id: 'clients', title: 'Clients', type: 'item', url: '/utilisateurs/clients/list' },
          { id: 'proprietaires', title: 'Propriétaires', type: 'item', url: '/utilisateurs/proprietaires/list' },
          { id: 'agents', title: 'Agents', type: 'item', url: '/utilisateurs/agents/list' }
        ]
      }
    ]
  },


  // ----------------- Emplacement -----------------
  {
    id: 'geo-group',
    title: 'Emplacement',
    type: 'group',
    icon: 'feather icon-map',
    children: [
      { id: 'villes', title: 'Villes', type: 'item', url: '/villes' },
      { id: 'departements', title: 'Départements', type: 'item', url: '/departements' }
    ]
  },

  // ----------------- Pages / Biens, Visites, Vente, Location -----------------
  {
    id: 'pages',
    title: 'Pages',
    type: 'group',
    icon: 'icon-pages',
    children: [

      { id: 'bienImmobilier', title: 'Bien Immobilier', type: 'item', icon: 'feather icon-map-pin', classes: 'nav-item', url: '/bien-immobilier' },

      // Biens
      {
        id: 'biens',
        title: 'Biens',
        type: 'collapse',
        icon: 'feather icon-list',
        children: [
          { id: 'list-biens', title: 'Liste des biens', type: 'item', url: '/biens/list' },
          { id: 'add-bien', title: 'Ajouter un bien', type: 'item', url: '/biens/add' },
          { id: 'details-visites', title: 'Détails / Visites', type: 'item', url: '/biens/details' }
        ]
      },

      // Visites
      {
        id: 'visites',
        title: 'Visites',
        type: 'collapse',
        icon: 'feather icon-calendar',
        children: [
          { id: 'planifier', title: 'Planifier une visite', type: 'item', url: '/visites/planifier' },
          { id: 'calendrier', title: 'Calendrier des visites', type: 'item', url: '/visites/calendrier' },
          { id: 'historique', title: 'Historique des visites', type: 'item', url: '/visites/historique' }
        ]
      },

      // Vente
      {
        id: 'vente',
        title: 'Vente',
        type: 'item',
        icon: 'feather icon-shopping-cart',
        url: '/vente'
      },

      // Location
      {
        id: 'location',
        title: 'Location',
        type: 'item',
        icon: 'feather icon-home',
        url: '/location'
      }


    ]
  },

  // ----------------- Statistiques -----------------
  {
    id: 'stats-group',
    title: 'Statistiques',
    type: 'group',
    icon: 'icon-pages',
    children: [
      {
        id: 'statistiques',
        title: 'Statistiques',
        type: 'collapse',
        icon: 'feather icon-bar-chart-2',
        children: [
          { id: 'ventes-stat', title: 'Ventes', type: 'item', url: '/operations/ventes/statistiques' },
          { id: 'locations-stat', title: 'Locations', type: 'item', url: '/operations/locations/statistiques' }
        ]
      }
    ]
  }

];
