<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Municipio</h1>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">

            <button @click="crearMunicipio" class="btn btn-success btn-sm float-end">
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
              <table class="table table-hover table-head-fixed text-nowrap projects" id="tableMunicipio">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Área</th>
                    <th>Estado</th>
                    <th>Fecha Alta</th>
                    <th>Fecha Modificación</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(municipio, index) in municipios" :key="index">
                    <td>{{ municipio.id }}</td>
                    <td>{{ municipio.codigo }}</td>
                    <td>{{ municipio.descripcion.length <= 20 ? municipio.descripcion : municipio.descripcion.substr(0,
                      20) + '...' }} </td>
                    <td>{{ municipio.area_descripcion.length <= 20 ? municipio.area_descripcion :
                      municipio.area_descripcion.substr(0, 20) + '...' }} </td>
                    <td> {{ municipio.estado == 'A' ? 'Activo' : 'Inactivo' }}</td>
                    <td>{{ dateTime(municipio.created_at) }}</td>
                    <td>{{ dateTime(municipio.created_at) }}</td>
                    <td>
                      <button @click="editarMunicipio(municipio)" class="btn btn-default btn-sm"> <i
                          class="fas fa-pencil-alt"></i> Editar</button>
                      <button @click="removerMunicipio(municipio)" class="btn btn-default btn-sm"><i
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
    <div class="modal" id="municipioModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="areaModalLabel" v-show="!deleteMode"><i class="fa-solid fa-location-pin"></i>{{
              !editMode ? ' Crear nuevo Municipio'
              :
              ' Editar Municipio' }}</h5>
            <h5 class="modal-title" id="areaModalLabel" v-show="deleteMode"><i class="fa-solid fa-location-pin"></i>
              Cambiar Estado</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row" v-show="editMode && !deleteMode">
              <div class="form-group row">
                <label for="codigo" class="col-sm-2 col-form-label">Código:</label>
                <div class="col-sm-10">
                  <input type="text" readonly class="form-control-plaintext text-warning" id="staticEmail"
                    v-model="municipioData.codigo">
                </div>
              </div>

            </div>
            <div class="row" v-show="!deleteMode">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="descripcion">Área:</label>
                  <select class="browser-default custom-select" v-model="municipioData.area_id">
                    <option v-for="area in areas" :value="area.id">{{ area.descripcion }}</option>
                  </select>
                  <span class="text-danger" v-show="municipioErrors.descripcion">El Área es requerida.</span>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label for="descripcion">Descripción:</label>
                  <textarea class="form-control text-uppercase" rows="3" v-model="municipioData.descripcion"></textarea>
                  <span class="text-danger" v-show="municipioErrors.descripcion">El campo Descripción es
                    requerida.</span>
                </div>
              </div>
            </div>
          </div>
          <h5 class="text-center" v-show="deleteMode">Esta seguro de cambiar el estado del Municipio {{
            this.municipioData.estado == 'A' ? ' a Inactivo' : 'a Activo' }} ?</h5>
          <div class="modal-footer" v-show="!deleteMode">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                class="fa fa-times"></i></button>
            <button type="button" @click="!editMode ? storeMunicipio() : updateMunicipio()" class="btn btn-primary">{{
              !editMode ? 'Crear Municipio ' : 'Guardar Cambios ' }}<i class="fa fa-check"></i></button>
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
      municipios: {},
      areas: {},
      municipioData: {
        id: '',
        codigo: '',
        descripcion: '',
        estado: '',
        area_id: '',

      },
      municipioErrors: {
        descripcion: false,
        area_id: false,
      },
    }
  },
  mounted() {

    this.getMunicipios();
    this.getAreas();
    //this.table();
  },
  methods: {

    getMunicipios() {

      axios.get('/parametros/municipio/getListarMunicipios').then(response => {
        this.municipios = response.data
        this.table();
      }).catch(errors => {
        console.log(errors)
      });

    },
    getAreas() {
      axios.get('/parametros/area/getListarAreas').then(response => {
        this.areas = response.data
      }).catch(errors => {
        console.log(errors)
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
    cambiarEstado() {
      axios.post('/parametros/municipio/cambiarEstado/' + this.municipioData.id).then(response => {
        Swal.fire({
          icon: 'success',
          title: 'Se actualizo el estado correctamente',
          showConfirmButton: false,
          timer: 1500
        })
        this.getMunicipios()
      }).catch(errors => {
        console.log(errors)
      }).finally(() => {
        $('#municipioModal').modal('hide')
      });
    },
    removerMunicipio(municipio) {
      this.deleteMode = true
      this.municipioData.id = municipio.id
      this.municipioData.estado = municipio.estado
      this.municipioData.area_id = municipio.area_id
      $('#municipioModal').modal('show')
    },
    updateMunicipio() {

      this.municipioData.descripcion == '' ? this.municipioErrors.descripcion = true : this.municipioErrors.descripcion = false
      this.municipioData.area_id == '' ? this.municipioErrors.area_id = true : this.municipioErrors.area_id = false

      if (this.municipioData.descripcion && this.municipioData.area_id ) {
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
      this.deleteMode = false
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
    crearMunicipio() {
      this.deleteMode = false
      this.editMode = false
      this.municipioData = {
        id: "",
        codigo: "",
        descripcion: "",
        estado: "",
        area_id: "",
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
    },
  },


}
</script>