<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Categoría</h1>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="content container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">

                        <button @click="crearCategoria" class="btn btn-success btn-sm float-end">
                            <i class="fas fa-plus-square"></i> Nuevo Categoría
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Bandeja de Resultados</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover table-head-fixed text-nowrap projects" id="tableCategoria">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th>Fecha Alta</th>
                                        <th>Fecha Modificación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(categoria, index) in categorias" :key="index">
                                        <td>{{ categoria.id }}</td>
                                        <td>{{ categoria.codigo }}</td>
                                        <td>{{ categoria.descripcion.length <= 20 ? categoria.descripcion :
                                            categoria.descripcion.substr(0, 20) + '...' }} </td>
                                        <td>
                                            <template v-if="categoria.estado == 'A'">
                                                <span class="badge badge-success" v-text="categoria.estado_dsc"></span>
                                            </template>
                                            <template v-else>
                                                <span class="badge badge-danger" v-text="categoria.estado_dsc"></span>
                                            </template>
                                        </td>
                                        <td>{{ dateTime(categoria.created_at) }}</td>
                                        <td>{{ dateTime(categoria.created_at) }}</td>
                                        <td>
                                            <button @click="editarCategoria(categoria)" class="btn btn-default btn-sm"> <i
                                                    class="fas fa-pencil-alt"></i> Editar</button>
                                            <button @click="editarEstado(categoria)" class="btn btn-default btn-sm"><i
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
        <div class="modal" id="categoriaModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="categoriaModalLabel" v-show="!estadoMode"><i class="nav-icon fas fa-sitemap"></i>{{ !editMode ? ' Crear nueva Categoría'
                                    :
                                    ' Editar Categoría' }}</h5>
                        <h5 class="modal-title" id="categoriaModalLabel" v-show="estadoMode"><i class="nav-icon fas fa-sitemap"></i> Cambiar Estado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-show="editMode && !estadoMode">
                            <div class="form-group row">
                                <label for="codigo" class="col-sm-2 col-form-label">Código:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control-plaintext text-warning" id="staticEmail"
                                        v-model="categoriaData.codigo">
                                </div>
                            </div>

                        </div>
                        <div class="row" v-show="!estadoMode">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción:</label>
                                    <textarea class="form-control text-uppercase" rows="3"
                                        v-model="categoriaData.descripcion"></textarea>
                                    <span class="text-danger" v-show="categoriaErrors.descripcion">El campo Descripción es
                                        requerida.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="text-center" v-show="estadoMode">Esta seguro de cambiar el estado de la Categoría {{
                        this.categoriaData.estado == 'A' ? ' a Inactivo' : 'a Activo' }} ?</h5>
                    <div class="modal-footer" v-show="!estadoMode">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                                class="fa fa-times"></i></button>
                        <button type="button" @click="!editMode ? storeCategoria() : updateCategoria()"
                            class="btn btn-primary">{{
                                !editMode ? 'Crear Categoría' : 'Guardar Cambios' }} <i class="fa fa-check"></i></button>
                    </div>
                    <div class="modal-footer" v-show="estadoMode">
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
            estadoMode: false,
            categorias: {},
            categoriaData: {
                id: '',
                codigo: '',
                descripcion: '',
                estado: '',

            },
            categoriaErrors: {
                descripcion: false,

            },
        }
    },
    mounted() {

        this.getCategorias();

    },
    methods: {

        getCategorias() {

            axios.get('/parametros/categoria/getListarCategorias').then(response => {
                this.categorias = response.data
                this.table();
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();
                }
            });

        },
        table() {
            this.$nextTick(() => {

                $('#tableCategoria').DataTable({
                    retrieve: true,
                    order: [[0, 'desc']],
                    language: {
                        url: '/plugins/datatables/json/Spanish.json',
                    },
                });
            });
        },
        dateTime(value) {
            return moment(value).format("YYYY/MM/DD H:mm:ss a");
        },
        cambiarEstado() {
            axios.post('/parametros/categoria/cambiarEstado/' + this.categoriaData.id).then(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Se actualizo el estado correctamente',
                    showConfirmButton: false,
                    timer: 1500
                })
                this.getCategorias()
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();
                }
            }).finally(() => {
                $('#categoriaModal').modal('hide')
            });
        },
        editarEstado(categoria) {
            this.estadoMode = true
            this.categoriaData.id = categoria.id
            this.categoriaData.estado = categoria.estado
            $('#categoriaModal').modal('show')
        },
        updateCategoria() {

            this.categoriaData.descripcion == '' ? this.categoriaErrors.descripcion = true : this.categoriaErrors.descripcion = false

            if (this.categoriaData.descripcion) {
                axios.post('/parametros/categoria/update/' + this.categoriaData.id, this.categoriaData).then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Se actualizo La Categoría correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getCategorias()
                }).catch(error => {
                    if (error.response.status == 401) {
                        this.$router.push({ name: 'login' })
                        // location.reload();
                        sessionStorage.clear();
                    }
                }).finally(() => {
                    $('#categoriaModal').modal('hide')
                });
            }

        },
        editarCategoria(categoria) {
            this.estadoMode = false
            this.editMode = true
            this.categoriaData = {
                id: categoria.id,
                codigo: categoria.codigo,
                descripcion: categoria.descripcion,
                estado: categoria.estado,

            }
            this.categoriaErrors = {
                descripcion: false,

            }

            $('#categoriaModal').modal('show')
        },
        crearCategoria() {
            this.estadoMode = false
            this.editMode = false
            this.categoriaData = {
                id: "",
                codigo: "",
                descripcion: "",
                estado: "",
            }
            this.categoriaErrors = {
                descripcion: false,

            }
            $('#categoriaModal').modal('show')
        },
        storeCategoria() {

            this.categoriaData.descripcion == '' ? this.categoriaErrors.descripcion = true : this.categoriaErrors.descripcion = false
            if (this.categoriaData.descripcion) {
                axios.post('/parametros/categoria/crear', this.categoriaData).then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Se grabo la Categoría correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getCategorias()
                }).catch(error => {

                    if (error.response.status == 401) {
                        this.$router.push({ name: 'login' })
                        // location.reload();
                        sessionStorage.clear();
                    }
                }).finally(() => {
                    $('#categoriaModal').modal('hide')
                });
            }
        },
    },


}
</script>