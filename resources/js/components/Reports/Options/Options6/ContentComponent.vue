<template>
    <div class="d-flex mb-10 ">
        <h1 class="color-blue my-auto">Informes</h1>
        <div class="subheader-separator subheader-separator-ver my-auto mx-6" style="background-color: #c1c1cd;"></div>
        <span class="my-auto font-weight-bold color-dark-gray">Informe facturado.</span>
    </div>
    <div class="mb-20">
        <div class="justify-content-center">
            <div class="card card-custom shadow-none border-0">
                <div class="card-body body-tab-step">
                    <TableComponent></TableComponent>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapMutations, mapState } from "vuex";
import AddButtonComponent from "../../../Partials/AddButtonComponent.vue";
import TableComponent from "./TableComponent.vue";


export default {
    name: "Option7ContentComponen",
    components: {
        AddButtonComponent,
        TableComponent
    },
    data() {
        return {
            publicPath: window.location.origin,
        };
    },
    computed: {
            ...mapState(['reports']),
    },
    methods: {
        ...mapMutations(["clearError", "changeViewStatusReports"]),
    },
};
</script>
