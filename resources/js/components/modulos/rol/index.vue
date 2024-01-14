<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Roles</h1>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="content container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">

                        <button @click="crearRol" class="btn btn-success btn-sm float-end">
                            <i class="fas fa-plus-square"></i> Nuevo Rol
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Bandeja de Resultados</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover table-head-fixed text-nowrap projects" id="tableRol">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Nombre</th>
                                        <th>Url</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(rol, index) in roles" :key="index">
                                        <td>{{ rol.id }}</td>
                                        <td>{{ rol.nombre }}</td>
                                        <td>{{ rol.url }}</td>
                                        <td>
                                            <button @click="editarRol(rol)" class="btn btn-default btn-sm"> <i
                                                    class="fas fa-pencil-alt"></i> Editar</button>
                                            <button @click="abrirModalByOption('rol', 'ver', rol)"
                                                class="btn btn-default btn-sm"> <i class="fas fa-folder"></i> Ver</button>


                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- Modal -->
    <div class="modal" id="modalCrearRol" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="areaModalLabel"> <i class="nav-icon fas fa-unlock-alt"></i>
                        {{ !editMode ? ' Crear nuevo Rol' : ' Editar Rol' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">Formulario Registrar Rol</h3>
                                        </div>
                                        <div class="card-body">
                                            <form role="form">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 col-form-label">Nombre</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control"
                                                                    v-model="fillCrearRol.cNombre">
                                                                <span class="text-danger" v-show="rolErrors.nombre">El
                                                                    Nombre es
                                                                    un campo obligatorio</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 col-form-label">Url Amigable</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control"
                                                                    v-model="fillCrearRol.cSlug">
                                                                <span class="text-danger" v-show="rolErrors.url">La Url
                                                                    Amigable
                                                                    es un campo obligatorio</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="card-footer">
                                            <div class="row">

                                                <button class="btn btn-flat btn-default btnWidth"
                                                    @click.prevent="limpiarCriterios">Limpiar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">Listar Permisos</h3>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <template v-if="listPermisosFilter.length">
                                                <div class="scrollTable">
                                                    <table class="table table-hover table-head-fixed text-nowrap projects">
                                                        <thead>
                                                            <tr>
                                                                <th>Acción</th>
                                                                <th>Nombre</th>
                                                                <th>Url Amigable</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(item, index) in listPermisosFilter" :key="index"
                                                                @click.prevent="marcarFila(index)">
                                                                <td>
                                                                    <!-- Ira el Checkbox para seleccionar los permisos que se le asignaran al rol -->
                                                                    <el-checkbox v-model="item.checked"></el-checkbox>
                                                                </td>
                                                                <td v-text="item.nombre"></td>
                                                                <td v-text="item.codigo"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </template>
                                            <template v-else>
                                                <div class="callout callout-info">
                                                    <h5>No se encontraron resultados...</h5>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                            class="fa fa-times"></i></button>
                    <button type="button" @click="!editMode ? setRegistrarRolPermisos() : setEditarRolPermisos()"
                        class="btn btn-primary">{{
                            !editMode ? 'Registrar' : 'Guardar Cambios' }} <i class="fa fa-check"></i></button>
                </div>


            </div>
        </div>
    </div>
    <div class="modal" id="modalVer" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="areaModalLabel"> <i class="nav-icon fas fa-unlock-alt"></i>
                        {{ ' Roles' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <div class="container-fluid">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Información del Rol</h3>
                            </div>
                            <div class="card-body">
                                <form role="form">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-md-12 col-form-label">Nombre</label>
                                                <div class="col-md-12">
                                                    <span class="form-control" v-text="fillVerRol.cNombre"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-md-12 col-form-label">Url Amigable</label>
                                                <div class="col-md-12">
                                                    <span class="form-control" v-text="fillVerRol.cSlug"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Listado de Permisos</h3>
                            </div>
                            <div class="card-body table-responsive">

                                <div class="scrollTable">
                                    <table class="table table-hover table-head-fixed text-nowrap projects">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Url Amigable</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) in listPermisos" :key="index">
                                                <template v-if="item.checked == 1">
                                                    <td v-text="item.nombre"></td>
                                                    <td v-text="item.codigo"></td>
                                                </template>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                            class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    data() {
        return {
            roles: {},
            editMode: false,
            //para insertar rol variables
            fillCrearRol: {
                nIdRol: '',
                cNombre: '',
                cSlug: '',
            },
            fillVerRol: {
                cNombre: '',
                cSlug: ''
            },
            listPermisos: [],
            listPermisosFilter: [],
            listRolPermisosByUsuario: [],
            listRolPermisosByUsuarioFilter: [],
            rolErrors: {
                nombre: false,
                url: false,
            },
        }
    },
    mounted() {
        this.getRoles();
    },
    methods: {

        getRoles() {

            axios.get('/administracion/rol/getListarRoles').then(response => {
                this.roles = response.data
                this.table();
            }).catch(errors => {
                console.log(errors)
            });

        },
        table() {
            this.$nextTick(() => {

                $('#tableRol').DataTable({
                    retrieve: true,
                    order: [[0, 'desc']],
                    language: {
                        url: '/plugins/datatables/json/Spanish.json',
                    },
                });
            });
        },
        crearRol() {
            this.editMode = false
            this.fillCrearRol = {
                nIdRol: "",
                cNombre: "",
                cSlug: "",
            },
                this.rolErrors = {
                    nombre: false,
                    url: false,
                },
                this.listPermisosFilter = [],
                this.getListarPermisosByRol(0);
            $('#modalCrearRol').modal('show')
        },
        editarRol(rol) {
            this.editMode = true
            this.fillCrearRol = {
                nIdRol: rol.id,
                cNombre: rol.nombre,
                cSlug: rol.url,
            },
                this.rolErrors = {
                    nombre: false,
                    url: false,
                },
                this.listPermisosFilter = [],
                this.getListarPermisosByRol(rol.id);
            $('#modalCrearRol').modal('show')
        },
        //metodos para insertar
        limpiarCriterios() {
            this.fillCrearRol.cNombre = '';
            this.fillCrearRol.cSlug = '';
        },
        getListarPermisosByRol(idRol) {

            var ruta = '/administracion/rol/getListarPermisosByRol'

            axios.get(ruta, { params: { nIdRol: idRol } }).then(response => {

                this.listPermisos = response.data;

                this.filterPermisosByRol();
            }).catch(error => {


            })
        },
        filterPermisosByRol() {

            let me = this;

            me.listPermisos.map(function (x, y) {
                me.listPermisosFilter.push({
                    'id': x.id,
                    'nombre': x.nombre,
                    'codigo': x.codigo,
                    'checked': (x.checked == 1) ? true : false
                })
            })
        },
        marcarFila(index) {
            this.listPermisosFilter[index].checked = !this.listPermisosFilter[index].checked;
        },

        setRegistrarRolPermisos() {

            this.fillCrearRol.cNombre == '' ? this.rolErrors.nombre = true : this.rolErrors.nombre = false
            this.fillCrearRol.cSlug == '' ? this.rolErrors.url = true : this.rolErrors.url = false
            let contador = 0;
            this.listPermisosFilter.map(function (x, y) {
                if (x.checked == true) {
                    contador++;
                }
            })

            if (this.fillCrearRol.cNombre && this.fillCrearRol.cSlug) {
                if (contador == 0) {
                    Swal.fire({
                        title: "Permisos",
                        text: "Debe seleccionar al menos un permiso!",
                        icon: "warning"
                    });
                } else {

                    this.$router.push('/rol', null, { shallow: true })
                    var url = '/administracion/rol/setRegistrarRolPermisos'
                    axios.post(url, {
                        'cNombre': this.fillCrearRol.cNombre,
                        'cSlug': this.fillCrearRol.cSlug,
                        'listPermisosFilter': this.listPermisosFilter
                    }).then(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Se registro el Rol correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })

                    }).catch(error => {

                    }).finally(() => {
                        this.getRoles();
                        $('#modalCrearRol').modal('hide')
                    });
                }

            }

        },
        setEditarRolPermisos() {
            this.fillCrearRol.cNombre == '' ? this.rolErrors.nombre = true : this.rolErrors.nombre = false
            this.fillCrearRol.cSlug == '' ? this.rolErrors.url = true : this.rolErrors.url = false
            let contador = 0;
            this.listPermisosFilter.map(function (x, y) {
                if (x.checked == true) {
                    contador++;
                }
            })
            if (this.fillCrearRol.cNombre && this.fillCrearRol.cSlug) {
                if (contador == 0) {
                    Swal.fire({
                        title: "Permisos",
                        text: "Debe seleccionar al menos un permiso!",
                        icon: "warning"
                    });
                } else {
                    var url = '/administracion/rol/setEditarRolPermisos'
                    axios.post(url, {
                        'nIdRol': this.fillCrearRol.nIdRol,
                        'cNombre': this.fillCrearRol.cNombre,
                        'cSlug': this.fillCrearRol.cSlug,
                        'listPermisosFilter': this.listPermisosFilter
                    }).then(response => {
                        this.getListarRolPermisosByUsuario();

                    }).catch(error => {

                    }).finally(() => {
                        this.getRoles();
                        $('#modalCrearRol').modal('hide')
                    });

                }

            }

        },
        getListarRolPermisosByUsuario() {
            var ruta = '/administracion/usuario/getListarRolPermisosByUsuario'
            axios.get(ruta).then(response => {
                this.listRolPermisosByUsuario = response.data;
                this.filterListarRolPermisosByUsuario();
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    location.reload();
                    sessionStorage.clear();
                    this.fullscreenLoading = false;
                }
            })
        },
        filterListarRolPermisosByUsuario() {
            let me = this;
            me.listRolPermisosByUsuarioFilter = [];
            me.listRolPermisosByUsuario.map(function (x, y) {
                me.listRolPermisosByUsuarioFilter.push(x.codigo)
            })
            sessionStorage.setItem('listRolPermisosByUsuario', JSON.stringify(me.listRolPermisosByUsuarioFilter));
            this.$eventBus.emit('notifyRolPermisosByUsuario', me.listRolPermisosByUsuarioFilter);
            this.fullscreenLoading = false;
            Swal.fire({
                icon: 'success',
                title: 'Se actualizo el rol correctamente',
                showConfirmButton: false,
                timer: 1500
            })
        },

        abrirModalByOption(modulo, accion, data) {
            switch (modulo) {
                case "rol":
                    {
                        switch (accion) {
                            case "ver":
                                {
                                    //Setear información del arreglo
                                    this.fillVerRol.cNombre = data.nombre;
                                    this.fillVerRol.cSlug = data.url;
                                    //Obtener los permisos por el rol seleccionado
                                    this.getListarPermisosByRol(data.id);
                                    $('#modalVer').modal('show')
                                }
                                break;

                            default:
                                break;
                        }
                    }
                    break;

                default:
                    break;
            }
        }
    },
}
</script>

<style lang="css">
.scrollTable {
    max-height: 350px !important;
    overflow: auto !important;
}
</style>