<template>
    <div>
        <!-- Navbar -->
        <Navbar :ruta="ruta" :usuario="authUser" :listPermisos="listRolPermisosByUsuario"></Navbar>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <Sidebar :ruta="ruta" :usuario="usuario" :listPermisos="listRolPermisosByUsuario"></Sidebar>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <transition name="slide-fade" mode="out-in">
                <router-view></router-view>
            </transition>


        </div>
        <!-- /.content-wrapper -->
        <Footer></Footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
</template>
<script>
import Navbar from './plantilla/NavBar.vue'
import Sidebar from './plantilla/Sidebar.vue'
import Footer from './plantilla/Footer.vue'
export default {
    props: ['ruta', 'usuario'],
    components: { Navbar, Sidebar, Footer },
    data() {
        return {
            authUser: this.usuario,
            listRolPermisosByUsuario: []
        }
    },
    mounted() {

        this.listRolPermisosByUsuario = JSON.parse(sessionStorage.getItem('listRolPermisosByUsuario'));
        this.$eventBus.on('verifyAuthenticatedUser', (data) => {
            this.authUser = data;
        });
        this.$eventBus.on('notifyRolPermisosByUsuario', (data) => {
            this.listRolPermisosByUsuario = data;
        });
        /*this.$eventBus.$on('verifyAuthenticatedUser', data => {
            // console.log("Evento ejecutado desde el Componente App.vue")
            // console.log(data);
            this.authUser = data;
        })
        this.$eventBus.$on('notifyRolPermisosByUsuario', data => {
            this.listRolPermisosByUsuario = data;
        })*/
    },
}
</script>

<style lang="css">
.slider-fade-enter {
    transform: translateX(10px);
    opacity: 0;
}

.slide-fade-enter-actve .slide-fade-leave-actve {
    transition: all 0.2s ease;
}

.slide-fade-leave-to {
    transform: translateX(-10px);
    opacity: 0;
}
</style>
