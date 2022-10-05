<template>
    <TableComponent v-if="show_view_state == 1"></TableComponent>
    <FormUserComponent v-else></FormUserComponent>
</template>

<script>

import { mapState, mapActions } from "vuex";

import TableComponent from "./TableComponent.vue";
import FormUserComponent from "./FormUserComponent.vue";


export default {
    name: "ContentComponent",
    components: {
        TableComponent,
        FormUserComponent
    },
    data() {
        return {
            publicPath: window.location.origin,
        };
    },
    computed: {
        ...mapState(["show_view_state"]),
    },
    watch: {
        
    }
};
</script>
