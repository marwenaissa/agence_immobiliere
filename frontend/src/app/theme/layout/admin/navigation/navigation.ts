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

  {
    id: 'utilisateurs',
    title: 'Utilisateurs',
    type: 'collapse',
    icon: 'feather icon-users',
    children: [
      {
        id: 'visiteurs',
        title: 'Visiteurs',
        type: 'collapse',
        children: [
          { id: 'list-visiteurs', title: 'Liste des visiteurs', type: 'item', url: '/utilisateurs/visiteurs/list' },
          { id: 'add-visiteur', title: 'Ajouter un visiteur', type: 'item', url: '/utilisateurs/visiteurs/add' }
        ]
      },
      {
        id: 'clients',
        title: 'Clients',
        type: 'collapse',
        children: [
          { id: 'list-clients', title: 'Liste des clients', type: 'item', url: '/utilisateurs/clients/list' },
          { id: 'add-client', title: 'Ajouter un client', type: 'item', url: '/utilisateurs/clients/add' }
        ]
      },
      {
        id: 'proprietaires',
        title: 'Propriétaires',
        type: 'collapse',
        children: [
          { id: 'list-proprietaires', title: 'Liste des propriétaires', type: 'item', url: '/utilisateurs/proprietaires/list' },
          { id: 'add-proprietaire', title: 'Ajouter un propriétaire', type: 'item', url: '/utilisateurs/proprietaires/add' }
        ]
      },
      {
        id: 'agents',
        title: 'Agents',
        type: 'collapse',
        children: [
          { id: 'list-agents', title: 'Liste des agents', type: 'item', url: '/utilisateurs/agents/list' },
          { id: 'add-agent', title: 'Ajouter un agent', type: 'item', url: '/utilisateurs/agents/add' }
        ]
      }
    ]
  },



  {
    id: 'geographie',
    title: 'Géographie',
    type: 'collapse',
    icon: 'feather icon-map',
    children: [
      {
        id: 'villes',
        title: 'Villes',
        type: 'item',
        url: '/villes'
      },
      {
        id: 'departements',
        title: 'Départements',
        type: 'item',
        url: '/departements'
      }
    ]
  },


  /* {
    id: 'ui-element',
    title: 'UI ELEMENT',
    type: 'group',
    icon: 'icon-ui',
    children: [
      {
        id: 'basic',
        title: 'Component',
        type: 'collapse',
        icon: 'feather icon-box',
        children: [
          {
            id: 'button',
            title: 'Button',
            type: 'item',
            url: '/basic/button'
          },
          {
            id: 'badges',
            title: 'Badges',
            type: 'item',
            url: '/basic/badges'
          },
          {
            id: 'breadcrumb-pagination',
            title: 'Breadcrumb & Pagination',
            type: 'item',
            url: '/basic/breadcrumb-paging'
          },
          {
            id: 'collapse',
            title: 'Collapse',
            type: 'item',
            url: '/basic/collapse'
          },
          {
            id: 'tabs-pills',
            title: 'Tabs & Pills',
            type: 'item',
            url: '/basic/tabs-pills'
          },
          {
            id: 'typography',
            title: 'Typography',
            type: 'item',
            url: '/basic/typography'
          }
        ]
      }
    ]
  }, */
 
 
  {
    id: 'pages',
    title: 'Pages',
    type: 'group',
    icon: 'icon-pages',
    children: [
      
   
     
      
      
      { 
        id: 'bienImmobilier',
        title: 'Bien Immobilier',
        type: 'item',
        icon: 'feather icon-map-pin',
        classes: 'nav-item',
        url: '/bien-immobilier' 
      },

            
      { 
        id: 'operation',
        title: 'operation',
        type: 'item',
        icon: 'feather icon-map-pin',
        classes: 'nav-item',
        url: '/operation' 
      },
      



      

      
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
      {
        id: 'vente',
        title: 'Vente',
        type: 'collapse',
        icon: 'feather icon-shopping-cart',
        children: [
          {
            id: 'liste-ventes',
            title: 'Liste des ventes',
            type: 'item',
            url: '/vente/liste',
            breadcrumbs: true
          },
          {
            id: 'ajouter-vente',
            title: 'Ajouter une vente',
            type: 'item',
            url: '/vente/ajouter',
            breadcrumbs: true
          }
        ]
      },
      {
        id: 'location',
        title: 'Location',
        type: 'collapse',
        icon: 'feather icon-home',
        children: [
          {
            id: 'liste-locations',
            title: 'Liste des locations',
            type: 'item',
            url: '/location/liste',
            breadcrumbs: true
          },
          {
            id: 'ajouter-location',
            title: 'Ajouter une location',
            type: 'item',
            url: '/location/ajouter',
            breadcrumbs: true
          }
        ]
      },

   



      /* {
        id: 'operations',
        title: 'Opérations',
        type: 'collapse',
        icon: 'feather icon-dollar-sign',
        children: [
          {
            id: 'vente',
            title: 'Achat / Vente',
            type: 'collapse',
            children: [
              { id: 'list-ventes', title: 'Liste des ventes', type: 'item', url: '/operations/ventes/list' },
              { id: 'add-vente', title: 'Ajouter une vente', type: 'item', url: '/operations/ventes/add' },
              { id: 'stats-ventes', title: 'Statistiques ventes', type: 'item', url: '/operations/ventes/stats' }
            ]
          },
          {
            id: 'location',
            title: 'Location',
            type: 'collapse',
            children: [
              { id: 'list-locations', title: 'Liste des locations', type: 'item', url: '/operations/locations/list' },
              { id: 'add-location', title: 'Ajouter une location', type: 'item', url: '/operations/locations/add' },
              { id: 'stats-locations', title: 'Statistiques locations', type: 'item', url: '/operations/locations/stats' }
            ]
          }
        ]
      }, */
    
      

      

    ]
  },

  {
  id: 'pages',
  title: 'Pages',
  type: 'group',
  icon: 'icon-pages',
  children: [
    {
      id: 'statistiques',
      title: 'Statistiques',
      type: 'collapse',
      icon: 'feather icon-bar-chart-2',
      children: [
        {
          id: 'ventes-stat',
          title: 'Ventes',
          type: 'item', // lien direct vers le composant VentesStatComponent
          url: '/operations/ventes/statistiques'
        },
        {
          id: 'locations-stat',
          title: 'Locations',
          type: 'item', // lien direct vers le composant LocationsStatComponent
          url: '/operations/locations/statistiques'
        }
      ]
    }
  ]
}

  


  
];
