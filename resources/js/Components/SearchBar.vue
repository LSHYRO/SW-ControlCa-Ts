<script>
export default {
  data() {
    return {
      searchTerm: "",
    };
  },
  methods: {
    search() {
      this.$emit("update-search", this.searchTerm);
    },
  },
};
</script>

<template>
  <input type="text" v-model="searchTerm" @input="search" placeholder="Buscar" />
</template>