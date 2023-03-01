<template>
    <div class="mb-20">
        <div class="justify-content-center">
            <div class="d-flex mb-10 ">
                <h1 class="color-blue my-auto" id="title_modal">Validación de facturas</h1>
            </div>
            <div class="card card-custom shadow-none border-0">
                <div class="card-body body-tab-step">
                    <TableComponent></TableComponent>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import TableComponent from "./TableComponent.vue";
    import { mapMutations, mapState } from "vuex";

    export default {
        name: "ContentComponent",
        components: {
            TableComponent,
        },
        data() {
            return {
                publicPath: window.location.origin,
            };
        },
        computed: {
                ...mapState(["errors", 'invoice_validations']),
        },
        methods: {
            ...mapMutations(["clearError"]),
            
        },
    };
</script>
