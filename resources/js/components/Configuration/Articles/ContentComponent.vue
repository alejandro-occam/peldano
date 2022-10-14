<template>
    <TableComponent ref="table"></TableComponent>
    <FormArticleComponent></FormArticleComponent>
</template>

<script>

import { mapState } from "vuex";

import TableComponent from "./TableComponent.vue";
import FormArticleComponent from "./FormArticleComponent.vue";

export default {
    name: "ContentComponent",
    components: {
        TableComponent,
        FormArticleComponent
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
