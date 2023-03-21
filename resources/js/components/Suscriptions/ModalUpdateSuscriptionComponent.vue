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
                            <span class="my-auto w-25">Forma de pago</span>
                            <select class="form-control bg-gray text-dark select-custom select-filter mt-3 w-100" style="color: #181C32 !important;" :name="'select_payment_method'" :id="'select_payment_method'" v-model="select_payment_method" data-style="select-lightgreen">
                                <option value="0" selected> Selecciona una forma de pago </option>
                                <option v-for="payment_method in suscriptions.array_payment_methods" :value="payment_method.id"  v-text="payment_method.name" :key="payment_method.id"></option>
                            </select>
                        </div>
                        <div class="input-group mb-5 d-block" >
                            <span class="my-auto w-25">Nº revista inicial</span>
                            <div class="mt-3">
                                <input v-model="num" type="number" class="form-control borders-box text-dark-gray" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 0"/>
                            </div>
                        </div>
                        <div class="input-group mb-5 d-block" >
                            <span class="my-auto w-25">Nº revista final</span>
                            <div class="mt-3">
                                <input v-model="num_finish" type="number" class="form-control borders-box text-dark-gray" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 0"/>
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
    <ProgressSpinner v-if="suscriptions.is_loading" style="position: fixed; top:50vh; left: 50%; z-index: 9999;" aria-label="Loading" />
</template>
    
<script>
    import ProgressSpinner from 'primevue/progressspinner';
    import { mapState, mapMutations, mapActions } from "vuex";

    export default {
        name: "ModalUpdateSuscriptionComponent",
        components: {
        },
        data() {
            return {
                publicPath: window.location.origin,
                array_suscriptions: [],
                select_payment_method: 0,
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
                
                if(this.num == "" || this.num <= 0 ||  this.num == null || this.num_finish == "" || this.select_payment_method == null || this.select_payment_method == 0 || this.num_finish == null || this.num_finish <= 0){
                    this.valid = false;
                }

                this.array_suscriptions = document.getElementById('array_suscriptions').value;
                
                if(this.valid){ 
                    var params = {
                        id_payment_method: this.select_payment_method,
                        array_suscriptions: this.array_suscriptions,
                        num: this.num,
                        num_finish: this.num_finish
                    }
                    this.suscriptions.is_loading = true;
                    this.updateSusctiptions(params);

                }else{
                    swal("", "Rellena todos los datos", "warning");
                }
            },
            clearForm(){
                this.id_suscriptor = 0;
                this.select_payment_method = 0;
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
                this.suscriptions.is_loading = false;
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