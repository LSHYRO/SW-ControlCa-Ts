<script>
export default {
    data() {
        return {
            searchTerm: "",
        };
    },
    methods: {
        search() {
            this.$emit("search", this.searchTerm);
        },
    },
};
</script>
  
<template>
        <input type="text" v-model="searchTerm" @input="search" placeholder="Buscar profesor" />
</template>
  
  