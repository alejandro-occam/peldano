<template>
    <div class="mb-20">
        <div class="justify-content-center">
            <div class="d-flex mb-10 ">
                <h1 class="color-blue my-auto" id="title_modal">Órdenes</h1>
            </div>
            <div class="card card-custom shadow-none border-0">
                <div class="card-body body-tab-step">
                    <TableComponent v-if="orders.status_view == 1"></TableComponent>
                    <FormComponent v-else-if="orders.status_view == 2"></FormComponent>
                    <FormProposalComponent v-else></FormProposalComponent>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TableComponent from "./TableComponent.vue";
import FormComponent from "./FormComponent.vue";
import FormProposalComponent from "./FormProposalComponent.vue";
import { mapMutations, mapState } from "vuex";

export default {
    name: "ContentComponent",
    components: {
        TableComponent,
        FormComponent,
        FormProposalComponent
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
