<template>
    <div class="modal fade" id="modal_reason_update_order" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal Header -->
            <div class="modal-content">
                <form @submit.prevent="">
                    <div class="modal-header">
                        <button type="button" class="close position-absolute" style="right: 22px;" data-dismiss="modal" @click="this.closeModal()">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Introduce el motivo de la modificación.</span>
                            </div>
                            <div class="">
                                <input v-model="reason" type="text" class="form-control borders-box text-dark-gray" placeholder="" />
                                <small class="text-danger " v-if="reason_error">Debes escribir un motivo.</small>
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

    import { mapState, mapMutations } from "vuex";

    export default {
        name: "ModalUpdateOrder",
        components: {
        },
        data() {
            return {
                publicPath: window.location.origin,
                reason: '',
                reason_error: false,
                valid: false
            };
        },
        computed: {
            ...mapState(["orders"]),
        },
        methods: {
            ...mapMutations(["updateCustomInvoiceNum"]),
            closeModal(){
                $("#modal_reason_update_order").modal("hide");
            },
            //Validar datos
            validateForm(){
                this.valid = true;
                this.reason_error = false;
                

                if(this.reason == "" || this.reason == null){
                    this.reason_error = true;
                    this.valid = false;
                }
                
                if(this.valid){ 
                    this.orders.reason_update = this.reason;
                    this.clearForm();
                    $('#modal_reason_update_order').modal('hide');

                }else{
                    swal("", "Rellena todos los datos", "warning");
                }
            },
            clearForm(){
                this.reason = '';
            },
        },
        mounted() {
            $('#modal_reason_update_order').on('hidden.bs.modal', async function (e) {
                this.clearForm();
            });

        }
    };
</script>
    