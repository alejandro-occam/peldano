<template>
    <TableComponent v-if="this.show_view_state == 1"></TableComponent>
    <FormUserComponent v-else="this.show_view_state == 2"></FormUserComponent>
</template>

<script>
import TableComponent from "./TableComponent.vue";
import FormUserComponent from "./FormUserComponent.vue";

import { mapState } from "vuex";

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
        ...mapState([
            "show_view_state",
        ]),
    },
    
    methods: {
    },
    mounted() {
    },
    watch: {
        
    }
};
</script>
