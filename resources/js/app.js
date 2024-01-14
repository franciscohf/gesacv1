/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import '../sass/app.scss'
/*import { createRouter, createWebHistory } from 'vue-router';
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
import login from './components/modulos/authenticate/login.vue';*/

import Swal from 'sweetalert2'
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';
import zhCn from 'element-plus/dist/locale/es.mjs';
import Vuesax from 'vuesax3'

import 'vuesax3/dist/vuesax.css'

window.Swal=Swal;

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */
/*const router = createRouter({
    history: createWebHistory(),
    routes: [
      { path: '/login', name:'login',component: login },
      { path: '/', name:'dashboard.index',component: dashboard },
      { path: '/unidad', name:'unidad.index',component: unidad },
      { path: '/area', name:'area.index',component: area },
      { path: '/tipo', name:'tipo_empleado.index',component: tipo_empleado },
      { path: '/institucion', name:'institucion.index',component: institucion },
      { path: '/usuario', name:'usuario.index',component: usuario },
      { path: '/municipio', name:'municipio.index',component: municipio },
      { path: '/empleado', name:'empleado.index',component: empleado },
      { path: '/permiso', name:'permiso.index',component: permiso },
      { path: '/rol', name:'rol.index',component: rol },
    
    ],
    mode:'history'
  });*/
  import routes from './routes';
const app = createApp({});

app.use(routes);
app.use(ElementPlus, {
  locale: zhCn,
});
app.use(Vuesax)
import eventBus  from './Plugins/event-bus';
//export const EventBus = new Vue();
app.use(eventBus);

import AppComponent from './components/App.vue';
import AuthComponent from './components/Auth.vue';

app.component('app-component', AppComponent);
app.component('auth-component', AuthComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */
//import router from './routes'
app.mount('#app');
