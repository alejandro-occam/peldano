<template>
    <TableComponent ref="table" v-if="config.users.show_view_state == 1"></TableComponent>
    <FormUserComponent v-else-if="config.users.show_view_state == 2"></FormUserComponent>
    <ShowUserComponent v-else></ShowUserComponent>
    <DeleteUserComponent></DeleteUserComponent>
</template>

<script>

import { mapState } from "vuex";

import TableComponent from "./TableComponent.vue";
import FormUserComponent from "./FormUserComponent.vue";
import ShowUserComponent from "./ShowUserComponent.vue";
import DeleteUserComponent from "./DeleteUserComponent.vue";

export default {
    name: "ContentComponent",
    components: {
        TableComponent,
        FormUserComponent,
        ShowUserComponent,
        DeleteUserComponent
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
        listUsers(){
            this.$refs.table.listUsers();
        }
    }
};
</script>
