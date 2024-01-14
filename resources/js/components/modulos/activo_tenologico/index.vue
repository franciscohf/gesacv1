<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Activos Tecnológicos</h1>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="content container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">

                        <button @click="crearActivoTecnologico" class="btn btn-success btn-sm float-end">
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
                            <table class="table table-hover table-head-fixed text-nowrap projects"
                                id="tableActivosTecnologicos">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Tipo Activo</th>
                                        <th>QR</th>
                                        <th>Área</th>
                                        <th>Estado Activo</th>
                                        <th>Características</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(activosTecnologicos, index) in activosTecnologicos" :key="index">
                                        <td>{{ activosTecnologicos.id }}</td>
                                        <td>{{ activosTecnologicos.codigo }}</td>
                                        <td>{{ activosTecnologicos.nombre }}</td>
                                        <td>{{ activosTecnologicos.tipo_activo_dsc }}</td>
                                        <td>{{ activosTecnologicos.qr }}</td>
                                        <td>{{ activosTecnologicos.area_dsc }}</td>
                                        <td>{{ activosTecnologicos.estado_dsc }}</td>
                                        <td>{{ activosTecnologicos.caracteristicas.length <= 20 ?
                                            activosTecnologicos.caracteristicas :
                                            activosTecnologicos.caracteristicas.substr(0, 20) + '...' }} </td>
                                        <td>
                                            <template v-if="activosTecnologicos.estado_codigo_reg != 99">
                                                <span class="badge badge-success"
                                                    v-text="activosTecnologicos.estado_dsc_reg"></span>
                                            </template>
                                            <template v-else>
                                                <span class="badge badge-danger"
                                                    v-text="activosTecnologicos.estado_dsc_reg"></span>
                                            </template>
                                        </td>

                                        <td>
                                            <template v-if="activosTecnologicos.estado_codigo_reg == 1">
                                                <button @click="editarActivotecnologico(activosTecnologicos)"
                                                    class="btn btn-default btn-sm"> <i class="fas fa-pencil-alt"></i>
                                                    Editar</button>
                                                <button @click="editEstado(activosTecnologicos)"
                                                    class="btn btn-default btn-sm"><i
                                                        class="fa-solid fa-circle-chevron-down"></i> Estado</button>
                                            </template>



                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="activoTecnologicoModal" tabindex="-1">
            <div class="modal-dialog  modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="areaModalLabel" v-show="!estadoMode"><i
                                class="nav-icon fa-solid fa-microchip"></i>{{
                                    !editMode ? ' Crear nuevo Activo'
                                    :
                                    ' Editar datos del Activo' }}</h5>
                        <h5 class="modal-title" id="areaModalLabel" v-show="estadoMode"><i
                                class="nav-icon fa-solid fa-microchip"></i>
                            Cambiar Estado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-show="editMode && !estadoMode">
                            <div class="form-group row">
                                <label for="codigo" class="col-sm-2 col-form-label">Código:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control-plaintext text-warning" id="staticEmail"
                                        v-model="activoTecnologicoData.codigo">
                                </div>
                            </div>

                        </div>
                        <div class="row " v-show="!estadoMode">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="lbname">Nombre</label>
                                        <input type="text" class="form-control text-uppercase" id="name" name="name"
                                            v-model="activoTecnologicoData.nombre" placeholder="Nombre">
                                        <span class="text-danger" v-show="activoTecnologicoErrors.nombre">El Nombre es
                                            requerida.</span>

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Categoría</label>
                                        <div>
                                            <el-select v-model="activoTecnologicoData.categoria_id"
                                                @change="getTiposActivos()" placeholder="Seleccione un Tipo Activo"
                                                clearable>
                                                <el-option v-for="item in categorias" :key="item.id"
                                                    :label="item.descripcion" :value="item.id">
                                                </el-option>
                                            </el-select>
                                            <span class="text-danger" v-show="activoTecnologicoErrors.categotia_id">La
                                                Categoría es requerido.</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Tipo Activo</label>
                                        <div>
                                            <el-select v-model="activoTecnologicoData.tipo_activo_id"
                                                placeholder="Seleccione un Tipo Activo" clearable>
                                                <el-option v-for="item in tiposActivos" :key="item.id"
                                                    :label="item.descripcion" :value="item.id">
                                                </el-option>
                                            </el-select>
                                            <span class="text-danger" v-show="activoTecnologicoErrors.tipo_activo_id">El
                                                Tipo de Activo es
                                                requerida.</span>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Tipo Ingreso</label>
                                        <div>
                                            <el-select v-model="activoTecnologicoData.tipo_ingreso_id"
                                                placeholder="Seleccione un Tipo de Ingreso" clearable>
                                                <el-option v-for="item in tiposIngresos" :key="item.id"
                                                    :label="item.descripcion" :value="item.id">
                                                </el-option>
                                            </el-select>
                                            <span class="text-danger" v-show="activoTecnologicoErrors.tipo_ingreso_id">El
                                                Tipo de Ingreso
                                                requerida.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Área</label>
                                        <div>
                                            <el-select v-model="activoTecnologicoData.area_id"
                                                placeholder="Seleccione un Área" clearable>
                                                <el-option v-for="item in areas" :key="item.id" :label="item.descripcion"
                                                    :value="item.id">
                                                </el-option>
                                            </el-select>
                                            <span class="text-danger" v-show="activoTecnologicoErrors.area_id">El Área es
                                                requerida.</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="lbname">N° Serie</label>
                                        <input type="text" class="form-control text-uppercase" id="number_serie"
                                            name="number_serie" v-model="activoTecnologicoData.serie"
                                            placeholder="Nro. de Serie">
                                        <span class="text-danger" v-show="activoTecnologicoErrors.serie">La serie es
                                            requerida.</span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="lbname">Modelo</label>
                                        <input type="text" class="form-control text-uppercase" id="modelo" name="modelo"
                                            v-model="activoTecnologicoData.modelo" placeholder="Modelo">
                                        <span class="text-danger" v-show="activoTecnologicoErrors.modelo">El Modelo es
                                            requerida.</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Marca</label>
                                        <div>
                                            <el-select v-model="activoTecnologicoData.marca_id"
                                                placeholder="Seleccione una Marca" clearable>
                                                <el-option v-for="item in marcas" :key="item.id" :label="item.descripcion"
                                                    :value="item.id">
                                                </el-option>
                                            </el-select>
                                            <span class="text-danger" v-show="activoTecnologicoErrors.marca_id">La Marca es
                                                requerida.</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Estado</label>
                                        <div>
                                            <el-select v-model="activoTecnologicoData.estado_activo_id"
                                                placeholder="Seleccione un Estado" clearable>
                                                <el-option v-for="item in estadosActivos" :key="item.id"
                                                    :label="item.descripcion" :value="item.id">
                                                </el-option>
                                            </el-select>
                                            <span class="text-danger" v-show="activoTecnologicoErrors.estado_activo_id">El
                                                Estado del Activo es
                                                requerida.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-8">
                                        <label for="lbname">Caracteristicas Activos</label>
                                        <textarea rows="7" type="text" class="form-control text-uppercase"
                                            id="characteristc" name="characteristc"
                                            v-model="activoTecnologicoData.caracteristicas"
                                            placeholder="Caracteristica"></textarea>
                                        <span class="text-danger" v-show="activoTecnologicoErrors.caracteristicas">El campo
                                            Caracteristicas
                                            es requerida.</span>

                                    </div>
                                    <div class="form-group col-md-4">

                                        <label for="lbname">Subir Imagen</label>
                                        <input v-on:change="onImageChange" type="file" class="form-control" id="image"
                                            name="image" placeholder="Teléfono/Celular">
                                        <br>
                                        <div align="center">
                                            <img v-bind:src="urlFotografia" style="
                                               height: 8vw;
                                               width: 8vw;
                                               box-shadow: 0 0 5px gray;
                                               display: inline-block;
                                               margin-left: auto;
                                               margin-right: auto;
                                               " />
                                        </div>
                                        <div class="invalid-feedback">{{ 'El Teléfono/Celular es requerido' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="text-center" v-show="estadoMode">Esta seguro de dar de Baja el Activo ?</h5>
                    <div class="modal-footer" v-show="!estadoMode">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                                class="fa fa-times"></i></button>
                        <button type="button" @click="!editMode ? registrarActivotecnologico() : updateActivotecnologico()"
                            class="btn btn-primary">{{
                                !editMode ? 'Registrar ' : 'Guardar Cambios ' }}<i class="fa fa-check"></i></button>
                    </div>
                    <div class="modal-footer" v-show="estadoMode">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                                class="fa fa-times"></i></button>
                        <button type="button" @click="cambiarEstado" class="btn btn-primary">Dar Baja <i
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
            activosTecnologicos: {},
            tiposActivos: {},
            tiposIngresos: {},
            areas: {},
            categorias: {},
            marcas: {},
            estadosActivos: {},

            activoTecnologicoData: {
                id: '',
                codigo: '',
                nombre: '',
                marca_id: '',
                modelo: '',
                serie: '',
                qr: '',
                imagen: '',
                caracteristicas: '',
                estado_activo_id: '',
                tipo_activo_id: '',
                tipo_ingreso_id: '',
                area_id: '',
                categoria_id: '',
                estado_codigo_reg: '',

            },
            activoTecnologicoErrors: {

                codigo: false,
                nombre: false,
                marca_id: false,
                modelo: false,
                serie: false,
                qr: false,
                imagen: false,
                caracteristicas: false,
                estado_activo_id: false,
                tipo_activo_id: false,
                tipo_ingreso_id: false,
                area_id: false,
                categoria_id: false,
                estado_codigo_reg: false,
            },
            form: new FormData,
            urlFotografia: ''

        }
    },
    mounted() {
        this.getActivosTecnologicos();
        this.getTiposIngesos();
        this.getAreas();
        this.getCategorias();
        this.getMarcas();
        this.getEstados();
    },
    methods: {

        getActivosTecnologicos() {

            axios.get('/procesos/activos_tecnologicos/getListarActivosTenologicos').then(response => {
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

        getTiposIngesos() {

            axios.get('/parametros/tipo_ingreso/getListarTiposIngresos').then(response => {
                this.tiposIngresos = response.data

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
        getTiposActivos() {

            axios.get('/parametros/tipos_activos/getListarTiposActivosPorCategoria/' + this.activoTecnologicoData.categoria_id).then(response => {
                this.tiposActivos = response.data

            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();
                }
            });

        },
        getMarcas() {

            axios.get('/parametros/marca/getListarMarcas').then(response => {
                this.marcas = response.data

            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();
                }
            });

        },
        getEstados() {

            axios.get('/parametros/estado_activo/getListarEstadosActivos').then(response => {
                this.estadosActivos = response.data

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

                $('#tableActivosTecnologicos').DataTable({
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

        /* getFile(e) {
             
             this.activoTecnologicoData.imagen = e.target.files[0];
             alert(e.target.files[0])
         },*/
        crearActivoTecnologico() {
            this.estadoMode = false
            this.editMode = false

            this.activoTecnologicoData = {
                id: '',
                codigo: '',
                nombre: '',
                marca_id: '',
                modelo: '',
                serie: '',
                qr: '',
                imagen: '',
                caracteristicas: '',
                estado_activo_id: '',
                tipo_activo_id: '',
                tipo_ingreso_id: '',
                area_id: '',
                categotia_id: '',

            }
            this.activoTecnologicoErrors = {

                codigo: false,
                nombre: false,
                marca_id: false,
                modelo: false,
                serie: false,
                qr: false,
                imagen: false,
                caracteristicas: false,
                estado_activo_id: false,
                tipo_activo_id: false,
                tipo_ingreso_id: false,
                area_id: false,
            }
            this.urlFotografia = '/img/no_imagen.png';
            $('#activoTecnologicoModal').modal('show')
        },
        registrarActivotecnologico() {

            this.activoTecnologicoData.imagen == '' || this.activoTecnologicoData.imagen == undefined ? this.activoTecnologicoErrors.imagen = true : this.activoTecnologicoErrors.imagen = false
            if (!this.activoTecnologicoData.imagen) {
                this.guardarActivoTecnologico(0);
            } else {
                this.registrarArchivo();
            }
        },
        registrarArchivo() {

            this.form.append('file', this.activoTecnologicoData.imagen)
            const config = { headers: { 'Content-Type': 'multipart/form-data' } }
            var url = '/archivo/registrarArchivoActivoTecnologico'
            axios.post(url, this.form, config).then(response => {
                var nIdFile = response.data;
                this.guardarActivoTecnologico(nIdFile);
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();

                }
            })
        },
        guardarActivoTecnologico(nIdFile) {
            this.activoTecnologicoData.nombre == '' ? this.activoTecnologicoErrors.nombre = true : this.activoTecnologicoErrors.nombre = false

            this.activoTecnologicoData.marca_id == '' ? this.activoTecnologicoErrors.marca_id = true : this.activoTecnologicoErrors.marca_id = false
            this.activoTecnologicoData.modelo == '' ? this.activoTecnologicoErrors.modelo = true : this.activoTecnologicoErrors.modelo = false
            this.activoTecnologicoData.serie == '' ? this.activoTecnologicoErrors.serie = true : this.activoTecnologicoErrors.serie = false
            this.activoTecnologicoData.caracteristicas == '' ? this.activoTecnologicoErrors.caracteristicas = true : this.activoTecnologicoErrors.caracteristicas = false
            this.activoTecnologicoData.estado_activo_id == '' ? this.activoTecnologicoErrors.estado_activo_id = true : this.activoTecnologicoErrors.estado_activo_id = false
            this.activoTecnologicoData.tipo_activo_id == '' ? this.activoTecnologicoErrors.tipo_activo_id = true : this.activoTecnologicoErrors.tipo_activo_id = false
            this.activoTecnologicoData.tipo_ingreso_id == '' ? this.activoTecnologicoErrors.tipo_ingreso_id = true : this.activoTecnologicoErrors.tipo_ingreso_id = false
            this.activoTecnologicoData.area_id == '' ? this.activoTecnologicoErrors.area_id = true : this.activoTecnologicoErrors.area_id = false
            this.activoTecnologicoData.categoria_id == '' ? this.activoTecnologicoErrors.categoria_id = true : this.activoTecnologicoErrors.categoria_id = false



            if (this.activoTecnologicoData.nombre && this.activoTecnologicoData.modelo && this.activoTecnologicoData.serie
                && this.activoTecnologicoData.caracteristicas && this.activoTecnologicoData.estado_activo_id && this.activoTecnologicoData.tipo_activo_id
                && this.activoTecnologicoData.tipo_ingreso_id && this.activoTecnologicoData.tipo_ingreso_id && this.activoTecnologicoData.area_id
                && this.activoTecnologicoData.categoria_id
            ) {
                this.activoTecnologicoData.imagen = nIdFile;
                axios.post('/procesos/activos_tecnologicos/crear', this.activoTecnologicoData).then(response => {
                    if (response.data.success == 'true') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Se grabo el Registro  correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.getActivosTecnologicos()

                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "ERROR",
                            text: "Existe un problema con el registro comuníquese con sistemas.",
                            //footer: '<a href="#">Why do I have this issue?</a>'
                        });
                    }

                }).catch(error => {
                    alert(error)
                    if (error.response.status == 401) {
                        this.$router.push({ name: 'login' })
                        // location.reload();
                        sessionStorage.clear();
                    }
                }).finally(() => {
                    $('#activoTecnologicoModal').modal('hide')
                });
            }

        },
        editarActivotecnologico(activoTecnologico) {
            this.estadoMode = false
            this.editMode = true
            this.activoTecnologicoData = {
                id: activoTecnologico.id,
                codigo: activoTecnologico.codigo,
                nombre: activoTecnologico.nombre,
                marca_id: activoTecnologico.marca_id,
                modelo: activoTecnologico.modelo,
                serie: activoTecnologico.serie,
                qr: activoTecnologico.qr,
                imagen: activoTecnologico.imagen,
                caracteristicas: activoTecnologico.caracteristicas,
                estado_activo_id: activoTecnologico.estado_activo_id,
                tipo_activo_id: activoTecnologico.tipo_activo_id,
                tipo_ingreso_id: activoTecnologico.tipo_ingreso_id,
                area_id: activoTecnologico.area_id,
                categoria_id: activoTecnologico.categoria_id,

            }
            this.urlFotografia = activoTecnologico.imagen;
            if (activoTecnologico.imagen == null) {
                this.urlFotografia = '/img/no_imagen.png';
            }

            this.getTiposActivos();

            this.activoTecnologicoErrors = {

                codigo: false,
                nombre: false,
                marca_id: false,
                modelo: false,
                serie: false,
                qr: false,
                imagen: false,
                caracteristicas: false,
                estado_activo_id: false,
                tipo_activo_id: false,
                tipo_ingreso_id: false,
                area_id: false,
                categoria_id: false,
            }

            $('#activoTecnologicoModal').modal('show')
        },
        updateActivotecnologico() {

            if (!this.activoTecnologicoData.imagen || this.activoTecnologicoData.imagen == undefined) {
                this.setGuardarActivoTecnologico(0);
            } else {
                this.setRegistrarArchivo();
            }

        },
        setRegistrarArchivo() {
            this.form.append('file', this.activoTecnologicoData.imagen)
            const config = { headers: { 'Content-Type': 'multipart/form-data' } }
            var url = '/archivo/registrarArchivoActivoTecnologico'
            axios.post(url, this.form, config).then(response => {
                var nIdFile = response.data;
                this.setGuardarActivoTecnologico(nIdFile);
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    //location.reload();
                    sessionStorage.clear();

                }
            })
        },
        setGuardarActivoTecnologico(nIdFile) {
            this.activoTecnologicoData.nombre == '' ? this.activoTecnologicoErrors.nombre = true : this.activoTecnologicoErrors.nombre = false

            this.activoTecnologicoData.marca_id == '' ? this.activoTecnologicoErrors.marca_id = true : this.activoTecnologicoErrors.marca_id = false
            this.activoTecnologicoData.modelo == '' ? this.activoTecnologicoErrors.modelo = true : this.activoTecnologicoErrors.modelo = false
            this.activoTecnologicoData.serie == '' ? this.activoTecnologicoErrors.serie = true : this.activoTecnologicoErrors.serie = false
            this.activoTecnologicoData.caracteristicas == '' ? this.activoTecnologicoErrors.caracteristicas = true : this.activoTecnologicoErrors.caracteristicas = false
            this.activoTecnologicoData.estado_activo_id == '' ? this.activoTecnologicoErrors.estado_activo_id = true : this.activoTecnologicoErrors.estado_activo_id = false
            this.activoTecnologicoData.tipo_activo_id == '' ? this.activoTecnologicoErrors.tipo_activo_id = true : this.activoTecnologicoErrors.tipo_activo_id = false
            this.activoTecnologicoData.tipo_ingreso_id == '' ? this.activoTecnologicoErrors.tipo_ingreso_id = true : this.activoTecnologicoErrors.tipo_ingreso_id = false
            this.activoTecnologicoData.area_id == '' ? this.activoTecnologicoErrors.area_id = true : this.activoTecnologicoErrors.area_id = false
            this.activoTecnologicoData.categoria_id == '' ? this.activoTecnologicoErrors.categoria_id = true : this.activoTecnologicoErrors.categoria_id = false



            if (this.activoTecnologicoData.nombre && this.activoTecnologicoData.modelo && this.activoTecnologicoData.serie
                && this.activoTecnologicoData.caracteristicas && this.activoTecnologicoData.estado_activo_id && this.activoTecnologicoData.tipo_activo_id
                && this.activoTecnologicoData.tipo_ingreso_id && this.activoTecnologicoData.tipo_ingreso_id && this.activoTecnologicoData.area_id
                && this.activoTecnologicoData.categoria_id) {
                this.activoTecnologicoData.imagen = nIdFile;
                axios.post('/procesos/activos_tecnologicos/update/' + this.activoTecnologicoData.id, this.activoTecnologicoData).then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Se actualizo el registro del Activo correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getActivosTecnologicos()
                }).catch(error => {

                    if (error.response.status == 401) {
                        this.$router.push({ name: 'login' })
                        // location.reload();
                        sessionStorage.clear();
                    }
                }).finally(() => {
                    $('#activoTecnologicoModal').modal('hide')
                });
            }
        },
        editEstado(activoTecnologico) {
            this.estadoMode = true
            this.editMode = false
            this.activoTecnologicoData.id = activoTecnologico.id
            this.activoTecnologicoData.estado_codigo_reg = activoTecnologico.estado_codigo_reg
            $('#activoTecnologicoModal').modal('show')
        },
        cambiarEstado() {
            axios.post('/procesos/activos_tecnologicos/cambiarEstado/' + this.activoTecnologicoData.id).then(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Se realizó  la baja del activo',
                    showConfirmButton: false,
                    timer: 1500
                })
                this.getActivosTecnologicos()
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$router.push({ name: 'login' })
                    // location.reload();
                    sessionStorage.clear();
                }
            }).finally(() => {
                $('#activoTecnologicoModal').modal('hide')
            });
        },
        onImageChange(e) {
            // console.log('....', e);
            this.image = e.target.files[0];
            this.activoTecnologicoData.imagen = e.target.files[0]
            // console.log(this.image);
            let file = e.target.files[0];
            this.cargarImagen(file);
            this.has_image = true;
        },

        cargarImagen(file) {
            let reader = new FileReader();
            reader.onload = (e) => {
                this.urlFotografia = e.target.result;

            }
            reader.readAsDataURL(file);
        },
    },


}
</script>