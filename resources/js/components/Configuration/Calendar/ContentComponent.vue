<template>
    <TableComponent ref="table" v-if="config.calendars.show_view_state == 1"></TableComponent>
    <FormNumberComponent></FormNumberComponent>
</template>

<script>

import { mapState } from "vuex";

import TableComponent from "./TableComponent.vue";
import FormNumberComponent from "./FormNumberComponent.vue";

export default {
    name: "ContentComponent",
    components: {
        TableComponent,
        FormNumberComponent
    },
    data() {
        return {
            publicPath: window.location.origin,
        };
    },
    computed: {
        ...mapState(["config"]),
    },
    methods: {
        listCalendars(){
            this.$refs.table.listCalendars();
        }
    }
};
</script>
