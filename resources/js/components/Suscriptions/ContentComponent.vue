<template>
    <div class="mb-20">
        <div class="justify-content-center">
            <div class="d-flex mb-10 ">
                <h1 class="color-blue my-auto" id="title_modal">Suscripciones</h1>
            </div>
            <div class="card card-custom shadow-none border-0">
                <div class="card-body body-tab-step">
                    <TableComponent></TableComponent>
                </div>
            </div>
        </div>
    </div>
    <ModalAddSuscriptionComponent></ModalAddSuscriptionComponent>
</template>

<script>
    import TableComponent from "./TableComponent.vue";
    import ModalAddSuscriptionComponent from "./ModalAddSuscriptionComponent.vue";
    import { mapMutations, mapState } from "vuex";

    export default {
        name: "ContentComponent",
        components: {
            TableComponent,
            ModalAddSuscriptionComponent
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
