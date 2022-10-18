<template>
    <div>
        <div class="d-flex">
            <AddButtonComponent
                    :columns="'col-1 ml-auto'"
                    :text="'Volver'"
                    :id="'btn_add_user'"
                    :src="'/media/custom-imgs/flecha_btn_volver.svg'"
                    :width="16"
                    :height="16"
                    @click.native="changeViewStatusProposals(1)"
                />
        </div>
        <div class="col-12 pl-0 mt-15">
            <h3 class="color-blue">Datos del cliente</h3>
            <div class="my-5 col-12 row">
                <div class="input-group px-0 d-flex">
                    <div class="w-25">
                        <span class="w-25">Empresa o nombre y apellidos</span>
                        <div class="mt-2">
                            <input v-model="name" type="text" class="form-control borders-box text-dark-gray" placeholder="" />
                            <small class="text-danger " v-if="name_error">El nombre no es válido</small>
                        </div>
                    </div>
                    <div class="w-25 ml-10">
                        <span class="w-25">Otros (localidad, e-mail, cif/nif, tlf, cp)</span>
                        <div class="mt-2">
                            <input v-model="name" type="text" class="form-control borders-box text-dark-gray" placeholder="" />
                            <small class="text-danger " v-if="name_error">El nombre no es válido</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-15">
                <button type="button" class="btn bg-azul color-white px-5 font-weight-bolder" @click="this.openFormArticle()">
                    <img class="mr-2" width="24" height="24" src="/media/custom-imgs/icono_btn_annadir_articulo_blanco.svg" />
                    Añadir artículo
                </button>
            </div>
        </div>
    </div>
    <FormAddArticleComponent></FormAddArticleComponent>
</template>

<script>

import { mapMutations, mapState } from "vuex";

import AddButtonComponent from "../Partials/AddButtonComponent.vue";
import FormAddArticleComponent from "./FormAddArticleComponent.vue";

export default {
    name: "FormComponent",
    components: {
        AddButtonComponent,
        FormAddArticleComponent
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
        ...mapMutations(["clearError", "changeViewStatusProposals"]),
        openFormArticle(){
            $('#modal_form_article_proposals').modal('show');
        }
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