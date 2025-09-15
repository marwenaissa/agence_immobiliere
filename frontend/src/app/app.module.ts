import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { AppComponent } from './app.component';
import { VisiteComponent } from './demo/pages/visite/visite.component';
import { HttpClientModule } from '@angular/common/http';
import { FullCalendarModule } from '@fullcalendar/angular';
import { AppRoutingModule } from './app-routing.module';

@NgModule({
  declarations: [AppComponent, VisiteComponent],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FullCalendarModule
  ],
  bootstrap: [AppComponent]
})
export class AppModule {}
