<template>
    <TableComponent v-if="config.users.show_view_state == 1"></TableComponent>
    <FormUserComponent v-else></FormUserComponent>
</template>

<script>

import { mapState } from "vuex";

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
        ...mapState(["config"]),
    },
    watch: {
        
    }
};
</script>
