<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Empleados</h1>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="content container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">

                        <button @click="crearEmpleado" class="btn btn-success btn-sm float-end">
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
                                class="fa-solid fa-helmet-safety"></i>{{
                                    !editMode ? ' Crear nuevo Registro'
                                    :
                                    ' Editar datos de Empleado' }}</h5>
                        <h5 class="modal-title" id="areaModalLabel" v-show="estadoMode"><i
                                class="fa-solid fa-location-pin"></i>
                            Cambiar Estado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-show="editMode && !estadoMode">
                            <div class="form-group row">
                                <label for="codigo" class="col-sm-2 col-form-label">Código:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control-plaintext text-warning" id="staticEmail"
                                        v-model="empleadoData.codigo">
                                </div>
                            </div>

                        </div>
                        <div class="row" v-show="!estadoMode">
                            <div class="form-group col-md-4">
                                <label for="lbname">Primer Nombre</label>
                                <input type="text" class="form-control text-uppercase" id="first_name"
                                    v-model="empleadoData.primer_nombre">
                                <span class="text-danger" v-show="empleadoErrors.primer_nombre">El primer nombre es
                                    requerido.</span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lbname">Segundo Nombre</label>
                                <input type="text" class="form-control text-uppercase" id="second_name"
                                    v-model="empleadoData.segundo_nombre">
                                <span class="text-danger" v-show="empleadoErrors.segundo_nombre">El segundo nombre es
                                    requerido.</span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lbname">Apellido Paterno</label>
                                <input type="text" class="form-control text-uppercase" id="last_name" name="last_name"
                                    v-model="empleadoData.apellido_paterno">
                                <span class="text-danger" v-show="empleadoErrors.apellido_paterno">El apellido paterno es
                                    requerido.</span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lbname">Apellido Materno</label>
                                <input type="text" class="form-control text-uppercase" id="mother_last_name"
                                    name="mother_last_name" v-model="empleadoData.apellido_materno">
                                <span class="text-danger" v-show="empleadoErrors.apellido_materno">El apellido materno es
                                    requerido.</span>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="lbname">CI</label>
                                <input type="text" class="form-control text-uppercase" id="identity_card"
                                    v-model="empleadoData.ci">
                                <span class="text-danger" v-show="empleadoErrors.ci">La cedula de Identidad es
                                    requerido.</span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lbname">Fecha Nacimiento</label>
                                <el-date-picker v-model="empleadoData.fecha_nacimiento" type="date"
                                    placeholder="Seleccione una fecha" size="default" />
                                <span class="text-danger" v-show="empleadoErrors.fecha_nacimiento">La fecha de nacimiento es
                                    requerido.</span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lbname">Tarjeta Identificación</label>
                                <input type="text" class="form-control text-uppercase" id="first_name"
                                    v-model="empleadoData.tarjeta_identificacion">
                                <span class="text-danger" v-show="empleadoErrors.tarjeta_identificacion">La Nro de Tarjeta
                                    de Identificación es
                                    requerido.</span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lbname">Teléfono</label>
                                <input type="text" class="form-control text-uppercase" id="second_name"
                                    v-model="empleadoData.telefono">
                                <span class="text-danger" v-show="empleadoErrors.telefono">La Nro de Teléfono es
                                    requerido.</span>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="lbname">Dirección</label>
                                <input type="text" class="form-control text-uppercase" id="address" name="address"
                                    v-model="empleadoData.direccion">
                                <span class="text-danger" v-show="empleadoErrors.direccion">La Dirección es
                                    requerido.</span>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Fotografia</label>
                                <div class="col-md-9">
                                    <input type="file" class="form-control" @change="getFile">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descripcion">Tipo Empleado:</label>
                                    <select class="browser-default custom-select" v-model="empleadoData.tipo_empleado_id">
                                        <option v-for="tipoEmpleado in tiposEmpleados" :value="tipoEmpleado.id">{{
                                            tipoEmpleado.descripcion }}</option>
                                    </select>
                                    <span class="text-danger" v-show="empleadoErrors.tipo_empleado_id">El Campo Tipo de
                                        Empleado es requerido.</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descripcion">Área:</label>
                                    <select class="browser-default custom-select" v-model="empleadoData.area_id"
                                        @change="getMunicipio()">
                                        <option :selected="true">Choose Province</option>
                                        <option v-for="area in areas" :value="area.id">{{ area.descripcion }}</option>
                                    </select>
                                    <span class="text-danger" v-show="empleadoErrors.area_id">El Área es
                                        requerida.</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descripcion">Municipio:</label>
                                    <select class="browser-default custom-select" v-model="empleadoData.municipio_id">
                                        <option v-for="municipio in municipios" :value="municipio.id">{{
                                            municipio.descripcion }}</option>
                                    </select>
                                    <span class="text-danger" v-show="empleadoErrors.municipio_id">El Municipio es
                                        requerido.</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descripcion">Institución:</label>
                                    <select class="browser-default custom-select" v-model="empleadoData.institucion_id">
                                        <option v-for="institucion in instituciones" :value="institucion.id">{{
                                            institucion.descripcion }}</option>
                                    </select>
                                    <span class="text-danger" v-show="empleadoErrors.institucion_id">El campo Institución es
                                        requerido.</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descripcion">Unidad:</label>
                                    <select class="browser-default custom-select" v-model="empleadoData.unidad_id">
                                        <option v-for="unidad in unidades" :value="unidad.id">{{ unidad.descripcion }}
                                        </option>
                                    </select>
                                    <span class="text-danger" v-show="empleadoErrors.unidad_id">El campo Unidad es
                                        requerido.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="text-center" v-show="estadoMode">Esta seguro de cambiar el estado del Municipio {{
                        this.empleadoData.estado == 'A' ? ' a Inactivo' : 'a Activo' }} ?</h5>
                    <div class="modal-footer" v-show="!estadoMode">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                                class="fa fa-times"></i></button>
                        <button type="button" @click="!editMode ? registrarEmpleado() : updateEmpleado()"
                            class="btn btn-primary">{{
                                !editMode ? 'Registrar ' : 'Guardar Cambios ' }}<i class="fa fa-check"></i></button>
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
            empleados: {},
            tiposEmpleados: {},
            unidades: {},
            instituciones: {},
            areas: {},
            municipios: {},
            empleadoData: {
                id: '',
                codigo: '',
                primer_nombre: '',
                segundo_nombre: '',
                apellido_paterno: '',
                apellido_materno: '',
                fecha_nacimiento: '',
                tarjeta_identificacion: '',
                ci: '',
                direccion: '',
                telefono: '',
                estado: '',
                tipo_empleado_id: '',
                unidad_id: '',
                institucion_id: '',
                municipio_id: '',
                fotografia: '',

            },
            empleadoErrors: {
                primer_nombre: false,
                apellido_paterno: false,
                apellido_materno: false,
                fecha_nacimiento: false,
                tarjeta_identificacion: false,
                ci: false,
                direccion: false,
                telefono: false,
                estado: false,
                tipo_empleado_id: false,
                unidad_id: false,
                institucion_id: false,
                municipio_id: false,
                fotografia: false,
            },
            form: new FormData,
        }
    },
    mounted() {
        this.getEmpleados();
        this.getTipoEmpleados();
        this.getAreas();
        this.getInstituciones();
        this.getUnidades();


        //this.table();
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
        getTipoEmpleados() {

            axios.get('/parametros/tipoEmpleado/getListarTipoEmpleados').then(response => {
                this.tiposEmpleados = response.data
                this.table();
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();

                }
            });

        },
        getAreas() {
            axios.get('/parametros/area/getListarAreas').then(response => {
                this.areas = response.data
                this.table();
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();

                }
            });

        },
        getMunicipio() {

            axios.get('/parametros/municipio/getListarMunicipiosPorArea/' + this.empleadoData.area_id).then(response => {
                this.municipios = response.data
                this.table();
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();

                }
            });

        },
        getUnidades() {

            axios.get('/parametros/undiad/getListarUnidades').then(response => {
                this.unidades = response.data
                this.table();
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();

                }
            });

        },
        getInstituciones() {

            axios.get('/parametros/institucion/getListarInstituciones').then(response => {
                this.instituciones = response.data
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

        getFile(e) {
            this.empleadoData.fotografia = e.target.files[0];
        },
        crearEmpleado() {
            this.estadoMode = false
            this.editMode = false
            this.empleadoData = {
                id: '',
                codigo: '',
                primer_nombre: '',
                segundo_nombre: '',
                apellido_paterno: '',
                apellido_materno: '',
                fecha_nacimiento: '',
                tarjeta_identificacion: '',
                ci: '',
                direccion: '',
                telefono: '',
                estado: '',

                fotografia: ''
            }
            this.empleadoErrors = {
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
        registrarEmpleado() {
            this.empleadoData.fotografia == '' || this.empleadoData.fotografia == undefined ? this.empleadoErrors.fotografia = true : this.empleadoErrors.fotografia = false
            if (!this.empleadoData.fotografia) {
                this.guardarEmpleado(0);
            } else {
                this.registrarArchivo();
            }
        },
        registrarArchivo() {
            this.form.append('file', this.empleadoData.fotografia)
            const config = { headers: { 'Content-Type': 'multipart/form-data' } }
            var url = '/archivo/registrarArchivoEmpleado'
            axios.post(url, this.form, config).then(response => {
                var nIdFile = response.data;
                this.guardarEmpleado(nIdFile);
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();

                }
            })
        },
        guardarEmpleado(nIdFile) {
            this.empleadoData.primer_nombre == '' ? this.empleadoErrors.primer_nombre = true : this.empleadoErrors.primer_nombre = false
            // this.empleadoData.segundo_nombre == '' ? this.empleadoErrors.segundo_nombre = true : this.empleadoErrors.segundo_nombre = false
            this.empleadoData.apellido_paterno == '' ? this.empleadoErrors.apellido_paterno = true : this.empleadoErrors.apellido_paterno = false
            this.empleadoData.apellido_materno == '' ? this.empleadoErrors.apellido_materno = true : this.empleadoErrors.apellido_materno = false
            this.empleadoData.fecha_nacimiento == '' ? this.empleadoErrors.fecha_nacimiento = true : this.empleadoErrors.fecha_nacimiento = false
            this.empleadoData.tarjeta_identificacion == '' ? this.empleadoErrors.tarjeta_identificacion = true : this.empleadoErrors.tarjeta_identificacion = false
            this.empleadoData.ci == '' ? this.empleadoErrors.ci = true : this.empleadoErrors.ci = false
            this.empleadoData.direccion == '' ? this.empleadoErrors.direccion = true : this.empleadoErrors.direccion = false
            this.empleadoData.telefono == '' ? this.empleadoErrors.telefono = true : this.empleadoErrors.telefono = false
            this.empleadoData.tipo_empleado_id == '' ? this.empleadoErrors.tipo_empleado_id = true : this.empleadoErrors.tipo_empleado_id = false
            this.empleadoData.unidad_id == '' ? this.empleadoErrors.unidad_id = true : this.empleadoErrors.unidad_id = false
            this.empleadoData.institucion_id == '' ? this.empleadoErrors.institucion_id = true : this.empleadoErrors.institucion_id = false
            //this.empleadoData.fotografia == '' ? this.empleadoErrors.fotografia = true : this.empleadoErrors.fotografia = false
            this.empleadoData.area_id == '' ? this.empleadoErrors.area_id = true : this.empleadoErrors.area_id = false
            this.empleadoData.municipio_id == '' ? this.empleadoErrors.municipio_id = true : this.empleadoErrors.municipio_id = false

            if (this.empleadoData.primer_nombre && this.empleadoData.apellido_paterno && this.empleadoData.apellido_materno
                && this.empleadoData.fecha_nacimiento && this.empleadoData.tarjeta_identificacion && this.empleadoData.ci
                && this.empleadoData.direccion && this.empleadoData.telefono && this.empleadoData.tipo_empleado_id
                && this.empleadoData.unidad_id && this.empleadoData.institucion_id && this.empleadoData.area_id
                && this.empleadoData.municipio_id) {
                this.empleadoData.fotografia = nIdFile;
                axios.post('/procesos/empleado/crear', this.empleadoData).then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Se grabo el Registro  correctamente',
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
        editarEmpleado(empleado) {
            this.estadoMode = false
            this.editMode = true
            this.empleadoData = {
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
            this.empleadoErrors = {
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
           
            if (!this.empleadoData.fotografia || this.empleadoData.fotografia == undefined) {
                this.setGuardarEmpleado(0);
            } else {
                this.registrarArchivo();
            }

        },
        setRegistrarArchivo() {
            this.form.append('file', this.empleadoData.fotografia)
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
            this.empleadoData.primer_nombre == '' ? this.empleadoErrors.primer_nombre = true : this.empleadoErrors.primer_nombre = false
            // this.empleadoData.segundo_nombre == '' ? this.empleadoErrors.segundo_nombre = true : this.empleadoErrors.segundo_nombre = false
            this.empleadoData.apellido_paterno == '' ? this.empleadoErrors.apellido_paterno = true : this.empleadoErrors.apellido_paterno = false
            this.empleadoData.apellido_materno == '' ? this.empleadoErrors.apellido_materno = true : this.empleadoErrors.apellido_materno = false
            this.empleadoData.fecha_nacimiento == '' ? this.empleadoErrors.fecha_nacimiento = true : this.empleadoErrors.fecha_nacimiento = false
            this.empleadoData.tarjeta_identificacion == '' ? this.empleadoErrors.tarjeta_identificacion = true : this.empleadoErrors.tarjeta_identificacion = false
            this.empleadoData.ci == '' ? this.empleadoErrors.ci = true : this.empleadoErrors.ci = false
            this.empleadoData.direccion == '' ? this.empleadoErrors.direccion = true : this.empleadoErrors.direccion = false
            this.empleadoData.telefono == '' ? this.empleadoErrors.telefono = true : this.empleadoErrors.telefono = false
            this.empleadoData.tipo_empleado_id == '' ? this.empleadoErrors.tipo_empleado_id = true : this.empleadoErrors.tipo_empleado_id = false
            this.empleadoData.unidad_id == '' ? this.empleadoErrors.unidad_id = true : this.empleadoErrors.unidad_id = false
            this.empleadoData.institucion_id == '' ? this.empleadoErrors.institucion_id = true : this.empleadoErrors.institucion_id = false
            //this.empleadoData.fotografia == '' ? this.empleadoErrors.fotografia = true : this.empleadoErrors.fotografia = false
            this.empleadoData.area_id == '' ? this.empleadoErrors.area_id = true : this.empleadoErrors.area_id = false
            this.empleadoData.municipio_id == '' ? this.empleadoErrors.municipio_id = true : this.empleadoErrors.municipio_id = false

            if (this.empleadoData.primer_nombre && this.empleadoData.apellido_paterno && this.empleadoData.apellido_materno
                && this.empleadoData.fecha_nacimiento && this.empleadoData.tarjeta_identificacion && this.empleadoData.ci
                && this.empleadoData.direccion && this.empleadoData.telefono && this.empleadoData.tipo_empleado_id
                && this.empleadoData.unidad_id && this.empleadoData.institucion_id && this.empleadoData.area_id
                && this.empleadoData.municipio_id) {
                this.empleadoData.fotografia = nIdFile;
                axios.post('/procesos/empleado/update/' + this.empleadoData.id, this.empleadoData).then(response => {
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
            this.empleadoData.id = empleado.id
            this.empleadoData.estado = empleado.estado
            $('#empleadoModal').modal('show')
        },
        cambiarEstado() {
            axios.post('/procesos/empleado/cambiarEstado/' + this.empleadoData.id).then(response => {
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
        /*  
        
          updateMunicipio() {
  
              this.municipioData.descripcion == '' ? this.municipioErrors.descripcion = true : this.municipioErrors.descripcion = false
              this.municipioData.area_id == '' ? this.municipioErrors.area_id = true : this.municipioErrors.area_id = false
  
              if (this.municipioData.descripcion && this.municipioData.area_id) {
                  axios.post('/parametros/municipio/update/' + this.municipioData.id, this.municipioData).then(response => {
                      Swal.fire({
                          icon: 'success',
                          title: 'Se actualizo el municipio correctamente',
                          showConfirmButton: false,
                          timer: 1500
                      })
                      this.getMunicipios()
                  }).catch(errors => {
                      console.log(errors)
                  }).finally(() => {
                      $('#municipioModal').modal('hide')
                  });
              }
  
          },
          editarMunicipio(municipio) {
              this.estadoMode = false
              this.editMode = true
              this.municipioData = {
                  id: municipio.id,
                  codigo: municipio.codigo,
                  descripcion: municipio.descripcion,
                  estado: municipio.estado,
                  area_id: municipio.area_id,
              }
              this.municipioErrors = {
                  descripcion: false,
                  area_id: false,
              }
  
              $('#municipioModal').modal('show')
          },
          
          storeMunicipio() {
  
              this.municipioData.descripcion == '' ? this.municipioErrors.descripcion = true : this.municipioErrors.descripcion = false
              this.municipioData.area_id == '' ? this.municipioErrors.area_id = true : this.municipioErrors.area_id = false
  
              if (this.municipioData.descripcion && this.municipioData.area_id) {
                  axios.post('/parametros/municipio/crear', this.municipioData).then(response => {
                      Swal.fire({
                          icon: 'success',
                          title: 'Se grabo el municipio correctamente',
                          showConfirmButton: false,
                          timer: 1500
                      })
                      this.getMunicipios()
                  }).catch(errors => {
  
                      console.log(errors)
                  }).finally(() => {
                      $('#municipioModal').modal('hide')
                  });
              }
          },*/
    },


}
</script>