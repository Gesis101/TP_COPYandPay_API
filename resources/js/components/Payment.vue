<template>
    <div class="text-2x1 text-gray-800 center_div">
        <div v-bind:class="[status ? 'text-success' : 'text-danger']" class="text-center successMsg2 bg-light rounded shadow">
            <h3 v-bind:class="[status ? 'text-success' : 'text-danger']" class="p-1">Checkout Status</h3>
            <h3 class="text-bold pb-1">Result Code: {{ paymentData.code }}</h3>
            <h3 class="text-bold">Description: {{ paymentData.description }}</h3>
            <h3 class="pb-2"></h3>
            <router-link to="/home" class="btn btn-danger align-end">RETURN</router-link>
        </div>

    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: "Payment",
    data: function () {
        return {
            paymentData: {},
            amount: {},
            references: {},
            added: false,
            status: 0
        };
    },
    created() {
        //this.test(),
        this.getStatus()
    },
    methods:{
        getStatus() {
            axios
            .get('/api/status/?id='+this.$route.query.id)
            .then(res => {
                console.log(res.data);
                this.amount = res.data.amount
               this.references = res.data.merchantTransactionId
                this.paymentData = {description: res.data.result.description, code: res.data.result.code}
                if (res.data.result.description.includes('successfully')){
                    this.status = 1
                    this.addToHistory();
                } else {
                    this.addToHistory();
                    this.status = 0``
                }
            })
        },
        addToHistory(){
            axios
                .get("/api/createHistory", {
                    params: {
                        amount: this.amount,
                        reference: this.references,
                        status: this.status
                    }
                })
                .then(res => {
                    console.log(res)
                    this.added = true;
                }).catch(err => {
                    this.added = false;
                    console.log(err)

            })
        }

    }
}
</script>
