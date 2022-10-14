<template>
    <TableComponent ref="table"></TableComponent>
</template>

<script>

import { mapState } from "vuex";

import TableComponent from "./TableComponent.vue";


export default {
    name: "ContentComponent",
    components: {
        TableComponent
    },
    data() {
        return {
            publicPath: window.location.origin,
        };
    },
    computed: {
        ...mapState(["config"]),
    },
    methods: {
        listArticles(){
            this.$refs.table.listArticles();
        }
    }
};
</script>
