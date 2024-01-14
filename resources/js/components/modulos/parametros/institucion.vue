<template>
  <div>
      <div class="content-header">
          <div class="container-fluid">
              <div class="row mb-2">
                  <div class="col-sm-6">
                      <h1 class="m-0 text-dark">Instituciones</h1>
                  </div>
              </div><!-- /.row -->
          </div><!-- /.container-fluid -->
      </div>
      <div class="content container-fluid">
          <div class="card">
              <div class="card-header">
                  <div class="card-tools">

                      <button @click="crearInstitucion" class="btn btn-success btn-sm float-end">
                          <i class="fas fa-plus-square"></i> Nuevo Institución
                      </button>
                  </div>
              </div>
              <div class="card-body">
                  <div class="card card-info">
                      <div class="card-header">
                          <h3 class="card-title">Bandeja de Resultados</h3>
                      </div>
                      <div class="card-body table-responsive">
                          <table class="table table-hover table-head-fixed text-nowrap projects" id="tableInstitucion">
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
                                  <tr v-for="(institucion, index) in instituciones" :key="index">
                                      <td>{{ institucion.id }}</td>
                                      <td>{{ institucion.codigo }}</td>
                                      <td>{{ institucion.descripcion.length <= 20 ? institucion.descripcion :
                                          institucion.descripcion.substr(0, 20) + '...' }} </td>
                                      <td> {{ institucion.estado == 'A' ? 'Activo' : 'Inactivo' }}</td>
                                      <td>{{ dateTime(institucion.created_at) }}</td>
                                      <td>{{ dateTime(institucion.created_at) }}</td>
                                      <td>
                                          <button @click="editarInstitucion(institucion)" class="btn btn-default btn-sm"> <i
                                                  class="fas fa-pencil-alt"></i> Editar</button>
                                          <button @click="removerInstitucion(institucion)" class="btn btn-default btn-sm"><i
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
      <div class="modal" id="institucionModal" tabindex="-1">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header bg-success">
                      <h5 class="modal-title" id="areaModalLabel" v-show="!deleteMode"><i 
                      class="fa-solid fa-building"></i>{{ !editMode ? ' Crear nueva Institución'
                                  :
                                  ' Editar Institución' }}</h5>
                      <h5 class="modal-title" id="areaModalLabel" v-show="deleteMode"><i 
                      class="fa-solid fa-building"></i> Cambiar Estado</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="row" v-show="editMode && !deleteMode">
                          <div class="form-group row">
                              <label for="codigo" class="col-sm-2 col-form-label">Código:</label>
                              <div class="col-sm-10">
                                  <input type="text" readonly class="form-control-plaintext text-warning" id="staticEmail"
                                      v-model="institucionData.codigo">
                              </div>
                          </div>

                      </div>
                      <div class="row" v-show="!deleteMode">
                          <div class="col-md-12">
                              <div class="form-group">
                                  <label for="descripcion">Descripción:</label>
                                  <textarea class="form-control text-uppercase" rows="3"
                                      v-model="institucionData.descripcion"></textarea>
                                  <span class="text-danger" v-show="institucionErrors.descripcion">El campo Descripción es
                                      requerida.</span>
                              </div>
                          </div>
                      </div>
                  </div>
                  <h5 class="text-center" v-show="deleteMode">Esta seguro de cambiar el estado de la Institución {{
                      this.institucionData.estado == 'A' ? ' a Inactivo' : 'a Activo' }} ?</h5>
                  <div class="modal-footer" v-show="!deleteMode">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                class="fa fa-times"></i></button>
                      <button type="button" @click="!editMode ? storeInstitucion() : updateInstitucion()" class="btn btn-primary">{{
                          !editMode ? 'Crear Institución' : 'Guardar Cambios' }} <i
                class="fa fa-check"></i></button>
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
          instituciones: {},
          institucionData: {
              id: '',
              codigo: '',
              descripcion: '',
              estado: '',

          },
          institucionErrors: {
              descripcion: false,

          },
      }
  },
  mounted() {

      this.getInstituciones();
      //this.table();
  },
  methods: {

    getInstituciones() {

          axios.get('/parametros/institucion/getListarInstituciones').then(response => {
              this.instituciones = response.data
              this.table();
          }).catch(errors => {
              console.log(errors)
          });

      },
      table() {
          this.$nextTick(() => {

              $('#tableInstitucion').DataTable({
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
          axios.post('/parametros/institucion/cambiarEstado/' + this.institucionData.id).then(response => {
              Swal.fire({
                  icon: 'success',
                  title: 'Se actualizo el estado correctamente',
                  showConfirmButton: false,
                  timer: 1500
              })
              this.getInstituciones()
          }).catch(errors => {
              console.log(errors)
          }).finally(() => {
              $('#institucionModal').modal('hide')
          });
      },
      removerInstitucion(unidad) {
          this.deleteMode = true
          this.institucionData.id = unidad.id
          this.institucionData.estado = unidad.estado
          $('#institucionModal').modal('show')
      },
      updateInstitucion() {

          this.institucionData.descripcion == '' ? this.institucionErrors.descripcion = true : this.institucionErrors.descripcion = false

          if (this.institucionData.descripcion) {
              axios.post('/parametros/institucion/update/' + this.institucionData.id, this.institucionData).then(response => {
                  Swal.fire({
                      icon: 'success',
                      title: 'Se actualizo la institucion correctamente',
                      showConfirmButton: false,
                      timer: 1500
                  })
                  this.getInstituciones()
              }).catch(errors => {
                  console.log(errors)
              }).finally(() => {
                  $('#institucionModal').modal('hide')
              });
          }

      },
      editarInstitucion(institucion) {
          this.deleteMode = false
          this.editMode = true
          this.institucionData = {
              id: institucion.id,
              codigo: institucion.codigo,
              descripcion: institucion.descripcion,
              estado: institucion.estado,

          }
          this.institucionErrors = {
              descripcion: false,

          }

          $('#institucionModal').modal('show')
      },
      crearInstitucion() {
          this.deleteMode = false
          this.editMode = false
          this.institucionData = {
              id: "",
              codigo: "",
              descripcion: "",
              estado: "",
          }
          this.institucionErrors = {
              descripcion: false,

          }
          $('#institucionModal').modal('show')
      },
      storeInstitucion() {

          this.institucionData.descripcion == '' ? this.institucionErrors.descripcion = true : this.institucionErrors.descripcion = false
          if (this.institucionData.descripcion) {
              axios.post('/parametros/institucion/crear', this.institucionData).then(response => {
                  Swal.fire({
                      icon: 'success',
                      title: 'Se grabo la institucion correctamente',
                      showConfirmButton: false,
                      timer: 1500
                  })
                  this.getInstituciones()
              }).catch(errors => {

                  console.log(errors)
              }).finally(() => {
                  $('#institucionModal').modal('hide')
              });
          }
      },
  },


}
</script>