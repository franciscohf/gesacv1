<template>
  <div>
      <div class="content-header">
          <div class="container-fluid">
              <div class="row mb-2">
                  <div class="col-sm-6">
                      <h1 class="m-0 text-dark">Área</h1>
                  </div>
              </div><!-- /.row -->
          </div><!-- /.container-fluid -->
      </div>
      <div class="content container-fluid">
          <div class="card">
              <div class="card-header">
                  <div class="card-tools">

                      <button @click="crearArea" class="btn btn-success btn-sm float-end">
                          <i class="fas fa-plus-square"></i> Nuevo Área
                      </button>
                  </div>
              </div>
              <div class="card-body">
                  <div class="card card-info">
                      <div class="card-header">
                          <h3 class="card-title">Bandeja de Resultados</h3>
                      </div>
                      <div class="card-body table-responsive">
                          <table class="table table-hover table-head-fixed text-nowrap projects" id="tableArea">
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
                                  <tr v-for="(area, index) in areas" :key="index">
                                      <td>{{ area.id }}</td>
                                      <td>{{ area.codigo }}</td>
                                      <td>{{ area.descripcion.length <= 20 ? area.descripcion :
                                          area.descripcion.substr(0, 20) + '...' }} </td>
                                      <td> {{ area.estado == 'A' ? 'Activo' : 'Inactivo' }}</td>
                                      <td>{{ dateTime(area.created_at) }}</td>
                                      <td>{{ dateTime(area.created_at) }}</td>
                                      <td>
                                          <button @click="editarArea(area)" class="btn btn-default btn-sm"> <i
                                                  class="fas fa-pencil-alt"></i> Editar</button>
                                          <button @click="removerArea(area)" class="btn btn-default btn-sm"><i
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
      <div class="modal" id="areaModal" tabindex="-1">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header bg-success">
                      <h5 class="modal-title" id="areaModalLabel" v-show="!deleteMode"><i 
                      class="fa-solid fa-book-atlas"></i>{{ !editMode ? ' Crear nueva Área'
                                  :
                                  ' Editar Área' }}</h5>
                      <h5 class="modal-title" id="areaModalLabel" v-show="deleteMode"><i 
                      class="fa-solid fa-book-atlas"></i> Cambiar Estado</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="row" v-show="editMode && !deleteMode">
                          <div class="form-group row">
                              <label for="codigo" class="col-sm-2 col-form-label">Código:</label>
                              <div class="col-sm-10">
                                  <input type="text" readonly class="form-control-plaintext text-warning" id="staticEmail"
                                      v-model="areaData.codigo">
                              </div>
                          </div>

                      </div>
                      <div class="row" v-show="!deleteMode">
                          <div class="col-md-12">
                              <div class="form-group">
                                  <label for="descripcion">Descripción:</label>
                                  <textarea class="form-control text-uppercase" rows="3"
                                      v-model="areaData.descripcion"></textarea>
                                  <span class="text-danger" v-show="areaErrors.descripcion">El campo Descripción es
                                      requerida.</span>
                              </div>
                          </div>
                      </div>
                  </div>
                  <h5 class="text-center" v-show="deleteMode">Esta seguro de cambiar el estado del  Área {{
                      this.areaData.estado == 'A' ? ' a Inactivo' : 'a Activo' }} ?</h5>
                  <div class="modal-footer" v-show="!deleteMode">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i
                class="fa fa-times"></i></button>
                      <button type="button" @click="!editMode ? storeArea() : updateArea()" class="btn btn-primary">{{
                          !editMode ? 'Crear Área' : 'Guardar Cambios' }} <i
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
          areas: {},
          areaData: {
              id: '',
              codigo: '',
              descripcion: '',
              estado: '',

          },
          areaErrors: {
              descripcion: false,

          },
      }
  },
  mounted() {

      this.getAreas();
      //this.table();
  },
  methods: {

      getAreas() {

          axios.get('/parametros/area/getListarAreas').then(response => {
              this.areas = response.data
              this.table();
          }).catch(errors => {
              console.log(errors)
          });

      },
      table() {
          this.$nextTick(() => {

              $('#tableArea').DataTable({
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
          axios.post('/parametros/area/cambiarEstado/' + this.areaData.id).then(response => {
              Swal.fire({
                  icon: 'success',
                  title: 'Se actualizo el estado correctamente',
                  showConfirmButton: false,
                  timer: 1500
              })
              this.getAreas()
          }).catch(errors => {
              console.log(errors)
          }).finally(() => {
              $('#areaModal').modal('hide')
          });
      },
      removerArea(unidad) {
          this.deleteMode = true
          this.areaData.id = unidad.id
          this.areaData.estado = unidad.estado
          $('#areaModal').modal('show')
      },
      updateArea() {

          this.areaData.descripcion == '' ? this.areaErrors.descripcion = true : this.areaErrors.descripcion = false

          if (this.areaData.descripcion) {
              axios.post('/parametros/area/update/' + this.areaData.id, this.areaData).then(response => {
                  Swal.fire({
                      icon: 'success',
                      title: 'Se actualizo el Área correctamente',
                      showConfirmButton: false,
                      timer: 1500
                  })
                  this.getAreas()
              }).catch(errors => {
                  console.log(errors)
              }).finally(() => {
                  $('#areaModal').modal('hide')
              });
          }

      },
      editarArea(unidad) {
          this.deleteMode = false
          this.editMode = true
          this.areaData = {
              id: unidad.id,
              codigo: unidad.codigo,
              descripcion: unidad.descripcion,
              estado: unidad.estado,

          }
          this.areaErrors = {
              descripcion: false,

          }

          $('#areaModal').modal('show')
      },
      crearArea() {
          this.deleteMode = false
          this.editMode = false
          this.areaData = {
              id: "",
              codigo: "",
              descripcion: "",
              estado: "",
          }
          this.areaErrors = {
              descripcion: false,

          }
          $('#areaModal').modal('show')
      },
      storeArea() {

          this.areaData.descripcion == '' ? this.areaErrors.descripcion = true : this.areaErrors.descripcion = false
          if (this.areaData.descripcion) {
              axios.post('/parametros/area/crear', this.areaData).then(response => {
                  Swal.fire({
                      icon: 'success',
                      title: 'Se grabo el Área correctamente',
                      showConfirmButton: false,
                      timer: 1500
                  })
                  this.getAreas()
              }).catch(errors => {

                  console.log(errors)
              }).finally(() => {
                  $('#areaModal').modal('hide')
              });
          }
      },
  },


}
</script>