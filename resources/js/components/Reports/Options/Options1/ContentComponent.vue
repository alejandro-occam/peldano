<template>
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
    name: "Option1ContentComponen",
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
