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
                <div class="input-group px-0 d-flex" v-if="this.select_company == '' && this.select_company_other_values == ''">
                    <div class="w-25">
                        <span class="w-25">Empresa o nombre y apellidos</span>
                        <div class="mt-2 select-filter">
                            <select class="form-control select2 select-filter" id="select_company" v-model="select_company">
                                <option :data-name="company.name" :value="company.id" v-for="company in proposals.array_companies" :key="company.id" v-text="company.name" ></option>
                            </select>
                        </div>
                    </div>
                    <div class="w-25 ml-10">
                        <span class="w-25">Otros (localidad, e-mail, cif/nif, tlf, cp)</span>
                        <div class="mt-2 select-filter">
                            <select class="form-control select2 select-filter" id="select_company_other_values" v-model="select_company_other_values">
                                <option :data-name="company.name" :value="company.id" v-for="company in proposals.array_companies" :key="company.id" v-text="company.email + ' - ' + company.nif" ></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="input-group px-0 d-flex mt-5" v-else>
                    <div class="bg-span-gray py-2 w-25 br-5">
                        <span class="font-weight-bolder color-white ml-5 f-15">{{ this.name_company }}</span>
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

import { mapMutations, mapState, mapActions } from "vuex";

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
            select_company: '',
            select_company_other_values: '',
            name_company: ''
        };
    },
    computed: {
            ...mapState(["errors", 'proposals']),
    },
    methods: {
        ...mapMutations(["clearError", "changeViewStatusProposals"]),
        ...mapActions(["getCompanies"]),
        openFormArticle(){
            $('#modal_form_article_proposals').modal('show');
        },
        getNameCompany(id){
            let me = this;
            me.proposals.array_companies.forEach(function callback(value, index, array) {
                if(value.id == id){
                    me.name_company = value.name;
                }
            });
        }
    },
    mounted() {
        let me = this;
        $('#select_company').select2({
            placeholder: "Selecciona una empresa"
        });
        $('#select_company_other_values').select2({
            placeholder: "Selecciona una empresa"
        });
        this.getCompanies();
        $('#select_company').on("change",function(){
            me.select_company = $('#select_company').val();
            me.getNameCompany(me.select_company);
        });
        $('#select_company_other_values').on("change",function(){
            me.select_company_other_values = $('#select_company_other_values').val();
            me.getNameCompany(me.select_company_other_values);
        });
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