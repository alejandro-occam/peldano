<template>
    <div class="mb-20">
        <div class="justify-content-center">
            <div class="d-flex mb-10 ">
                <h1 class="color-blue my-auto" id="title_modal">Órdenes</h1>
                <div class="subheader-separator subheader-separator-ver my-auto mx-6" style="background-color: #c1c1cd;" v-if="proposals.status_view != 1"></div>
                <span class="my-auto font-weight-bold color-dark-gray" v-if="proposals.status_view == 2">Nueva propuesta</span>
                <span class="my-auto font-weight-bold color-dark-gray" v-if="proposals.status_view == 3">Ofrecer propuesta</span>
            </div>
            <div class="card card-custom shadow-none border-0">
                <div class="card-body body-tab-step">
                    <TableComponent v-if="proposals.status_view == 1"></TableComponent>
                    <FormComponent v-else></FormComponent>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TableComponent from "./TableComponent.vue";
import FormComponent from "../Proposals/FormComponent.vue";
import { mapMutations, mapState } from "vuex";

export default {
    name: "ContentComponent",
    components: {
        TableComponent,
        FormComponent
    },
    data() {
        return {
            publicPath: window.location.origin,
        };
    },
    computed: {
            ...mapState(["errors", 'orders', 'proposals']),
    },
    methods: {
        ...mapMutations(["clearError"]),
        
    },
};
</script>
