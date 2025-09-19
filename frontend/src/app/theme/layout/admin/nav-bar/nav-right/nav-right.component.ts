import { Component, inject, OnInit } from '@angular/core';
import { NgbDropdownConfig } from '@ng-bootstrap/ng-bootstrap';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { NotificationService } from './services/notification.service';

@Component({
  selector: 'app-nav-right',
  imports: [SharedModule],
  templateUrl: './nav-right.component.html',
  styleUrls: ['./nav-right.component.scss'],
  providers: [NgbDropdownConfig]
})
export class NavRightComponent implements OnInit {
  notifications: any[] = [];

  private notificationService = inject(NotificationService);

  constructor() {
    const config = inject(NgbDropdownConfig);
    config.placement = 'bottom-right';
  }

  ngOnInit(): void {
    this.notificationService.getVisiteNotifications().subscribe({
      next: (notif) => {
        this.notifications.unshift(notif);
      },
      error: (err) => console.error('Erreur notifications:', err)
    });
  }
}
