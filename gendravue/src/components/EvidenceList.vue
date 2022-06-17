<template>
  <div class="row">
    <!-- <div class="col-md-8">
      <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Search by title"
          v-model="title"/>
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="button"
            @click="searchTitle"
          >
            Search
          </button>
        </div>
      </div>
    </div> -->
    <div class="col-md-8">
      <h4>Evidencias</h4>
      <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Usuario</th>
            <th scope="col">Categoria</th>
            <th scope="col">Descripcion</th>
            <th scope="col">Imagen</th>
            <th scope="col">Estatus</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in evidencias" v-bind:key="item.id">  
            <th scope="row">{{ item.id }}</th>
            <td>{{ item.user_name }}</td>
            <td>{{ item.category_name }}</td>
            <td>{{ item.description }}</td>
            <td><img v-bind:src="'http://gendra.test/storage/' + item.url_image" class="img-fluid ancho" width="100px" v-bind:alt="item.description"></td>
            <td>{{ item.status_name }}</td>
            <td>
              <button class="btn btn-primary" v-on:click="setActiveEvidence(item, index)">Editar</button>
              <button class="btn btn-danger" v-on:click="deleteEvidence(item.id)">Eliminar</button>

            </td>
          </tr>
        </tbody>
      </table> 

      <!-- <button class="m-3 btn btn-sm btn-danger" @click="removeAllTutorials">
        Remove All
      </button> -->
    </div>
    <div class="col-md-4">
      <div v-if="currentEvidence">
        <h4>Editar evidencia</h4>
        <div class="form-group">
        <label for="status">Estatus</label>
        <select class="form-control" id="status" name="status" v-model="currentEvidence.status" required>
          <option value="">Seleccione...</option>
          <option value="1">Activo</option>
          <option value="0">Inactivo</option>
        </select>
        
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <input
          class="form-control"
          id="description"
          required
          v-model="currentEvidence.description"
          name="description"
        />
      </div>

        <!-- <div>
          <label><strong>Status:</strong></label> {{ currentTutorial.published ? "Published" : "Pending" }}
        </div> -->

        <router-link :to="'/updateevidence/' + currentEvidence.id" class="btn btn-primary">Guardar</router-link>
      </div>
      <div v-else>
        <br />
        <p>Evidencias de todos los usuarios</p>
      </div>
    </div>
  </div>
</template>

<script>
import EvidenceDataService from "../services/EvidenceDataService";

export default {
  name: "tutorials-list",
  data() {
    return {
       fields: ['user_id', 'category_id', 'description', 'status', 'image', 'category_name', 'user_name', 'status_name', 'url_image'],
        id: "",
        user_id: "",
        category_id: "",
        description: "",
        status: "",
        image: "",        
        category_name: "",
        user_name: "",
        status_name: "",
        url_image: "",
        evidencias: [], // Creamos este array para almacenar los datos JSON 
      currentEvidence: null,
      currentIndex: -1,
      title: "",
    };
  },
  methods: {
    retrieveEvidence() {
      EvidenceDataService.getAll()
        .then(response => {
          this.evidencias = response.data;
          console.log(response.data);
        })
        .catch(e => {
          console.log(e);
        });
    },

    refreshList() {
      this.retrieveEvidence();
      this.currentEvidence = null;
      this.currentIndex = -1;
    },

    setActiveEvidence(evidence, index) {
      console.log(evidence);
      this.currentEvidence = evidence;
      this.currentIndex = index;
    },

    removeAllTutorials() {
      EvidenceDataService.deleteAll()
        .then(response => {
          console.log(response.data);
          this.refreshList();
        })
        .catch(e => {
          console.log(e);
        });
    },
    
    searchTitle() {
      EvidenceDataService.findByTitle(this.title)
        .then(response => {
          this.tutorials = response.data;
          console.log(response.data);
        })
        .catch(e => {
          console.log(e);
        });
    }
  },
  mounted() {
    this.retrieveEvidence();
  }
};
</script>

<style>
.list {
  text-align: left;
  max-width: 750px;
  margin: auto;
}
</style>
