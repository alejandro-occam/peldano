<template>
    <TableComponent ref="table" v-if="config.articles.show_view_state == 1"></TableComponent>
    <TableExportsComponent v-else></TableExportsComponent>
    <FormArticleComponent></FormArticleComponent>
    <DeleteArticleComponent></DeleteArticleComponent>
</template>

<script>

import { mapState } from "vuex";

import TableComponent from "./TableComponent.vue";
import FormArticleComponent from "./FormArticleComponent.vue";
import DeleteArticleComponent from "./DeleteArticleComponent.vue";
import TableExportsComponent from "./TableExportsComponent.vue"


export default {
    name: "ContentComponent",
    components: {
        TableComponent,
        FormArticleComponent,
        DeleteArticleComponent,
        TableExportsComponent
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
            this.$refs.table.listBatchs();
        }
    }
};
</script>
