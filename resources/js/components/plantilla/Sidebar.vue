<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="#" class="brand-link">
            <img src="#" alt="" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">SISTEMA GESAC</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <template v-if="!usuario.file_id">
                        <img :src="ruta + '/img/imagen_default.png'" class="img-circle elevation-2" :alt="usuario.username">
                    </template>
                    <template v-else>
                        <img :src="usuario.file.path" class="img-circle elevation-2" :alt="usuario.username"
                            style="height:34px !important;">
                    </template>
                </div>
                <div class="info" >
                    <a href="#" class="d-block">  {{ usuario.username }}
</a>
                  
                </div>
            </div>

            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <a href="#" class="d-block" @click.prevent="logout" v-loading.fullscreen.lock="fullscreenLoading">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <template v-if="listPermisos.includes('dashboard.index')">
                            <router-link class="nav-link active" :to="'/'">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </router-link>
                        </template>


                    </li>
                    <li class="nav-header">PROCESOS</li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="'/empleado'">
                            <i class="nav-icon fa-solid fa-helmet-safety"></i>
                            <p>Empleados</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="'/activo_tecnologico'">
                            <i class="nav-icon fa-solid fa-microchip"></i>
                            <p>Activos Tecnológicos</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="'/asignar_activos'">
                            <i class="nav-icon fa-solid fa-desktop"></i>
                            <p>Asignar activos</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                PARAMETROS
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link class="nav-link" :to="'/unidad'">
                                    <i class="nav-icon fa-solid fa-helmet-safety"></i>
                                    <p>Unidad</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" :to="'/institucion'">
                                    <i class="nav-icon  fa-solid fa-building"></i>
                                    <p>Institucion</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" :to="'/tipo'">
                                    <i class="nav-icon  fa-solid fa-address-card"></i>
                                    <p>Tipo Empleados</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" :to="'/area'">
                                    <i class="nav-icon fa-solid fa-book-atlas"></i>
                                    <p>Areas</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" :to="'/municipio'">
                                    <i class="nav-icon fa-solid fa-location-pin"></i>
                                    <p>Municipios</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" :to="'/categoria'">
                                    <i class="nav-icon fas fa-sitemap"></i>
                                    <p>Categorias</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" :to="'/tipoActivos'">
                                    <i class="nav-icon fa-solid fa-shop"></i>
                                    <p>Tipos Activos</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" :to="'/marca'">
                                    <i class="nav-icon fa-solid fa-magnifying-glass-arrow-right"></i>
                                    <p>Marcas</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" :to="'/estado'">
                                    <i class="nav-icon fa-solid fa-magnifying-glass-arrow-right"></i>
                                    <p>Estados Activos</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" :to="'/ingreso'">
                                    <i class="nav-icon fa-solid fa-magnifying-glass-arrow-right"></i>
                                    <p>Tipos Ingresos</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>
                    <template
                        v-if="listPermisos.includes('usuario.index') || listPermisos.includes('rol.index') || listPermisos.includes('permiso.index')">
                        <li class="nav-header">ADMINISTRACION</li>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="'/usuario'">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Usuarios</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="'/rol'">
                                <i class="nav-icon fas fa-unlock-alt"></i>
                                <p>Roles</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="'/permiso'">
                                <i class="nav-icon fas fa-key"></i>
                                <p>Permisos</p>
                            </router-link>
                        </li>
                    </template>

                    <li class="nav-header">REPORTES</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-ellipsis-h"></i>
                            <p>Activos</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</template>
<script>

export default {
    props: ['ruta', 'usuario', 'listPermisos'],
    data() {
        return {
            fullscreenLoading: false
        }
    },
    mounted() {
        Echo.private(`logout.user.${this.usuario.id}`)
            .listen('Logout', (e) => {
                // console.log(e)
                this.logout();
            });

    },
    methods: {
        logout() {
            this.fullscreenLoading = true;
            var url = '/authenticate/logout'
            axios.post(url).then(response => {
                if (response.data.code == 204) {
                    this.$router.push({ name: 'login' })
                    location.reload();
                    sessionStorage.clear();
                    this.fullscreenLoading = false;
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    location.reload();
                    sessionStorage.clear();
                    this.fullscreenLoading = false;
                }
            })
        }
    },
}
</script>