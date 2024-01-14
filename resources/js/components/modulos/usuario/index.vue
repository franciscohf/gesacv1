<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Usuarios</h1>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="content container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">

                        <button @click="crearUsuario" class="btn btn-success btn-sm float-end">
                            <i class="fas fa-plus-square"></i> Nuevo Usuario
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Bandeja de Resultados</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover table-head-fixed text-nowrap projects" id="tableUsuarios">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>fotografia</th>
                                        <th>Nombres</th>
                                        <th>Usuario</th>
                                        <th>Correo</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(usuario, index) in usuarios" :key="index">
                                        <td>{{ usuario.id }}</td>
                                        <td>
                                            <template v-if="!usuario.path">
                                                <li class="user-block">
                                                    <img src="/img/imagen_default.png" :alt="usuario.username"
                                                        class="profile-avatar-img img-fluid img-circle">
                                                </li>
                                            </template>
                                            <template v-else>
                                                <li class="user-block">
                                                    <img :src="usuario.path" :alt="usuario.username"
                                                        class="profile-avatar-img img-fluid img-circle">
                                                </li>
                                            </template>
                                        </td>
                                        <td>{{ usuario.name }}</td>
                                        <td>{{ usuario.username }}</td>
                                        <td>{{ usuario.email }}</td>
                                        <td>
                                            <template v-if="usuario.estado == 'A'">
                                                <span class="badge badge-success" v-text="usuario.estado_dsc"></span>
                                            </template>
                                            <template v-else>
                                                <span class="badge badge-danger" v-text="usuario.estado_dsc"></span>
                                            </template>
                                        </td>
                                        <td>
                                            <template v-if="usuario.estado == 'A'">
                                                <button @click="editarUsuario(usuario)" class="btn btn-default btn-sm"> <i
                                                        class="fas fa-pencil-alt"></i> Editar</button>
                                            </template>
                                            <router-link class="btn btn-flat btn-success btn-sm"
                                                :to="{ name: 'usuario.permiso', params: { id: usuario.id } }">
                                                <i class="fas fa-key"></i> Permiso
                                            </router-link>
                                            <button @click="cambiarEstadoUsuario(usuario)" class="btn btn-default btn-sm"><i
                                                    class="fa-solid fa-circle-chevron-down"></i> Estado</button>

                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="usuarioModal" tabindex="-1">
            <div class="modal-dialog  modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="areaModalLabel" v-show="!deleteMode"><i class="nav-icon fas fa-users"></i>
                        {{ !editMode ? ' Crear nueva Usuario'
                                    :
                                    ' Editar Usuario' }}</h5>
                        <h5 class="modal-title" id="areaModalLabel" v-show="deleteMode"><i class="nav-icon fas fa-users"></i>
                             Cambiar Estado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row" v-show="!deleteMode">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Nombres:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control  text-uppercase"
                                            v-model="usuarioData.nombre">
                                        <span class="text-danger" v-show="usuarioErrors.nombre">El campo Nombre
                                            es requerido.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Usuario:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" v-model="usuarioData.nombre_usuario"
                                            :disabled="editMode">
                                        <span class="text-danger" v-show="usuarioErrors.nombre_usuario">El campo Nombre de
                                            Usuario
                                            es requerido.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Correo Electronico:</label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" v-model="usuarioData.correo">
                                        <span class="text-danger" v-show="usuarioErrors.correo">El campo Correo es
                                            requerido.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Contraseña:</label>
                                    <div class="col-md-9">
                                        <el-input placeholder="Ingrese una contraseña" v-model="usuarioData.contrasenia"
                                            show-password></el-input>
                                        <span class="text-danger" v-show="usuarioErrors.contrasenia">La Contraseña
                                            es requerida.</span>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Rol</label>
                                    <div class="col-md-9">
                                        <el-select v-model="usuarioData.nIdRol" placeholder="Seleccione un Rol" clearable>
                                            <el-option v-for="item in listRoles" :key="item.id" :label="item.nombre"
                                                :value="item.id">
                                            </el-option>
                                        </el-select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Fotografia:</label>
                                    <div class="col-md-9">
                                        <input type="file" class="form-control" @change="getFile">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <h5 class="text-center" v-show="deleteMode">Esta seguro de cambiar el estado del Usuario {{
                        this.usuarioData.estado == 'A' ? ' a Inactivo' : 'a Activo' }} ?</h5>
                    <div class="modal-footer" v-show="!deleteMode">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                                class="fa fa-times"></i></button>
                        <button type="button" @click="!editMode ? registrarUsuario() : actualizarUsuario()"
                            class="btn btn-primary">{{
                                !editMode ? 'Crear Usuario' : 'Guardar Cambios' }} <i class="fa fa-check"></i></button>
                    </div>
                    <div class="modal-footer" v-show="deleteMode">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                                class="fa fa-times"></i></button>
                        <button type="button" @click="cambiarEstado" class="btn btn-primary">Cambiar Estado <i
                                class="fa fa-check"></i></button>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <!-- Modal -->
