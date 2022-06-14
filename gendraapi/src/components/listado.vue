<template>
 
  <div class="container">
 
    <div class="row-12">
 
      <h1>{{ msg }}</h1>
<button
      type="button"
      class="btn btn-primary"
      @click="showModal"
    >
      Agregar
    </button>

    <Modal
      v-show="isModalVisible"
      @close="closeModal"
    />
       

      <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Usuario</th>
            <th scope="col">Categoria</th>
            <th scope="col">Descripcion</th>
            <th scope="col">Imagen</th>
            <th scope="col">Estatus</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in evidencias" v-bind:key="item.id">  
            <th scope="row">{{ item.id }}</th>
            <td>{{ item.user_name }}</td>
            <td>{{ item.category_name }}</td>
            <td>{{ item.description }}</td>
            <td><img v-bind:src="'http://127.0.0.1:8000/storage/' + item.url_image" class="img-fluid ancho" width="100px" v-bind:alt="item.description"></td>
            <td>{{ item.status_name }}</td>
          </tr>
        </tbody>
      </table> 
      
    </div>
 
  </div>
  
</template>
 
<script>
  
  // Importamos el archivo de configuración de Axios 
  import API from '../api';
  import Modal from './Modal.vue';

  export default {
    components: {
      Modal,
    },
    name: 'Evidencias',
    props: {
      msg: String
    }, 
 
    // Definimos los campos para los datos que mostraremos en la tabla HTML 
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
        isModalVisible: false,
      }
    },
    methods: {
      showModal() {
        this.isModalVisible = true;
      },
      closeModal() {
        this.isModalVisible = false;
      }
    },
    // Colocamos la ruta del EndPoint de la API REST y luego 
    // lo imprimimos en consola para verificar si estamos recibiendo datos 
    mounted() {
      
      API.get('http://127.0.0.1:8000/evidenciasindex')
        .then(response => {
          this.evidencias = response.data;
          console.log(this.evidencias); 
        });
 
    },
 
  }
 
</script>
 
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
img {
  width: 100px !important;
}
/* */
</style>