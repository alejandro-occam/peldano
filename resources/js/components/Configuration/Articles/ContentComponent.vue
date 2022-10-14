<template>
    <TableComponent ref="table"></TableComponent>
    <FormArticleComponent></FormArticleComponent>
    <DeleteArticleComponent></DeleteArticleComponent>
</template>

<script>

import { mapState } from "vuex";

import TableComponent from "./TableComponent.vue";
import FormArticleComponent from "./FormArticleComponent.vue";
import DeleteArticleComponent from "./DeleteArticleComponent.vue";

export default {
    name: "ContentComponent",
    components: {
        TableComponent,
        FormArticleComponent,
        DeleteArticleComponent
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
