<template>
  <div id="app">
    <Home></Home>
  </div>
</template>
<script>
import Home from "./components/Home.vue";

export default {
  data() {
    return {
      cruds: [],
    };
  },
  methods: {
    async create() {
      // To do
    },
    async read() {
      // To do
    },
    async update(id, color) {
      // To do
    },
    async del(id) {
      // To do
    },
  },
  components: {
    'Home': Home,
  },
};
</script>
