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
     <div v-if="err" class="text-center successMsg bg-light rounded shadow">
          <h3 class="text-danger p-1">Transaction Failed</h3>
          <h3 class="text-bold pb-1">Result Code: {{ results.code }} </h3>
          <h3 class="text-bold ">Description:</h3>
          <h3 class="pb-2">{{ results.reference }}</h3>
          <button v-on:click="closeErrAlert()" class="btn btn-danger align-end">Close</button>
      </div>
    <form v-on:submit.prevent="submitPayment" v-on:submit="getUserHistory" class="form text-center pt-5">
      <div class="form-group">
        <label for="amount">Amount £</label>
        <input type="number" class="form-control" name="amount" v-model="fields.amount" id="amount" />
        <div v-if="errors && errors.name" class="text-danger">{{ errors }}</div>
      </div>
      <div class="form-group">
        <label for="referenceID">ReferenceID</label>
        <input type="text" class="form-control" name="referenceID" v-model="fields.reference" id="referenceID" />
      </div>
      <button type="submit"  class="btn btn-primary">Submit</button>
    </form>
      <div v-if="history" class="mt-5">
          <div  class="h5 text-center">Payment History for '{{ authUser.name }}'</div>
          <div  v-for="history in history" :key="history.id"  v-bind:class="[history.result === 1 ? 'alert-success' : 'alert-danger']" class="alert " role="alert">
             Date: {{ history.created_at}} . Amount: £{{ history.amount }} . Reference: {{ history.reference }}
          </div>
      </div>
  </div>
</template>
<script>
import axios from "axios";
import moment from 'moment';
export default {
  data: function () {
    return {
        authUser: window.auth_user,
        fields: {},
        errors: {},
        err: false,
        success: false,
        results: {},
        history: {},
    };
  },
  methods: {
      //Calls Laravel route /submitPayment with user params
    submitPayment() {
      this.errors = {};
      axios
        .get("/submitPayment", { params: {amount: this.fields.amount, referenceID: this.fields.reference}})
        .then((response) => {
          this.fields = {}; //Clear input field values
          this.results = {code: response.data.result.code, reference: response.data.result.description} //Passes response data to Vue object
          this.success = true;
        })
        .catch((error) => {
          //Catch any error during async
            this.errors = error.response.data.errors || {};
            this.err = true;
        });
    },
      closeAlert() {
        this.success = false;
      },
      closeErrAlert(){
        this.err = false;
      },
      getUserHistory(){
        axios
          .get('/api/PaymentHistory')
          .then( (res) => {
              this.history = res.data;
          }).catch( (err) => {
              console.log(err)
        })
      }
  },
    created() {
      this.getUserHistory()
    },

};
</script>
