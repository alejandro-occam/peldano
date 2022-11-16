<template>
    <div class="mb-20">
        <div class="justify-content-center">
            <div class="d-flex mb-10 ">
                <h1 class="color-blue my-auto" id="title_modal">Propuestas</h1>
                <div class="subheader-separator subheader-separator-ver my-auto mx-6" style="background-color: #c1c1cd;" v-if="proposals.status_view != 1"></div>
                <span class="my-auto font-weight-bold color-dark-gray" v-if="proposals.status_view == 2">Nueva propuesta</span>
                <span class="my-auto font-weight-bold color-dark-gray" v-if="proposals.status_view == 3">Ofrecer propuesta</span>
            </div>
            <div class="card card-custom shadow-none border-0">
                <div class="card-body body-tab-step">
                    <TableComponent v-if="proposals.status_view == 1"></TableComponent>
                    <FormComponent v-else-if="proposals.status_view == 2"></FormComponent>
                    <TableExportsComponent v-else></TableExportsComponent>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TableComponent from "./TableComponent.vue";
import FormComponent from "./FormComponent.vue";
import TableExportsComponent from "./TableExportsComponent.vue";

import { mapMutations, mapState } from "vuex";

export default {
    name: "ContentComponent",
    components: {
        TableComponent,
        FormComponent,
        TableExportsComponent
    },
    data() {
        return {
            publicPath: window.location.origin,
        };
    },
    computed: {
            ...mapState(["errors", 'proposals']),
    },
    methods: {
        ...mapMutations(["clearError"]),
        
    },
};
</script>
