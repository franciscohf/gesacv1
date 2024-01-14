<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Asignar activos</h1>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="content container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">

                        <button @click="openModalAsignar" class="btn btn-success btn-sm float-end">
                            <i class="fas fa-plus-square"></i> Nuevo Registro
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Bandeja de Resultados</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover table-head-fixed text-nowrap projects" id="tableMunicipio">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Código</th>
                                        <th>ID Tarjeta</th>
                                        <th>Razón social</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>Estado</th>
                                        <th>Fecha Alta</th>
                                        <th>Fecha Modificación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(empleado, index) in empleados" :key="index">
                                        <td>{{ empleado.id }}</td>
                                        <td>{{ empleado.codigo }}</td>
                                        <td>{{ empleado.tarjeta_identificacion }}</td>
                                        <td>{{ empleado.razon_social }}</td>
                                        <td>{{ empleado.direccion.length <= 20 ? empleado.direccion :
                                            direccion.direccion.substr(0, 20) + '...' }} </td>
                                        <td>{{ empleado.telefono }}</td>
                                        <td>
                                            <template v-if="empleado.estado == 'A'">
                                                <span class="badge badge-success" v-text="empleado.estado_dsc"></span>
                                            </template>
                                            <template v-else>
                                                <span class="badge badge-danger" v-text="empleado.estado_dsc"></span>
                                            </template>
                                        </td>
                                        <td>{{ dateTime(empleado.created_at) }}</td>
                                        <td>{{ dateTime(empleado.created_at) }}</td>
                                        <td>
                                            <button @click="editarEmpleado(empleado)" class="btn btn-default btn-sm"> <i
                                                    class="fas fa-pencil-alt"></i> Editar</button>
                                            <button @click="editEstado(empleado)" class="btn btn-default btn-sm"><i
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
        <div class="modal" id="empleadoModal" tabindex="-1">
            <div class="modal-dialog  modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="areaModalLabel" v-show="!estadoMode"><i
                                class="fa-solid fa-desktop"></i>{{
                                    !editMode ? ' Crear nueva Asignación de activo'
                                    :
                                    ' Editar datos de la Asignación' }}</h5>
                        <h5 class="modal-title" id="areaModalLabel" v-show="estadoMode"><i class="fa-solid fa-desktop"></i>
                            Cambiar Estado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-show="editMode && !estadoMode">
                            <div class="form-group row">
                                <label for="codigo" class="col-sm-2 col-form-label">Código:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control-plaintext text-warning" id="staticEmail"
                                        v-model="asignarActivosData.codigo">
                                </div>
                            </div>

                        </div>
                        <div class="row" v-show="!estadoMode">

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group col-md-12">
                                        <label for="lbname">Funcionario</label>

                                        <el-select v-model="asignarActivosData.empleado_id"
                                            @change="setDatosEmpleado($event)" placeholder="Seleccione un Funcionario"
                                            clearable>
                                            <el-option v-for="item in empleados" :key="item.id" :label="item.razon_social"
                                                :value="item.id">
                                            </el-option>
                                        </el-select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="lbname">Unidad</label>
                                        <input type="text" readonly class="form-control-plaintext text-warning"
                                            id="staticEmail" v-model="this.unidad_dsc">

                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="lbname">Institución</label>
                                        <input type="text" readonly class="form-control-plaintext text-warning"
                                            id="staticEmail" v-model="this.institucion_dsc">

                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">Listar de Activos</h3>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <template v-if="activosTecnologicos.length">
                                                <div class="scrollTable">
                                                    <table class="table table-hover table-head-fixed text-nowrap projects">
                                                        <thead>
                                                            <tr>
                                                                <th>Acción</th>
                                                                <th>Codigo</th>
                                                                <th>nombre</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(item, index) in activosTecnologicos" :key="index"
                                                                @click.prevent="marcarFila(index)">
                                                                <td>
                                                                    <!-- Ira el Checkbox para seleccionar los permisos que se le asignaran al rol -->
                                                                    <el-checkbox v-model="item.checked"></el-checkbox>
                                                                </td>
                                                                <td v-text="item.codigo"></td>
                                                                <td v-text="item.nombre"></td>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col-md-12">
                                        <label for="lbname">Descripción</label>
                                        <textarea rows="5" type="text" class="form-control" id="description"
                                            name="description" v-model="asignarActivosData.descripcion"
                                            placeholder="Descripción"></textarea>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group col-md-12">
                                        <label for="lbname">Observación</label>
                                        <textarea rows="5" type="text" class="form-control" id="observation"
                                            name="observation" v-model="asignarActivosData.observacion"
                                            placeholder="Observación"></textarea>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <h5 class="text-center" v-show="estadoMode">Esta seguro de cambiar el estado del Municipio {{
                        this.asignarActivosData.estado == 'A' ? ' a Inactivo' : 'a Activo' }} ?</h5>
                    <div class="modal-footer" v-show="!estadoMode">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                                class="fa fa-times"></i></button>
                        <button type="button" @click="!editMode ? registrarAsignacionActivos() : updateEmpleado()"
                            class="btn btn-primary">{{
                                !editMode ? 'Asignar ' : 'Guardar Cambios ' }}<i class="fa fa-check"></i></button>
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
            value: null,
            options: ['list', 'of', 'options'],

            editMode: false,
            estadoMode: false,
            empleados: {},
            unidad_dsc: '',
            institucion_dsc: '',

            activosTecnologicos: {},

            asignarActivosData: {
                id: '',
                empleado_id: '',
                observacion: '',
                descripcion: '',
                estado: '',


            },
            asignarActivosErrors: {
                empleado_id: false,
                observacion: false,
                descripcion: false,
                estado: false,
            },
            form: new FormData,
            listActivosFilter: [],
        }
    },
    mounted() {
        this.getEmpleados();

    },
    methods: {

        getEmpleados() {

            axios.get('/procesos/empleado/getListarEmpleados').then(response => {
                this.empleados = response.data
                this.table();
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();

                }
            });

        },
        getActivos() {
            axios.get('/procesos/activos_tecnologicos/activosTecnologicosActivos').then(response => {
                this.activosTecnologicos = response.data
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

                $('#tableMunicipio').DataTable({
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

        setDatosEmpleado(empleado) {
            axios.get('/procesos/empleado/getEmpleado/' + empleado).then(response => {

                this.unidad_dsc = response.data.unidad_dsc;
                this.institucion_dsc = response.data.institucion_dsc;
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();
                }
            });
        },

        marcarFila(index) {
            this.listActivosFilter[index].checked = !this.listActivosFilter[index].checked;
        },
        openModalAsignar() {
            this.estadoMode = false
            this.editMode = false
            this.asignarActivosData = {
                id: '',
                empleado_id: '',
                observacion: '',
                descripcion: '',
                estado: '',
            }
            this.asignarActivosErrors = {
                empleado_id: false,
                observacion: false,
                descripcion: false,
                estado: false,
            }
            this.getActivos();
            $('#empleadoModal').modal('show')
        },

        registrarAsignacionActivos() {
            this.asignarActivosData.empleado_id == '' ? this.asignarActivosErrors.empleado_id = true : this.asignarActivosErrors.empleado_id = false
            this.asignarActivosData.descripcion == '' ? this.asignarActivosErrors.descripcion = true : this.asignarActivosErrors.descripcion = false
            this.asignarActivosData.observacion == '' ? this.asignarActivosErrors.observacion = true : this.asignarActivosErrors.observacion = false

            if (this.asignarActivosData.empleado_id && this.asignarActivosData.descripcion && this.asignarActivosData.observacion
            ) {

                this.$router.push('/rol', null, { shallow: true })
                var url = '/procesos/activos_tecnologicos/setRegistrarActivosAsignados'
                axios.post(url, {
                    'empleado_id': this.asignarActivosData.empleado_id,
                    'descripcion': this.asignarActivosData.descripcion,
                    'observacion': this.asignarActivosData.observacion,
                    'listActivosAsignadosFilter': this.listPermisosFilter
                }).then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Se asigno el activos correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })

                }).catch(error => {
                    if (error.response.status == 401) {
                        this.$router.push({ name: 'login' })
                        // location.reload();
                        sessionStorage.clear();
                    }
                }).finally(() => {
                    this.getRoles();
                    $('#modalCrearRol').modal('hide')
                });
            }

        },
        editarEmpleado(empleado) {
            this.estadoMode = false
            this.editMode = true
            this.asignarActivosData = {
                id: empleado.id,
                codigo: empleado.codigo,
                primer_nombre: empleado.primer_nombre,
                segundo_nombre: empleado.segundo_nombre,
                apellido_paterno: empleado.apellido_paterno,
                apellido_materno: empleado.apellido_materno,
                fecha_nacimiento: empleado.fecha_nacimiento,
                tarjeta_identificacion: empleado.tarjeta_identificacion,
                ci: empleado.ci,
                direccion: empleado.direccion,
                telefono: empleado.telefono,
                estado: empleado.estado,
                unidad_id: empleado.unidad_id,
                tipo_empleado_id: empleado.tipo_empleado_id,
                institucion_id: empleado.institucion_id,
                area_id: empleado.area_id,
                municipio_id: empleado.municipio_id,
                fotografia: ''
            }
            this.getMunicipio();
            this.asignarActivosErrors = {
                primer_nombre: false,
                apellido_paterno: false,
                apellido_materno: false,
                fecha_nacimiento: false,
                tarjeta_identificacion: false,
                ci: false,
                direccion: false,
                telefono: false,
                estado: false,

                fotografia: false
            }

            $('#empleadoModal').modal('show')
        },
        updateEmpleado() {

            if (!this.asignarActivosData.fotografia || this.asignarActivosData.fotografia == undefined) {
                this.setGuardarEmpleado(0);
            } else {
                this.registrarArchivo();
            }

        },
        setRegistrarArchivo() {
            this.form.append('file', this.asignarActivosData.fotografia)
            const config = { headers: { 'Content-Type': 'multipart/form-data' } }
            var url = '/archivo/registrarArchivoEmpleado'
            axios.post(url, this.form, config).then(response => {
                var nIdFile = response.data;
                this.setGuardarEmpleado(nIdFile);
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    //location.reload();
                    sessionStorage.clear();

                }
            })
        },
        setGuardarEmpleado(nIdFile) {
            this.asignarActivosData.primer_nombre == '' ? this.asignarActivosErrors.primer_nombre = true : this.asignarActivosErrors.primer_nombre = false
            // this.asignarActivosData.segundo_nombre == '' ? this.asignarActivosErrors.segundo_nombre = true : this.asignarActivosErrors.segundo_nombre = false
            this.asignarActivosData.apellido_paterno == '' ? this.asignarActivosErrors.apellido_paterno = true : this.asignarActivosErrors.apellido_paterno = false
            this.asignarActivosData.apellido_materno == '' ? this.asignarActivosErrors.apellido_materno = true : this.asignarActivosErrors.apellido_materno = false
            this.asignarActivosData.fecha_nacimiento == '' ? this.asignarActivosErrors.fecha_nacimiento = true : this.asignarActivosErrors.fecha_nacimiento = false
            this.asignarActivosData.tarjeta_identificacion == '' ? this.asignarActivosErrors.tarjeta_identificacion = true : this.asignarActivosErrors.tarjeta_identificacion = false
            this.asignarActivosData.ci == '' ? this.asignarActivosErrors.ci = true : this.asignarActivosErrors.ci = false
            this.asignarActivosData.direccion == '' ? this.asignarActivosErrors.direccion = true : this.asignarActivosErrors.direccion = false
            this.asignarActivosData.telefono == '' ? this.asignarActivosErrors.telefono = true : this.asignarActivosErrors.telefono = false
            this.asignarActivosData.tipo_empleado_id == '' ? this.asignarActivosErrors.tipo_empleado_id = true : this.asignarActivosErrors.tipo_empleado_id = false
            this.asignarActivosData.unidad_id == '' ? this.asignarActivosErrors.unidad_id = true : this.asignarActivosErrors.unidad_id = false
            this.asignarActivosData.institucion_id == '' ? this.asignarActivosErrors.institucion_id = true : this.asignarActivosErrors.institucion_id = false
            //this.asignarActivosData.fotografia == '' ? this.asignarActivosErrors.fotografia = true : this.asignarActivosErrors.fotografia = false
            this.asignarActivosData.area_id == '' ? this.asignarActivosErrors.area_id = true : this.asignarActivosErrors.area_id = false
            this.asignarActivosData.municipio_id == '' ? this.asignarActivosErrors.municipio_id = true : this.asignarActivosErrors.municipio_id = false

            if (this.asignarActivosData.primer_nombre && this.asignarActivosData.apellido_paterno && this.asignarActivosData.apellido_materno
                && this.asignarActivosData.fecha_nacimiento && this.asignarActivosData.tarjeta_identificacion && this.asignarActivosData.ci
                && this.asignarActivosData.direccion && this.asignarActivosData.telefono && this.asignarActivosData.tipo_empleado_id
                && this.asignarActivosData.unidad_id && this.asignarActivosData.institucion_id && this.asignarActivosData.area_id
                && this.asignarActivosData.municipio_id) {
                this.asignarActivosData.fotografia = nIdFile;
                axios.post('/procesos/empleado/update/' + this.asignarActivosData.id, this.asignarActivosData).then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Se actualizo el registro del Empleado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getEmpleados()
                }).catch(error => {

                    if (error.response.status == 401) {
                        this.$router.push({ name: 'login' })
                        // location.reload();
                        sessionStorage.clear();
                    }
                }).finally(() => {
                    $('#empleadoModal').modal('hide')
                });
            }
        },
        editEstado(empleado) {
            this.estadoMode = true
            this.asignarActivosData.id = empleado.id
            this.asignarActivosData.estado = empleado.estado
            $('#empleadoModal').modal('show')
        },
        cambiarEstado() {
            axios.post('/procesos/empleado/cambiarEstado/' + this.asignarActivosData.id).then(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Se actualizo el estado correctamente',
                    showConfirmButton: false,
                    timer: 1500
                })
                this.getEmpleados()
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();
                }
            }).finally(() => {
                $('#empleadoModal').modal('hide')
            });
        },

    },



}
</script>



<style lang="css"></style>