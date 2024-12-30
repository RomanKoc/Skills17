import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ExperienciaNuevaComponent } from './experiencia-nueva.component';

describe('ExperienciaNuevaComponent', () => {
  let component: ExperienciaNuevaComponent;
  let fixture: ComponentFixture<ExperienciaNuevaComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ExperienciaNuevaComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(ExperienciaNuevaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
