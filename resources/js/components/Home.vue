<template>
  <div class="text-2x1 text-gray-800 center_div">
    <p class="text-center h4">Transaction Details</p>
      <div v-if="success" class="text-center successMsg bg-light rounded shadow">
          <h3 class="text-success p-1">Transaction Added</h3>
          <h3 class="text-bold pb-1">Result Code: {{ results.code }} </h3>
          <h3 class="text-bold ">Description:</h3>
          <h3 class="pb-2">{{ results.reference }}</h3>
          <button v-on:click="closeAlert()" class="btn btn-danger align-end">Close</button>
      </div>
    <!--  <div v-if="failed" class="text-center successMsg bg-light rounded shadow">
          <h3 class="text-danger p-1">Transaction Added</h3>
          <h3 class="text-bold pb-1">Result Code: {{ results.code }} </h3>
          <h3 class="text-bold ">Description:</h3>
          <h3 class="pb-2">{{ results.reference }}</h3>
          <button v-on:click="closeAlert()" class="btn btn-danger align-end">Close</button>
      </div>-->
    <form v-on:submit.prevent="submitPayment" class="form text-center pt-5">
      <div class="form-group">
        <label for="amount">Amount £</label>
        <input type="number" class="form-control" name="amount" v-model="fields.amount" id="amount" />
        <div v-if="errors && errors.name" class="text-danger">{{ errors }}</div>
      </div>
      <div class="form-group">
        <label for="referenceID">ReferenceID</label>
        <input type="text" class="form-control" name="referenceID" v-model="fields.reference" id="referenceID" />
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</template>
<script>
import axios from "axios";
export default {
  data: function () {
    return {
        authUser: window.auth_user,
        fields: {},
        errors: {},
        success: false,
        results: {}
    };
  },
  methods: {
    submitPayment() {
      this.errors = {};
      axios
        .get("/submitPayment", { params: {amount: this.fields.amount, referenceID: this.fields.reference}})
        .then((response) => {
          this.fields = {}; //Clear input field values
          console.log(response.data.result);
         this.results = {code: response.data.result.code, reference: response.data.result.description}
          this.success = true;
        })
        .catch((error) => {
          //Catch any errors during submit
          console.log(error);
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {};
          }
        });
    },
      closeAlert() {
        this.success = false;
      }
  },
};
</script>
