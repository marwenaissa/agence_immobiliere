import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { AppComponent } from './app.component';
import { BienVisiteComponent } from './demo/pages/bien-visite/bien-visite.component';
import { HttpClientModule } from '@angular/common/http';
import { FullCalendarModule } from '@fullcalendar/angular';
import { AppRoutingModule } from './app-routing.module';

@NgModule({
  declarations: [AppComponent, BienVisiteComponent],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FullCalendarModule
  ],
  bootstrap: [AppComponent]
})
export class AppModule {}
