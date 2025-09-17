import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

// project import
import { AdminComponent } from './theme/layout/admin/admin.component';
import { GuestComponent } from './theme/layout/guest/guest.component';
import { VillesComponent } from './demo/pages/villes/villes.component';
import { DepartementsComponent } from './demo/pages/departements/departements.component';
import { BienImmobilierComponent } from './demo/pages/bien-immobilier/bien-immobilier.component';
import { BienVisiteComponent } from './demo/pages/bien-visite/bien-visite.component';
import { OperationsComponent } from './demo/pages/operation/operation.component';

   
import { PlanifierVisiteComponent } from './demo/pages/planifier-visite/planifier-visite.component'; 

import { CalendrierVisitesComponent } from './demo/pages/calendrier-visites/calendrier-visites.component';

import { HistoriqueVisitesComponent } from './demo/pages/historique-visites/historique-visites.component';

import { VisiteursComponent } from './demo/pages/visiteurs/visiteurs.component';
import { ClientsComponent } from './demo/pages/clients/clients.component';
import { ProprietairesComponent } from './demo/pages/proprietaires/proprietaires.component';


  PlanifierVisiteComponent 
  CalendrierVisitesComponent 
  PlanifierVisiteComponent 

    

const routes: Routes = [
  {
    path: '',
    component: AdminComponent,
    children: [
      {
        path: '',
        redirectTo: 'dashboard',
        pathMatch: 'full'
      },
      {
        path: 'dashboard',
        loadComponent: () => import('./demo/dashboard/dashboard.component').then((c) => c.DashboardComponent)
      },
      {
        path: 'basic',
        loadChildren: () => import('./demo/ui-elements/ui-basic/ui-basic.module').then((m) => m.UiBasicModule)
      },
      {
        path: 'forms',
        loadComponent: () => import('./demo/pages/form-element/form-element').then((c) => c.FormElement)
      },
      {
        path: 'tables',
        loadComponent: () => import('./demo/pages/tables/tbl-bootstrap/tbl-bootstrap.component').then((c) => c.TblBootstrapComponent)
      },
      {
        path: 'apexchart',
        loadComponent: () => import('./demo/pages/core-chart/apex-chart/apex-chart.component').then((c) => c.ApexChartComponent)
      },
      {
        path: 'sample-page',
        loadComponent: () => import('./demo/extra/sample-page/sample-page.component').then((c) => c.SamplePageComponent)
      },
      {
        path: 'villes',
        component: VillesComponent
      },
      {
        path: 'departements',   // <-- ICI : "departements" (pluriel)
        component: DepartementsComponent
      },
      
        { path: 'vente', component: OperationsComponent },
      { path: 'location', component: OperationsComponent },
      { path: 'bien-immobilier', component: BienImmobilierComponent },



      {
        path: 'visiteurs/list',
        component: VisiteursComponent
      },
      {
        path: 'clients/list',
        component: ClientsComponent
      },
      {
        path: 'proprietaires/list',
        component: ProprietairesComponent
      },
      
      { path: 'visites/planifier', component: PlanifierVisiteComponent },
      { path: 'visites/calendrier', component: CalendrierVisitesComponent },
      { path: 'visites/historique', component: HistoriqueVisitesComponent },
       { path: 'bien-visite/:id', component: BienVisiteComponent },



    ]
  },
  {
    path: '',
    component: GuestComponent,
    children: [
      {
        path: 'login',
        loadComponent: () => import('./demo/pages/authentication/auth-signin/auth-signin.component').then((c) => c.AuthSigninComponent)
      },
      {
        path: 'register',
        loadComponent: () => import('./demo/pages/authentication/auth-signup/auth-signup.component').then((c) => c.AuthSignupComponent)
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {}
