<template>
    <div class="modal fade" id="modal_update_suscription" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal Header -->
            <input id="id_consultant" type="hidden" value="" />
            <div class="modal-content">
                <input type="hidden" id="array_suscriptions" name="array_suscriptions[]" />
                <form @submit.prevent="">
                    <div class="modal-header">
                        <h2 class="mx-auto color-blue" id="title_modal">{{ this.title_modal }}</h2>
                        <button type="button" class="close position-absolute" style="right: 22px;" data-dismiss="modal" @click="this.closeModal()">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-5 d-block" >
                            <span class="my-auto w-25">Nº revista inicial</span>
                            <div class="mt-3">
                                <input v-model="num" type="text" class="form-control borders-box text-dark-gray" placeholder="" />
                            </div>
                        </div>
                        <div class="input-group mb-5 d-block" >
                            <span class="my-auto w-25">Nº revista final</span>
                            <div class="mt-3">
                                <input v-model="num_finish" type="text" class="form-control borders-box text-dark-gray" placeholder="" />
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
        name: "ModalUpdateSuscriptionComponent",
        components: {
        },
        data() {
            return {
                publicPath: window.location.origin,
                array_suscriptions: [],
                num: '',
                num_finish: '',
                title_modal: 'Actualizar suscriptor',
                valid: false
            };
        },
        props: ["type"],
        computed: {
            ...mapState(["suscriptions", "errors"]),
        },
        methods: {
            ...mapMutations(["clearError"]),
            ...mapActions(["updateSusctiptions"]),
            closeModal(){
                this.clearForm();
                
            },
            //Validar datos
            validateForm(){
                this.valid = true;
                
                if(this.num == "" || this.num == null || this.num_finish == "" || this.num_finish == null){
                    this.valid = false;
                }

                this.array_suscriptions = document.getElementById('array_suscriptions').value;
                
                if(this.valid){ 
                    var params = {
                        array_suscriptions: this.array_suscriptions,
                        num: this.num,
                        num_finish: this.num_finish
                    }
                    this.updateSusctiptions(params);

                }else{
                    swal("", "Rellena todos los datos", "warning");
                }
            },
            clearForm(){
                this.id_suscriptor = 0;
                this.num = '';
                this.num_finish = '';
                this.title_modal = 'Actualizar suscriptor';
                $("#modal_update_suscription").modal("hide");
            }
        },
        mounted() {
            this.clearError();
        },
        watch: {
            '$store.state.errors.code': function() {
                if(this.errors.type_error == 'update_suscription'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            swal("", "Suscriptor/es actualizado/s correctamente", "success");
                            $("#modal_update_suscription").hide();
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