<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Permisos</h1>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="content container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">

                        <button @click="crearPermiso" class="btn btn-success btn-sm float-end">
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
                            <table class="table table-hover table-head-fixed text-nowrap projects" id="tablePermiso">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Nombre</th>
                                        <th>Url</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(permiso, index) in permisos" :key="index">
                                        <td>{{ permiso.id }}</td>
                                        <td>{{ permiso.nombre }}</td>
                                        <td>{{ permiso.codigo }}</td>
                                        <td>
                                            <button @click="editarRol(permiso)" class="btn btn-default btn-sm"> <i
                                                    class="fas fa-pencil-alt"></i> Editar</button>
                                            

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
    <div class="modal" id="modalPermiso" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="areaModalLabel"> <i class="nav-icon fas fa-key"></i>
                        {{ !editMode ? ' Crear registro Permiso' : ' Editar Permiso' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="card-body">
                            <form role="form">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Nombre</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="fillPermiso.nombre">
                                                <span class="text-danger" v-show="permisoErrors.nombre">
                                                    {{ 'El Nombre es un campo obligatorio' }} </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Url</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="fillPermiso.codigo">
                                                <span class="text-danger" v-show="permisoErrors.codigo">
                                                    {{ 'La url es un campo obligatorio' }} </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                            class="fa fa-times"></i></button>
                    <button type="button" @click="!editMode ? setRegistrarPermisos() : setEditarRolPermisos()"
                        class="btn btn-primary">{{
                            !editMode ? 'Registrar' : 'Guardar Cambios' }} <i class="fa fa-check"></i></button>
                </div>


            </div>
        </div>
    </div>
</template>
<script>

export default {
    data() {
        return {
            permisos: {},
            editMode: false,
            //para insertar rol variables
            fillPermiso: {
                id: '',
                nombre: '',
                codigo: '',
            },

            listPermisos: [],
            permisoErrors: {
                nombre: false,
                codigo: false,
            },
        }
    },
    mounted() {
        this.getPermisos();
    },
    methods: {

        getPermisos() {

            axios.get('/administracion/permiso/getListarPermisos').then(response => {
                this.permisos = response.data
                this.table();
            }).catch(errors => {
                console.log(errors)
            });

        },
        table() {
            this.$nextTick(() => {

                $('#tablePermiso').DataTable({
                    retrieve: true,
                    order: [[0, 'desc']],
                    language: {
                        url: '/plugins/datatables/json/Spanish.json',
                    },
                });
            });
        },
        crearPermiso() {
            this.editMode = false
            this.fillPermiso = {
                id: "",
                nombre: "",
                codigo: "",
            },
                this.permisoErrors = {
                    nombre: false,
                    codigo: false,
                },

                $('#modalPermiso').modal('show')
        },
        editarRol(permiso) {
            this.editMode = true
            this.fillPermiso = {
                id: permiso.id,
                nombre: permiso.nombre,
                codigo: permiso.codigo,
            },
                this.permisoErrors = {
                    nombre: false,
                    codigo: false,
                },


                $('#modalPermiso').modal('show')
        },
        //metodos para insertar
           

        setRegistrarPermisos() {

            this.fillPermiso.nombre == '' ? this.permisoErrors.nombre = true : this.permisoErrors.nombre = false
            this.fillPermiso.codigo == '' ? this.permisoErrors.codigo = true : this.permisoErrors.codigo = false
            
            if (this.fillPermiso.nombre && this.fillPermiso.codigo) {
                    var url = '/administracion/permiso/setRegistrarPermiso'
                    axios.post(url, this.fillPermiso).then(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Se registro el Permiso correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })

                    }).catch(error => {

                    }).finally(() => {
                        this.getPermisos();
                        $('#modalPermiso').modal('hide')
                    });
                

            }

        },
        setEditarRolPermisos() {
            this.fillPermiso.nombre == '' ? this.permisoErrors.nombre = true : this.permisoErrors.nombre = false
            this.fillPermiso.codigo == '' ? this.permisoErrors.codigo = true : this.permisoErrors.codigo = false
           
            if (this.fillPermiso.nombre && this.fillPermiso.codigo) {
               
                    var url = '/administracion/permiso/update/'
                    axios.post(url+this.fillPermiso.id, this.fillPermiso).then(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Se actualizo el registro del Permiso',
                            showConfirmButton: false,
                            timer: 1500
                        })

                    }).catch(error => {

                    }).finally(() => {
                        this.getPermisos();
                        $('#modalPermiso').modal('hide')
                    });

                

            }

        },

    },
}
</script>

<style lang="css"></style>