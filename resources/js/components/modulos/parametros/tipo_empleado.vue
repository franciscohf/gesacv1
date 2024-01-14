<template>
  <div>
      <div class="content-header">
          <div class="container-fluid">
              <div class="row mb-2">
                  <div class="col-sm-6">
                      <h1 class="m-0 text-dark">Tipo Empleado</h1>
                  </div>
              </div><!-- /.row -->
          </div><!-- /.container-fluid -->
      </div>
      <div class="content container-fluid">
          <div class="card">
              <div class="card-header">
                  <div class="card-tools">

                      <button @click="crearTipoEmpleado" class="btn btn-success btn-sm float-end">
                          <i class="fas fa-plus-square"></i> Nuevo Tipo Empleado
                      </button>
                  </div>
              </div>
              <div class="card-body">
                  <div class="card card-info">
                      <div class="card-header">
                          <h3 class="card-title">Bandeja de Resultados</h3>
                      </div>
                      <div class="card-body table-responsive">
                          <table class="table table-hover table-head-fixed text-nowrap projects" id="tableUnidad">
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
                                  <tr v-for="(tipoEmpleado, index) in tipoEmpleados" :key="index">
                                      <td>{{ tipoEmpleado.id }}</td>
                                      <td>{{ tipoEmpleado.codigo }}</td>
                                      <td>{{ tipoEmpleado.descripcion.length <= 20 ? tipoEmpleado.descripcion :
                                          tipoEmpleado.descripcion.substr(0, 20) + '...' }} </td>
                                      <td> {{ tipoEmpleado.estado == 'A' ? 'Activo' : 'Inactivo' }}</td>
                                      <td>{{ dateTime(tipoEmpleado.created_at) }}</td>
                                      <td>{{ dateTime(tipoEmpleado.created_at) }}</td>
                                      <td>
                                          <button @click="editarTipoEmpleado(tipoEmpleado)" class="btn btn-default btn-sm"> <i
                                                  class="fas fa-pencil-alt"></i> Editar</button>
                                          <button @click="removerTipoEmpleado(tipoEmpleado)" class="btn btn-default btn-sm"><i
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
      <div class="modal" id="tipoEmpleadoModal" tabindex="-1">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header bg-success">
                      <h5 class="modal-title" id="areaModalLabel" v-show="!deleteMode"><i
                              class="fa-solid fa-address-card"></i>{{ !editMode ? ' Crear nuevo Tipo de Empleado'
                                  :
                                  ' Editar Tipo de Empleado' }}</h5>
                      <h5 class="modal-title" id="areaModalLabel" v-show="deleteMode"><i
                              class="fa-solid fa-address-card"></i> Cambiar Estado</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="row" v-show="editMode && !deleteMode">
                          <div class="form-group row">
                              <label for="codigo" class="col-sm-2 col-form-label">Código:</label>
                              <div class="col-sm-10">
                                  <input type="text" readonly class="form-control-plaintext text-warning" id="staticEmail"
                                      v-model="tipoEmpleadoData.codigo">
                              </div>
                          </div>

                      </div>
                      <div class="row" v-show="!deleteMode">
                          <div class="col-md-12">
                              <div class="form-group">
                                  <label for="descripcion">Descripción:</label>
                                  <textarea class="form-control text-uppercase" rows="3"
                                      v-model="tipoEmpleadoData.descripcion"></textarea>
                                  <span class="text-danger" v-show="tipoEmpleadoErrors.descripcion">El campo Descripción es
                                      requerida.</span>
                              </div>
                          </div>
                      </div>
                  </div>
                  <h5 class="text-center" v-show="deleteMode">Esta seguro de cambiar el estado del Tipo de Empleado {{
                      this.tipoEmpleadoData.estado == 'A' ? ' a Inactivo' : 'a Activo' }} ?</h5>
                  <div class="modal-footer" v-show="!deleteMode">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                class="fa fa-times"></i></button>
                      <button type="button" @click="!editMode ? storeTipoEmpleado() : updateTipoEmpleado()" class="btn btn-primary">{{
                          !editMode ? 'Crear Tipo de Empleado' : 'Guardar Cambios' }} <i
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
          tipoEmpleados: {},
          tipoEmpleadoData: {
              id: '',
              codigo: '',
              descripcion: '',
              estado: '',

          },
          tipoEmpleadoErrors: {
              descripcion: false,

          },
      }
  },
  mounted() {

      this.gettipoEmpleados();
      //this.table();
  },
  methods: {

      gettipoEmpleados() {

          axios.get('/parametros/tipoEmpleado/getListarTipoEmpleados').then(response => {
              this.tipoEmpleados = response.data
              this.table();
          }).catch(errors => {
              console.log(errors)
          });

      },
      table() {
          this.$nextTick(() => {

              $('#tableUnidad').DataTable({
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
          axios.post('/parametros/tipoEmpleado/cambiarEstado/' + this.tipoEmpleadoData.id).then(response => {
              Swal.fire({
                  icon: 'success',
                  title: 'Se actualizo el estado correctamente',
                  showConfirmButton: false,
                  timer: 1500
              })
              this.gettipoEmpleados()
          }).catch(errors => {
              console.log(errors)
          }).finally(() => {
              $('#tipoEmpleadoModal').modal('hide')
          });
      },
      removerTipoEmpleado(unidad) {
          this.deleteMode = true
          this.tipoEmpleadoData.id = unidad.id
          this.tipoEmpleadoData.estado = unidad.estado
          $('#tipoEmpleadoModal').modal('show')
      },
      updateTipoEmpleado() {

          this.tipoEmpleadoData.descripcion == '' ? this.tipoEmpleadoErrors.descripcion = true : this.tipoEmpleadoErrors.descripcion = false

          if (this.tipoEmpleadoData.descripcion) {
              axios.post('/parametros/tipoEmpleado/update/' + this.tipoEmpleadoData.id, this.tipoEmpleadoData).then(response => {
                  Swal.fire({
                      icon: 'success',
                      title: 'Se actualizo el Tipo de Empleado correctamente',
                      showConfirmButton: false,
                      timer: 1500
                  })
                  this.gettipoEmpleados()
              }).catch(errors => {
                  console.log(errors)
              }).finally(() => {
                  $('#tipoEmpleadoModal').modal('hide')
              });
          }

      },
      editarTipoEmpleado(unidad) {
          this.deleteMode = false
          this.editMode = true
          this.tipoEmpleadoData = {
              id: unidad.id,
              codigo: unidad.codigo,
              descripcion: unidad.descripcion,
              estado: unidad.estado,

          }
          this.tipoEmpleadoErrors = {
              descripcion: false,

          }

          $('#tipoEmpleadoModal').modal('show')
      },
      crearTipoEmpleado() {
          this.deleteMode = false
          this.editMode = false
          this.tipoEmpleadoData = {
              id: "",
              codigo: "",
              descripcion: "",
              estado: "",
          }
          this.tipoEmpleadoErrors = {
              descripcion: false,

          }
          $('#tipoEmpleadoModal').modal('show')
      },
      storeTipoEmpleado() {

          this.tipoEmpleadoData.descripcion == '' ? this.tipoEmpleadoErrors.descripcion = true : this.tipoEmpleadoErrors.descripcion = false
          if (this.tipoEmpleadoData.descripcion) {
              axios.post('/parametros/tipoEmpleado/crear', this.tipoEmpleadoData).then(response => {
                  Swal.fire({
                      icon: 'success',
                      title: 'Se grabo el Tipo de Empleado correctamente',
                      showConfirmButton: false,
                      timer: 1500
                  })
                  this.gettipoEmpleados()
              }).catch(errors => {

                  console.log(errors)
              }).finally(() => {
                  $('#tipoEmpleadoModal').modal('hide')
              });
          }
      },
  },


}
</script>