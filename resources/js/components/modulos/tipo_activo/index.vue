<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Tipos Activos</h1>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="content container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">

                        <button @click="crearTipoActivo" class="btn btn-success btn-sm float-end">
                            <i class="fas fa-plus-square"></i> Nuevo Municipio
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Bandeja de Resultados</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover table-head-fixed text-nowrap projects" id="tableTiposActivos">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>Categoría</th>
                                        <th>Estado</th>
                                        <th>Fecha Alta</th>
                                        <th>Fecha Modificación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(tipoActivo, index) in tiposActivos" :key="index">
                                        <td>{{ tipoActivo.id }}</td>
                                        <td>{{ tipoActivo.codigo }}</td>
                                        <td>{{ tipoActivo.descripcion.length <= 20 ? tipoActivo.descripcion :
                                            tipoActivo.descripcion.substr(0, 20) + '...' }} </td>
                                        <td>{{ tipoActivo.categoria_descripcion.length <= 20 ?
                                            tipoActivo.categoria_descripcion :
                                            tipoActivo.categoria_descripcion.substr(0, 20) + '...' }} </td>
                                       <td>
                                            <template v-if="tipoActivo.estado == 'A'">
                                                <span class="badge badge-success" v-text="tipoActivo.estado_dsc"></span>
                                            </template>
                                            <template v-else>
                                                <span class="badge badge-danger" v-text="tipoActivo.estado_dsc"></span>
                                            </template>
                                        </td>
                                        <td>{{ dateTime(tipoActivo.created_at) }}</td>
                                        <td>{{ dateTime(tipoActivo.created_at) }}</td>
                                        <td>
                                            <button @click="editarTipoActivo(tipoActivo)" class="btn btn-default btn-sm"> <i
                                                    class="fas fa-pencil-alt"></i> Editar</button>
                                            <button @click="editarEstado(tipoActivo)" class="btn btn-default btn-sm"><i
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
        <div class="modal" id="tiposActivosModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="areaModalLabel" v-show="!estadoMode"><i class="fa-solid fa-shop"></i>{{
                                    !editMode ? ' Crear nuevo Tipo de Activo'
                                    :
                                    ' Editar Tipo de Activo' }}</h5>
                        <h5 class="modal-title" id="areaModalLabel" v-show="estadoMode"><i class="fa-solid fa-shop"></i>
                            Cambiar Estado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-show="editMode && !estadoMode">
                            <div class="form-group row">
                                <label for="codigo" class="col-sm-2 col-form-label">Código:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control-plaintext text-warning" id="staticEmail"
                                        v-model="tipoActivoData.codigo">
                                </div>
                            </div>

                        </div>
                        <div class="row" v-show="!estadoMode">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Categoría:</label>
                                    <select class="browser-default custom-select" v-model="tipoActivoData.categoria_id">
                                        <option v-for="categoria in categorias" :value="categoria.id">{{ categoria.descripcion }}
                                        </option>
                                    </select>
                                    <span class="text-danger" v-show="tipoActivoErrors.descripcion">La Categoría es
                                        requerida.</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción:</label>
                                    <textarea class="form-control text-uppercase" rows="3"
                                        v-model="tipoActivoData.descripcion"></textarea>
                                    <span class="text-danger" v-show="tipoActivoErrors.descripcion">El campo Descripción es
                                        requerida.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="text-center" v-show="estadoMode">Esta seguro de cambiar el estado del Tipo de Activo {{
                        this.tipoActivoData.estado == 'A' ? ' a Inactivo' : 'a Activo' }} ?</h5>
                    <div class="modal-footer" v-show="!estadoMode">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                                class="fa fa-times"></i></button>
                        <button type="button" @click="!editMode ? storeTipoActivo() : updateTipoActivo()"
                            class="btn btn-primary">{{
                                !editMode ? 'Crear Tipo de Activo ' : 'Guardar Cambios ' }}<i class="fa fa-check"></i></button>
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
            tiposActivos: {},
            categorias: {},
            tipoActivoData: {
                id: '',
                codigo: '',
                descripcion: '',
                estado: '',
                categoria_id: '',

            },
            tipoActivoErrors: {
                descripcion: false,
                categoria_id: false,
            },
        }
    },
    mounted() {

        this.gerTiposActivos();
        this.getCategorias();
        //this.table();
    },
    methods: {

        gerTiposActivos() {

            axios.get('/parametros/tipos_activos/getListarTiposActivos').then(response => {
                this.tiposActivos = response.data
                this.table();
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();
                }
            });

        },
        getCategorias() {

            axios.get('/parametros/categoria/getListarCategorias').then(response => {
                this.categorias = response.data
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

                $('#tableTiposActivos').DataTable({
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
            axios.post('/parametros/tipos_activos/cambiarEstado/' + this.tipoActivoData.id).then(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Se actualizo el estado correctamente',
                    showConfirmButton: false,
                    timer: 1500
                })
                this.gerTiposActivos()
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();
                }
            }).finally(() => {
                $('#tiposActivosModal').modal('hide')
            });
        },
        editarEstado(tipoActivo) {
            this.estadoMode = true
            this.tipoActivoData.id = tipoActivo.id
            this.tipoActivoData.estado = tipoActivo.estado
            this.tipoActivoData.categoria_id = tipoActivo.categoria_id
            $('#tiposActivosModal').modal('show')
        },
        updateTipoActivo() {

            this.tipoActivoData.descripcion == '' ? this.tipoActivoErrors.descripcion = true : this.tipoActivoErrors.descripcion = false
            this.tipoActivoData.categoria_id == '' ? this.tipoActivoErrors.categoria_id = true : this.tipoActivoErrors.categoria_id = false

            if (this.tipoActivoData.descripcion && this.tipoActivoData.categoria_id) {
                axios.post('/parametros/tipos_activos/update/' + this.tipoActivoData.id, this.tipoActivoData).then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Se actualizo el Tipo Activo correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.gerTiposActivos()
                }).catch(error => {
                    if (error.response.status == 401) {
                        this.$router.push({ name: 'login' })
                        // location.reload();
                        sessionStorage.clear();
                    }
                }).finally(() => {
                    $('#tiposActivosModal').modal('hide')
                });
            }

        },
        editarTipoActivo(tipoActivo) {
            this.estadoMode = false
            this.editMode = true
            this.tipoActivoData = {
                id: tipoActivo.id,
                codigo: tipoActivo.codigo,
                descripcion: tipoActivo.descripcion,
                estado: tipoActivo.estado,
                categoria_id: tipoActivo.categoria_id,
            }
            this.tipoActivoErrors = {
                descripcion: false,
                categoria_id: false,
            }

            $('#tiposActivosModal').modal('show')
        },
        crearTipoActivo() {
            this.estadoMode = false
            this.editMode = false
            this.tipoActivoData = {
                id: "",
                codigo: "",
                descripcion: "",
                estado: "",
                categoria_id: "",
            }
            this.tipoActivoErrors = {
                descripcion: false,
                categoria_id: false,
            }
            $('#tiposActivosModal').modal('show')
        },
        storeTipoActivo() {

            this.tipoActivoData.descripcion == '' ? this.tipoActivoErrors.descripcion = true : this.tipoActivoErrors.descripcion = false
            this.tipoActivoData.categoria_id == '' ? this.tipoActivoErrors.categoria_id = true : this.tipoActivoErrors.categoria_id = false

            if (this.tipoActivoData.descripcion && this.tipoActivoData.categoria_id) {
                axios.post('/parametros/tipos_activos/crear', this.tipoActivoData).then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Se grabo el Tipo Activo correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.gerTiposActivos()
                }).catch(errors => {

                    if (error.response.status == 401) {
                        this.$router.push({ name: 'login' })
                        // location.reload();
                        sessionStorage.clear();
                    }
                }).finally(() => {
                    $('#tiposActivosModal').modal('hide')
                });
            }
        },
    },


}
</script>