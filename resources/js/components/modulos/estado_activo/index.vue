<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Estado de Activos</h1>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="content container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">

                        <button @click="crearEstadoActivo" class="btn btn-success btn-sm float-end">
                            <i class="fas fa-plus-square"></i> Nueva Estado
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Bandeja de Resultados</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover table-head-fixed text-nowrap projects" id="tableEstadoActivo">
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
                                    <tr v-for="(estadosActivo, index) in estadosActivos" :key="index">
                                        <td>{{ estadosActivo.id }}</td>
                                        <td>{{ estadosActivo.codigo }}</td>
                                        <td>{{ estadosActivo.descripcion.length <= 20 ? estadosActivo.descripcion :
                                            estadosActivo.descripcion.substr(0, 20) + '...' }} </td>
                                        <td>
                                            <template v-if="estadosActivo.estado == 'A'">
                                                <span class="badge badge-success" v-text="estadosActivo.estado_dsc"></span>
                                            </template>
                                            <template v-else>
                                                <span class="badge badge-danger" v-text="estadosActivo.estado_dsc"></span>
                                            </template>
                                        </td>
                                        <td>{{ dateTime(estadosActivo.created_at) }}</td>
                                        <td>{{ dateTime(estadosActivo.created_at) }}</td>
                                        <td>
                                            <button @click="editarEstadoActivo(estadosActivo)" class="btn btn-default btn-sm"> <i
                                                    class="fas fa-pencil-alt"></i> Editar</button>
                                            <button @click="editarEstado(estadosActivo)" class="btn btn-default btn-sm"><i
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
        <div class="modal" id="estadoActivoModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="estadoActivoModalLabel" v-show="!estadoMode"><i class="fa-solid fa-magnifying-glass-arrow-right"></i>{{ !editMode ? ' Crear nuevo Estado'
                                    :
                                    ' Editar Estado' }}</h5>
                        <h5 class="modal-title" id="estadoActivoModalLabel" v-show="estadoMode"><i class="fa-solid fa-magnifying-glass-arrow-right"></i> Cambiar Estado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-show="editMode && !estadoMode">
                            <div class="form-group row">
                                <label for="codigo" class="col-sm-2 col-form-label">Código:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control-plaintext text-warning" id="staticEmail"
                                        v-model="estadoActivoData.codigo">
                                </div>
                            </div>

                        </div>
                        <div class="row" v-show="!estadoMode">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción:</label>
                                    <textarea class="form-control text-uppercase" rows="3"
                                        v-model="estadoActivoData.descripcion"></textarea>
                                    <span class="text-danger" v-show="estadoActivoErrors.descripcion">El campo Descripción es
                                        requerida.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="text-center" v-show="estadoMode">Esta seguro de cambiar el estado de Tipos de Estados {{
                        this.estadoActivoData.estado == 'A' ? ' a Inactivo' : 'a Activo' }} ?</h5>
                    <div class="modal-footer" v-show="!estadoMode">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                                class="fa fa-times"></i></button>
                        <button type="button" @click="!editMode ? storeEstadoActivo() : updateEstadoActivo()"
                            class="btn btn-primary">{{
                                !editMode ? 'Crear Estado' : 'Guardar Cambios' }} <i class="fa fa-check"></i></button>
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
            estadosActivos: {},
            estadoActivoData: {
                id: '',
                codigo: '',
                descripcion: '',
                estado: '',

            },
            estadoActivoErrors: {
                descripcion: false,

            },
        }
    },
    mounted() {

        this.getEstadoActivos();

    },
    methods: {

        getEstadoActivos() {

            axios.get('/parametros/estado_activo/getListarEstadosActivos').then(response => {
                this.estadosActivos = response.data
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

                $('#tableEstadoActivo').DataTable({
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
            axios.post('/parametros/estado_activo/cambiarEstado/' + this.estadoActivoData.id).then(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Se actualizo el estado correctamente',
                    showConfirmButton: false,
                    timer: 1500
                })
                this.getEstadoActivos()
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();
                }
            }).finally(() => {
                $('#estadoActivoModal').modal('hide')
            });
        },
        editarEstado(marca) {
            this.estadoMode = true
            this.estadoActivoData.id = marca.id
            this.estadoActivoData.estado = marca.estado
            $('#estadoActivoModal').modal('show')
        },
        updateEstadoActivo() {

            this.estadoActivoData.descripcion == '' ? this.estadoActivoErrors.descripcion = true : this.estadoActivoErrors.descripcion = false

            if (this.estadoActivoData.descripcion) {
                axios.post('/parametros/estado_activo/update/' + this.estadoActivoData.id, this.estadoActivoData).then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Se actualizo el Estado de Activos correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getEstadoActivos()
                }).catch(error => {
                    if (error.response.status == 401) {
                        this.$router.push({ name: 'login' })
                        // location.reload();
                        sessionStorage.clear();
                    }
                }).finally(() => {
                    $('#estadoActivoModal').modal('hide')
                });
            }

        },
        editarEstadoActivo(marca) {
            this.estadoMode = false
            this.editMode = true
            this.estadoActivoData = {
                id: marca.id,
                codigo: marca.codigo,
                descripcion: marca.descripcion,
                estado: marca.estado,

            }
            this.estadoActivoErrors = {
                descripcion: false,

            }

            $('#estadoActivoModal').modal('show')
        },
        crearEstadoActivo() {
            this.estadoMode = false
            this.editMode = false
            this.estadoActivoData = {
                id: "",
                codigo: "",
                descripcion: "",
                estado: "",
            }
            this.estadoActivoErrors = {
                descripcion: false,

            }
            $('#estadoActivoModal').modal('show')
        },
        storeEstadoActivo() {

            this.estadoActivoData.descripcion == '' ? this.estadoActivoErrors.descripcion = true : this.estadoActivoErrors.descripcion = false
            if (this.estadoActivoData.descripcion) {
                axios.post('/parametros/estado_activo/crear', this.estadoActivoData).then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Se grabo la Marca correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getEstadoActivos()
                }).catch(error => {

                    if (error.response.status == 401) {
                        this.$router.push({ name: 'login' })
                        // location.reload();
                        sessionStorage.clear();
                    }
                }).finally(() => {
                    $('#estadoActivoModal').modal('hide')
                });
            }
        },
    },


}
</script>