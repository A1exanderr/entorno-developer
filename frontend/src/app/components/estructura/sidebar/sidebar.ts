import { Component, computed, effect, ElementRef, inject, OnDestroy, OnInit } from '@angular/core';
import { NavigationEnd, Router, RouterModule } from '@angular/router';
import { filter, Subject, takeUntil } from 'rxjs';
//componente
import { Menu } from '../menu/menu';
//servicio
import { EstructuraService } from '../../../servicios/estructura/estructura.service';
@Component({
  selector: 'app-sidebar',
  imports: [RouterModule, Menu],
  templateUrl: './sidebar.html',
  styleUrl: './sidebar.scss',
})
export class Sidebar implements OnInit, OnDestroy{
  estructuraService = inject(EstructuraService);

  router = inject(Router);

  el = inject(ElementRef);

  private outsideClickListener: ((event: MouseEvent) => void) | null = null;

  private destroy$ = new Subject<void>();

  constructor() {
    effect(() => {
      const state = this.estructuraService.layoutState();

      if (this.estructuraService.isDesktop()) {
        if (state.overlayMenuActive) {
          this.bindOutsideClickListener();
        } else {
          this.unbindOutsideClickListener();
        }
      } else {
        if (state.mobileMenuActive) {
          this.bindOutsideClickListener();
        } else {
          this.unbindOutsideClickListener();
        }
      }
    });
  }

  ngOnInit() {
    this.router.events
      .pipe(
        filter((event) => event instanceof NavigationEnd),
        takeUntil(this.destroy$)
      )
      .subscribe((event) => {
        const navEvent = event as NavigationEnd;
        this.onRouteChange(navEvent.urlAfterRedirects);
      });

    this.onRouteChange(this.router.url);
  }

  ngOnDestroy() {
    this.destroy$.next();
    this.destroy$.complete();
    this.unbindOutsideClickListener();
  }

  private onRouteChange(path: string) {
    this.estructuraService.layoutState.update((val) => ({
      ...val,
      activePath: path,
      overlayMenuActive: false,
      staticMenuMobileActive: false,
      mobileMenuActive: false,
      menuHoverActive: false
    }));
  }
  private unbindOutsideClickListener() {
    if (this.outsideClickListener) {
      document.removeEventListener('click', this.outsideClickListener);
      this.outsideClickListener = null;
    }
  }

  private isOutsideClicked(event: MouseEvent): boolean {
    const topbarButtonEl = document.querySelector('.topbar-start > button');
    const sidebarEl = this.el.nativeElement;
    return !(
      sidebarEl?.isSameNode(event.target as Node) ||
      sidebarEl?.contains(event.target as Node) ||
      topbarButtonEl?.isSameNode(event.target as Node) ||
      topbarButtonEl?.contains(event.target as Node)
    );
  }

  private bindOutsideClickListener() {
    if (!this.outsideClickListener) {
      this.outsideClickListener = (event: MouseEvent) => {
        if (this.isOutsideClicked(event)) {
          this.estructuraService.layoutState.update((val) => ({
            ...val,
            overlayMenuActive: false,
            staticMenuMobileActive: false,
            mobileMenuActive: false,
            menuHoverActive: false
          }));
        }
      };

      document.addEventListener('click', this.outsideClickListener);
    }
  }
}
