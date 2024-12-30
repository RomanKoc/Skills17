import { Routes } from '@angular/router';
import { BienvenidaComponent } from './bienvenida/bienvenida.component';
import { ExperienciasComponent } from './experiencias/experiencias.component';
import { LoginComponent } from './login/login.component';
import { RegistroComponent } from './registro/registro.component';
import { InfoUsuarioComponent } from './info-usuario/info-usuario.component';
import { ExperienciaIndividualComponent } from './experiencia-individual/experiencia-individual.component';
import { ExperienciaNuevaComponent } from './experiencia-nueva/experiencia-nueva.component';

export const routes: Routes = [
    {
        path: '',
        component: BienvenidaComponent
    },
    {
        path: 'bienvenida',
        component: BienvenidaComponent
    },
    {
        path: 'login',
        component: LoginComponent
    },
    {
        path: 'registro',
        component: RegistroComponent
    },
    {
        path: 'info-usuario',
        component: InfoUsuarioComponent
    },
    {
        path: 'experiencias',
        component: ExperienciasComponent,

    },
    {
        path: 'experiencia-individual/:id',
        component: ExperienciaIndividualComponent
    },
    {
        path: 'experiencia-nueva', /* Creo que no hay que meter id, revisar */
        component: ExperienciaNuevaComponent
    },
    {
        path: '**',
        redirectTo: 'bienvenida'
    }
];