</template>
<script>
import moment from 'moment'
export default {
    data() {
        return {
            editMode: false,
            deleteMode: false,
            usuarios: {},
            usuarioData: {
                id: '',
                nombre: '',
                nombre_usuario: '',
                correo: '',
                contrasenia: '',
                fotografia: '',
                estado: '',
                nIdRol: '',

            },
            usuarioErrors: {

                id: false,
                nombre: false,
                nombre_usuario: false,
                correo: false,
                contrasenia: false,
                fotografia: false,
                estado: false,
                nIdRol: false,

            },
            form: new FormData,

            listRoles: [],
        }
    },
    mounted() {

        this.getUsuarios();
        this.getListarRoles();
    },
    methods: {

        crearUsuario() {
            this.deleteMode = false
            this.editMode = false
            this.usuarioData = {
                id: '',
                nombre: '',
                nombre_usuario: '',
                correo: '',
                contrasenia: '',
                fotografia: '',
                estado: '',
                nIdRol: '',
            }
            this.usuarioErrors = {
                id: false,
                nombre: false,
                nombre_usuario: false,
                correo: false,
                contrasenia: false,
                fotografia: false,
                estado: false,
                nIdRol: false,
            }
            $('#usuarioModal').modal('show')
        },
        getFile(e) {
            this.usuarioData.fotografia = e.target.files[0];
        },
        registrarUsuario() {
alert("sss")
alert(this.usuarioData.fotografia)
            if (!this.usuarioData.fotografia || this.usuarioData.fotografia == undefined) {
                alert("sss1")
                // this.guardarUsuario(0);
            } else {
                alert('tt')
                //this.registrarArchivo();
            }
        },
        registrarArchivo() {
            this.form.append('file', this.usuarioData.fotografia)
            const config = { headers: { 'Content-Type': 'multipart/form-data' } }
            var url = '/archivo/registrarArchivoUsuario'
            axios.post(url, this.form, config).then(response => {
                var nIdFile = response.data;
                this.guardarUsuario(nIdFile);
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                   // location.reload();
                    sessionStorage.clear();
                    
                }
            })
        },
        guardarUsuario(nIdFile) {
            this.usuarioData.nombre == '' ? this.usuarioErrors.nombre = true : this.usuarioErrors.nombre = false
            this.usuarioData.nombre_usuario == '' ? this.usuarioErrors.nombre_usuario = true : this.usuarioErrors.nombre_usuario = false
            this.usuarioData.correo == '' ? this.usuarioErrors.correo = true : this.usuarioErrors.correo = false
            this.usuarioData.contrasenia == '' ? this.usuarioErrors.contrasenia = true : this.usuarioErrors.contrasenia = false
            this.usuarioData.nIdRol == '' ? this.usuarioErrors.nIdRol = true : this.usuarioErrors.nIdRol = false

            if (this.usuarioData.nombre && this.usuarioData.nombre_usuario && this.usuarioData.correo
                && this.usuarioData.contrasenia && this.usuarioData.nIdRol) {
                this.usuarioData.fotografia = nIdFile;
                axios.post('/administracion/usuario/crear', this.usuarioData).then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Se registro el Usuario correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getUsuarios()
                }).catch(errors => {

                    console.log(errors)
                }).finally(() => {
                    $('#usuarioModal').modal('hide')
                });
            }
        },
        getUsuarios() {

            axios.get('/administracion/usuario/getListarUsuarios').then(response => {
                this.usuarios = response.data
                this.table();
            }).catch(errors => {
                 if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    location.reload();
                    sessionStorage.clear();
                   
                }
            });

        },
        table() {
            this.$nextTick(() => {

                $('#tableUsuarios').DataTable({
                    retrieve: true,
                    //  order: [[0, 'desc']],
                    language: {
                        url: '/plugins/datatables/json/Spanish.json',
                    },
                });
            });
        },
        editarUsuario(usuario) {
            this.deleteMode = false
            this.editMode = true
            this.usuarioData = {
                id: usuario.id,
                nombre: usuario.name,
                nombre_usuario: usuario.username,
                correo: usuario.email,
                //contrasenia: usuario.contrasenia,
                fotografia: usuario.fotografia,
                estado: usuario.estado,


            }
            this.usuarioErrors = {
                id: false,
                nombre: false,
                nombre_usuario: false,
                correo: false,
                contrasenia: false,
                fotografia: false,
                estado: false,
                nIdRol: false,

            }
            this.getRolByUsuario();
            $('#usuarioModal').modal('show')
        },
        actualizarUsuario() {


            if (!this.usuarioData.fotografia || this.usuarioData.fotografia == undefined) {

                this.setGuardarUsuario(0);
            } else {

                this.setRegistrarArchivo();
            }
        },
        setRegistrarArchivo() {
            this.form.append('file', this.usuarioData.fotografia)
            const config = { headers: { 'Content-Type': 'multipart/form-data' } }
            var url = '/archivo/registrarArchivoUsuario'
            axios.post(url, this.form, config).then(response => {
                var nIdFile = response.data;
                this.setGuardarUsuario(nIdFile);
            }).catch(error => {
                /*if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    location.reload();
                    sessionStorage.clear();
                    this.fullscreenLoading = false;
                }*/
            })
        },
        setGuardarUsuario(nIdFile) {
            this.usuarioData.nombre == '' ? this.usuarioErrors.nombre = true : this.usuarioErrors.nombre = false
            //  this.usuarioData.nombre_usuario == '' ? this.usuarioErrors.nombre_usuario = true : this.usuarioErrors.nombre_usuario = false
            this.usuarioData.correo == '' ? this.usuarioErrors.correo = true : this.usuarioErrors.correo = false
            //this.usuarioData.contrasenia == '' ? this.usuarioErrors.contrasenia = true : this.usuarioErrors.contrasenia = false
            this.usuarioData.nIdRol == '' ? this.usuarioErrors.nIdRol = true : this.usuarioErrors.nIdRol = false

            if (this.usuarioData.nombre && this.usuarioData.correo && this.usuarioData.nIdRol) {
                this.usuarioData.fotografia = nIdFile;
                axios.post('/administracion/usuario/update/' + this.usuarioData.id, this.usuarioData).then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Se actualizo el registro del Usuario correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getUsuarios()
                }).catch(errors => {

                    console.log(errors)
                }).finally(() => {
                    $('#usuarioModal').modal('hide')
                });
            }
        },
        cambiarEstadoUsuario(usuario) {
            this.deleteMode = true
            this.usuarioData.id = usuario.id
            this.usuarioData.estado = usuario.estado
            $('#usuarioModal').modal('show')
        },

        cambiarEstado() {
            axios.post('/administracion/usuario/cambiarEstado/' + this.usuarioData.id).then(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Se actualizo el estado correctamente',
                    showConfirmButton: false,
                    timer: 1500
                })
                this.getUsuarios()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#usuarioModal').modal('hide')
            });
        },

        getListarRoles() {
            this.fullscreenLoading = true;

            var url = '/administracion/rol/getListarRoles'
            axios.get(url).then(response => {
                this.listRoles = response.data;

            }).catch(error => {

            })
        },
        getRolByUsuario() {
            var url = '/administracion/usuario/getRolByUsuario'
            axios.get(url, {
                params: {
                    'nIdUsuario': this.usuarioData.id
                }
            }).then(response => {
                this.usuarioData.nIdRol = (response.data.length == 0) ? '' : response.data[0].rol_id;
            }).catch(error => {

            })
        },

    },


}
</script>
<style lang="css">
.user-block {
    list-style: none;
}
</style>