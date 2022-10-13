<template>
    <TableComponent ref="table" v-if="config.calendars.show_view_state == 1"></TableComponent>
    <TableExportsComponent v-else></TableExportsComponent>
    <FormNumberComponent></FormNumberComponent>
</template>

<script>

import { mapState , mapActions, mapMutations} from "vuex";

import TableComponent from "./TableComponent.vue";
import TableExportsComponent from "./TableExportsComponent.vue"
import FormNumberComponent from "./FormNumberComponent.vue";

export default {
    name: "ContentComponent",
    components: {
        TableComponent,
        TableExportsComponent,
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
        ...mapActions(["getInfoFormAddCalendar", "addCalendar", "updateCalendar"]),
        ...mapMutations(["changeShowViewCalendar"]),
        listCalendars(){
            this.$refs.table.listCalendars();
        }
    },
    mounted() {
        this.getInfoFormAddCalendar();
    }
};
</script>
