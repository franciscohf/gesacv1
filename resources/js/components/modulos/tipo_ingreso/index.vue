<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Tipo de Ingreso</h1>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="content container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">

                        <button @click="crearTipoIngreso" class="btn btn-success btn-sm float-end">
                            <i class="fas fa-plus-square"></i> Nuevo Tipo Ingreso
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Bandeja de Resultados</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover table-head-fixed text-nowrap projects" id="tableTipoIngreso">
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
                                    <tr v-for="(tipoIngreso, index) in tiposIngresos" :key="index">
                                        <td>{{ tipoIngreso.id }}</td>
                                        <td>{{ tipoIngreso.codigo }}</td>
                                        <td>{{ tipoIngreso.descripcion.length <= 20 ? tipoIngreso.descripcion :
                                            tipoIngreso.descripcion.substr(0, 20) + '...' }} </td>
                                        <td>
                                            <template v-if="tipoIngreso.estado == 'A'">
                                                <span class="badge badge-success" v-text="tipoIngreso.estado_dsc"></span>
                                            </template>
                                            <template v-else>
                                                <span class="badge badge-danger" v-text="tipoIngreso.estado_dsc"></span>
                                            </template>
                                        </td>
                                        <td>{{ dateTime(tipoIngreso.created_at) }}</td>
                                        <td>{{ dateTime(tipoIngreso.created_at) }}</td>
                                        <td>
                                            <button @click="editarTipoIngreso(tipoIngreso)" class="btn btn-default btn-sm"> <i
                                                    class="fas fa-pencil-alt"></i> Editar</button>
                                            <button @click="editarEstado(tipoIngreso)" class="btn btn-default btn-sm"><i
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
        <div class="modal" id="tipoIngresoModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="tipoIngresoModalLabel" v-show="!estadoMode"><i class="nav-icon fas fa-sitemap"></i>{{ !editMode ? ' Crear nueva Tipo de Ingresos'
                                    :
                                    ' Editar Tipo de Ingreso' }}</h5>
                        <h5 class="modal-title" id="tipoIngresoModalLabel" v-show="estadoMode"><i class="nav-icon fas fa-sitemap"></i> Cambiar Estado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-show="editMode && !estadoMode">
                            <div class="form-group row">
                                <label for="codigo" class="col-sm-2 col-form-label">Código:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control-plaintext text-warning" id="staticEmail"
                                        v-model="tipoIngresoData.codigo">
                                </div>
                            </div>

                        </div>
                        <div class="row" v-show="!estadoMode">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción:</label>
                                    <textarea class="form-control text-uppercase" rows="3"
                                        v-model="tipoIngresoData.descripcion"></textarea>
                                    <span class="text-danger" v-show="tipoIngresoErrors.descripcion">El campo Descripción es
                                        requerida.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="text-center" v-show="estadoMode">Esta seguro de cambiar el estado del Tipo de Ingreso {{
                        this.tipoIngresoData.estado == 'A' ? ' a Inactivo' : 'a Activo' }} ?</h5>
                    <div class="modal-footer" v-show="!estadoMode">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                                class="fa fa-times"></i></button>
                        <button type="button" @click="!editMode ? storeTipoIngreso() : updateTipoIngreso()"
                            class="btn btn-primary">{{
                                !editMode ? 'Crear Tipo Ingreso' : 'Guardar Cambios' }} <i class="fa fa-check"></i></button>
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
            tiposIngresos: {},
            tipoIngresoData: {
                id: '',
                codigo: '',
                descripcion: '',
                estado: '',

            },
            tipoIngresoErrors: {
                descripcion: false,

            },
        }
    },
    mounted() {

        this.getTiposIngresos();

    },
    methods: {

        getTiposIngresos() {

            axios.get('/parametros/tipo_ingreso/getListarTiposIngresos').then(response => {
                this.tiposIngresos = response.data
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

                $('#tableTipoIngreso').DataTable({
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
            axios.post('/parametros/tipo_ingreso/cambiarEstado/' + this.tipoIngresoData.id).then(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Se actualizo el estado correctamente',
                    showConfirmButton: false,
                    timer: 1500
                })
                this.getTiposIngresos()
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();
                }
            }).finally(() => {
                $('#tipoIngresoModal').modal('hide')
            });
        },
        editarEstado(tipoIngreso) {
            this.estadoMode = true
            this.tipoIngresoData.id = tipoIngreso.id
            this.tipoIngresoData.estado = tipoIngreso.estado
            $('#tipoIngresoModal').modal('show')
        },
        updateTipoIngreso() {

            this.tipoIngresoData.descripcion == '' ? this.tipoIngresoErrors.descripcion = true : this.tipoIngresoErrors.descripcion = false

            if (this.tipoIngresoData.descripcion) {
                axios.post('/parametros/tipo_ingreso/update/' + this.tipoIngresoData.id, this.tipoIngresoData).then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Se actualizo el Tipo de Ingreso correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getTiposIngresos()
                }).catch(error => {
                    if (error.response.status == 401) {
                        this.$router.push({ name: 'login' })
                        // location.reload();
                        sessionStorage.clear();
                    }
                }).finally(() => {
                    $('#tipoIngresoModal').modal('hide')
                });
            }

        },
        editarTipoIngreso(tipoIngreso) {
            this.estadoMode = false
            this.editMode = true
            this.tipoIngresoData = {
                id: tipoIngreso.id,
                codigo: tipoIngreso.codigo,
                descripcion: tipoIngreso.descripcion,
                estado: tipoIngreso.estado,

            }
            this.tipoIngresoErrors = {
                descripcion: false,

            }

            $('#tipoIngresoModal').modal('show')
        },
        crearTipoIngreso() {
            this.estadoMode = false
            this.editMode = false
            this.tipoIngresoData = {
                id: "",
                codigo: "",
                descripcion: "",
                estado: "",
            }
            this.tipoIngresoErrors = {
                descripcion: false,

            }
            $('#tipoIngresoModal').modal('show')
        },
        storeTipoIngreso() {

            this.tipoIngresoData.descripcion == '' ? this.tipoIngresoErrors.descripcion = true : this.tipoIngresoErrors.descripcion = false
            if (this.tipoIngresoData.descripcion) {
                axios.post('/parametros/tipo_ingreso/crear', this.tipoIngresoData).then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Se realizó el registro del Tipo de Ingreso',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getTiposIngresos()
                }).catch(error => {

                    if (error.response.status == 401) {
                        this.$router.push({ name: 'login' })
                        // location.reload();
                        sessionStorage.clear();
                    }
                }).finally(() => {
                    $('#tipoIngresoModal').modal('hide')
                });
            }
        },
    },


}
</script>