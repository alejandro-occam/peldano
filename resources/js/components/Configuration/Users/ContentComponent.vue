<template>
    <FormUserComponent ></FormUserComponent>
</template>

<script>
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
    },
    
    methods: {
    },
    mounted() {
    },
    watch: {
        
    }
};
</script>
