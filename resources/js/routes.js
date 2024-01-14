import { createRouter, createWebHistory } from 'vue-router';
import dashboard from './components/modulos/dashboard/index.vue';
import usuario from './components/modulos/usuario/index.vue';
import area from './components/modulos/area/index.vue';
import municipio from './components/modulos/municipio/index.vue';
import unidad from './components/modulos/parametros/unidad.vue';
import tipo_empleado from './components/modulos/parametros/tipo_empleado.vue';
import institucion from './components/modulos/parametros/institucion.vue';
import empleado from './components/modulos/empleado/index.vue';

import permiso from './components/modulos/permiso/index.vue';
import rol from './components/modulos/rol/index.vue';
import login from './components/modulos/authenticate/login.vue';

import usuarioPermiso from './components/modulos/usuario/permisos.vue';

import categoria from './components/modulos/categoria/index.vue';
import tipoActivo from './components/modulos/tipo_activo/index.vue';
import marca from './components/modulos/marca/index.vue';
import estadoActivo from './components/modulos/estado_activo/index.vue';
import tipoIngreso from './components/modulos/tipo_ingreso/index.vue';
import activoTecnologico from './components/modulos/activo_tenologico/index.vue';
import asignarActivo from './components/modulos/activo_tenologico/asignacionActivos.vue';
import page404 from './components/plantilla/404.vue';

function verificarAcceso(to, from, next) {
    let authUser = JSON.parse(sessionStorage.getItem('authUser'));

    if (authUser) {
        let listRolPermisosByUsuario = JSON.parse(sessionStorage.getItem('listRolPermisosByUsuario'));

        if (listRolPermisosByUsuario.includes(to.name)) {
            next();
        } else {
            let listRolPermisosByUsuarioFilter = [];

            listRolPermisosByUsuario.map(function (x) {

                if (x.includes('index')) {
                    listRolPermisosByUsuarioFilter.push(x);
                }
            })
            if (to.name == 'dashboard.index') {
                next({ name: listRolPermisosByUsuarioFilter[0] });
            } else {
                next(from.path)
            }
        }
    } else {
        next('/login')
    }
}
export default createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/login', name: 'login', component: login },
        {
            path: '/',
            name: 'dashboard.index',
            component: dashboard,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
            }
        },
        { path: '/unidad', name: 'unidad.index', component: unidad },
        { path: '/area', name: 'area.index', component: area },
        { path: '/tipo', name: 'tipo_empleado.index', component: tipo_empleado },
        { path: '/institucion', name: 'institucion.index', component: institucion },
        {
            path: '/usuario',
            name: 'usuario.index',
            component: usuario,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
            }
        },
        { path: '/municipio', name: 'municipio.index', component: municipio },
        { path: '/empleado', name: 'empleado.index', component: empleado },
        {
            path: '/permiso',
            name: 'permiso.index',
            component: permiso,
          
        },
        {
            path: '/rol',
            name: 'rol.index',
            component: rol,
           
        },
        {
            path: '/usuario/permiso/:id',
            name: 'usuario.permiso',
            component: usuarioPermiso,
            props: true
        },
        {
            path: '/categoria',
            name: 'categoria.index',
            component: categoria,
           
        },
        {
            path: '/tipoActivos',
            name: 'tipoActivos.index',
            component: tipoActivo,
            
        },
        {
            path: '/marca',
            name: 'marca.index',
            component: marca,
           
        },
        {
            path: '/estado',
            name: 'estadoActivo.index',
            component: estadoActivo,
            
        },
        {
            path: '/ingreso',
            name: 'tipoIngreso.index',
            component: tipoIngreso,
           
        },
        {
            path: '/activo_tecnologico',
            name: 'activoTecnologico.index',
            component: activoTecnologico,
            
        },
        {
            path: '/asignar_activos',
            name: 'asignarActivos.index',
            component: asignarActivo,
            
        },
        
        {
            path: '/*',
            component: page404
        }

    ],
    mode: 'history',
    linkActiveClass: 'active'
});