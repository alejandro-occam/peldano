<template>
    <div class="mb-20">
        <div class="justify-content-center">
            <div class="d-flex mb-10 ">
                <h1 class="color-blue my-auto" id="title_modal">Propuestas</h1>
                <div class="subheader-separator subheader-separator-ver my-auto mx-6" style="background-color: #c1c1cd;" v-if="proposals.status_view != 1"></div>
                <span class="my-auto font-weight-bold color-dark-gray" v-if="proposals.status_view == 2">Nueva propuesta</span>
                <span class="my-auto font-weight-bold color-dark-gray" v-if="proposals.status_view == 3">Ofrecer propuesta</span>
            </div>
            <div class="card card-custom shadow-none border-0">
                <div class="card-body body-tab-step">
                    <TableComponent v-if="proposals.status_view == 1"></TableComponent>
                    <FormComponent v-else></FormComponent>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TableComponent from "./TableComponent.vue";
import FormComponent from "./FormComponent.vue";

import { mapMutations, mapState } from "vuex";

export default {
    name: "ContentComponent",
    components: {
        TableComponent,
        FormComponent
    },
    data() {
        return {
            publicPath: window.location.origin,
        };
    },
    computed: {
            ...mapState(["errors", 'proposals']),
    },
    methods: {
        ...mapMutations(["clearError"]),
        
    },
    watch: {
            '$store.state.errors.code': function() {
                if(this.errors.type_error == 'delete_user'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            $("#list_users").KTDatatable("reload");
                            $("#modal_delete_user").modal("hide");
                            swal("", "Usuario eliminado correctamente", "success");
                        }else if(this.errors.code == 1001){
                            swal("", "El usuario no existe", "warning");
                        }else{
                            swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                        }
                        this.clearError();
                    }
                }
            }
        }
    
};
</script>
