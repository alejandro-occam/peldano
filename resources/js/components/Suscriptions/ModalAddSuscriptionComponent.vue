<template>
    <div class="modal fade" id="modal_add_suscription" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal Header -->
            <input id="id_consultant" type="hidden" value="" />
            <div class="modal-content">
                <form @submit.prevent="">
                    <div class="modal-header">
                        <h2 class="mx-auto color-blue" id="title_modal">{{ this.title_modal }}</h2>
                        <button type="button" class="close position-absolute" style="right: 22px;" data-dismiss="modal" @click="this.closeModal()">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-5 d-block" >
                            <span class="my-auto w-25">Cliente</span>
                            <div class="mt-2 select-filter">
                                <select class="form-control select2 select-filter" id="select_client" v-model="select_client">
                                </select>
                            </div>
                        </div>
                        <div class="input-group mb-5 d-block" >
                            <span class="my-auto w-25">Revista</span>
                            <select class="form-control bg-gray text-dark select-custom select-filter mt-3 w-100" style="color: #181C32 !important;" :name="'select_magazine'" :id="'select_magazine'" v-model="select_magazine" data-style="select-lightgreen" @change="listArticlesModal">
                                <option value="0" selected> Selecciona una revista</option>
                                <option v-for="calendar_magazine in suscriptions.array_calendars_magazines" :value="calendar_magazine.id" :key="calendar_magazine.id" v-text="calendar_magazine.name_calendar + ' - ' + calendar_magazine.title" ></option>
                            </select>
                        </div>
                        <div class="input-group mb-5 d-block" >
                            <span class="my-auto w-25">Artículo</span>
                            <select class="form-control bg-gray text-dark select-custom select-filter mt-3 w-100" style="color: #181C32 !important;" :name="'select_article'" :id="'select_article'" v-model="select_article" data-style="select-lightgreen">
                                <option value="0" selected> Selecciona un artículo </option>
                                <option v-for="article in suscriptions.array_articles" :value="article.id"  v-text="article.name" :key="article.id"></option>-->
                            </select>
                        </div>
                        <div class="input-group mb-5 d-block" >
                            <span class="my-auto w-25">Nº revista</span>
                            <div class="mt-3">
                                <input v-model="num" type="text" class="form-control borders-box text-dark-gray" placeholder="" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-close ml-auto px-10 color-blue font-weight-bold bg-blue-light-white" data-dismiss="modal" @click="this.closeModal()">
                            Cancelar
                        </button>
                        <button type="submit" class="btn bg-azul color-white px-10 font-weight-bold" @click="this.validateForm()">
                            Aceptar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
    
    <script>

import { mapState, mapMutations, mapActions } from "vuex";

    export default {
        name: "ModalAddSuscriptionComponent",
        components: {
        },
        data() {
            return {
                publicPath: window.location.origin,
                select_client: 0, 
                array_clients: [],
                select_magazine: 0,
                select_article: 0,
                num: '',
                title_modal: 'Añadir suscriptor',
                search_client: '',
                valid: false
            };
        },
        props: ["type"],
        computed: {
            ...mapState(["suscriptions"]),
        },
        methods: {
            ...mapMutations(["clearError", "updateConsultant"]),
            ...mapActions(["getCalendarsMagazines", "getArticlesSuscriptions", "addSusctiption"]),
            closeModal(){
                this.clearForm();
                
            },
            //Validar datos
            validateForm(){
                this.valid = true;
                
                if(this.select_client == "" || this.select_client == 0 || this.select_client == null
                    || this.select_magazine == "" || this.select_magazine == 0 || this.select_magazine == null
                    || this.select_article == "" || this.select_article == 0 || this.select_article == null
                    || this.num == "" || this.num == null){
                    this.valid = false;
                }
                
                if(this.valid){ 
                    var params = {
                        id_client: this.select_client,
                        id_calendar_magazine: this.select_magazine,
                        id_article: this.select_article,
                        num: this.num
                    }
                    this.addSusctiption(params);

                }else{
                    swal("", "Rellena todos los datos", "warning");
                }
            },
            clearForm(){
                this.select_client = 0;
                this.select_magazine = 0;
                this.select_article = 0;
                this.num = '';
                this.title_modal = 'Añadir suscriptor';
                $("#modal_add_consultant").modal("hide");
            },
            //Listar artículos
            listArticlesModal(){
                this.getArticlesSuscriptions(this.select_magazine);
                this.clearError();
            }
        },
        mounted() {
            let me = this;
            $('#select_client').select2({
                placeholder: "Selecciona un cliente",
                dropdownParent: $("#modal_add_suscription"),
                minimumInputLength: 5,
                ajax: {
                    url: '/admin/get_companies_search',
                    type: "POST",
                    delay: 250,
                    data: function (params) {
                        var queryParameters = {
                            "term": params.term,
                            "type_search": 1,
                            "_token": $('meta[name="csrf-token"]').attr("content"),
                        }
                        me.search_client = params.term;
                        return queryParameters;
                    },
                    processResults: function (data) {
                        me.array_clients = data;
                        return {
                            results: data
                        };
                        
                    }
                }
            });

            $('#select_client').on("change",function(){
                me.select_client = $('#select_client').val();
            });

            this.getCalendarsMagazines();
            $('#modal_add_suscription').on('hidden.bs.modal', async function (e) {
                this.clearForm();
            });
        },
        watch: {
            '$store.state.errors.code': function() {
                if(this.errors.type_error == 'add_suscription'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            swal("", "Suscripción creada correctamente", "success");
                            $("#modal_add_consultant").modal("hide");
                        }
                    }
                }
                this.clearError();
            }
        }
    };
</script>
<style>
    .select2.select2-container.select2-container--default{
        width: 100% !important;
    }
</style>