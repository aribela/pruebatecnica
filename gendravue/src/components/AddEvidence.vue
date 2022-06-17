<template>
  <div class="submit-form">
    <div v-if="!submitted">
      <div class="form-group">
        <label for="status">Estatus</label>
        <select class="form-control" id="status" name="status" v-model="evidence.status" required>
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
          v-model="evidence.description"
          name="description"
        />
      </div>

      <button @click="saveEvidence" class="btn btn-success">Aceptar</button>
    </div>

    <div v-else>
      <h4>You submitted successfully!</h4>
      <button class="btn btn-success" @click="newEvidence">Add</button>
    </div>
  </div>
</template>

<script>
import EvidenceDataService from "../services/EvidenceDataService";

export default {
  name: "add-evidence",
  data() {
    return {
      evidence: {
        id: null,
        status: "",
        description: "",
        published: false,
        category_id: 1,
        user_id: 1,
      },
      submitted: false
    };
  },
  methods: {
    saveEvidence() {
      var data = {
        status: this.evidence.status,
        description: this.evidence.description,
        category_id: this.evidence.category_id,
        user_id: this.evidence.user_id,
        image: "",
      };

      EvidenceDataService.create(data)
        .then(response => {
          this.evidence.id = response.data.id;
          console.log(response.data);
          this.submitted = true;
        })
        .catch(e => {
          console.log(e);
        });
    },
    
    newEvidence() {
      this.submitted = false;
      this.evidence = {};
    }
  }
};
</script>

<style>
.submit-form {
  max-width: 300px;
  margin: auto;
}
</style>
