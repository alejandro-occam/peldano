<template>
    <div class="modal fade" id="modal_custom_invoice" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal Header -->
            <div class="modal-content">
                <form @submit.prevent="">
                    <div class="modal-header">
                        <h2 class="mx-auto color-blue" id="title_modal">Factura personalizada</h2>
                        <button type="button" class="close position-absolute" style="right: 22px;" data-dismiss="modal" @click="this.closeModal()">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Cantidad de facturas para esta propuesta</span>
                            </div>
                            <div class="">
                                <input v-model="amount" type="text" class="form-control borders-box text-dark-gray" placeholder="" />
                                <small class="text-danger " v-if="amount_error">La cantidad no es válida</small>
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
        name: "FormAddArticleComponent",
        components: {
        },
        data() {
            return {
                publicPath: window.location.origin,
                amount: '',
                amount_error: false,
                valid: false
            };
        },
        computed: {
            ...mapState(["proposals"]),
        },
        methods: {
            ...mapMutations(["updateCustomInvoiceNum"]),
            closeModal(){
                $("#modal_custom_invoice").modal("hide");
            },
            //Validar datos
            validateForm(){
                this.valid = true;
                this.amount_error = false;
                

                if(this.amount == "" || this.amount == null || this.amount == 0){
                    this.amount_error = true;
                    this.valid = false;
                }
                
                if(this.valid){ 
                    this.proposals.num_custom_invoices = this.amount;
                    this.clearForm();
                    $('#modal_custom_invoice').modal('hide');

                }else{
                    swal("", "Rellena todos los datos", "warning");
                }
            },
            clearForm(){
                this.amount = '';
            },
        },
        mounted() {
            $('#modal_custom_invoice').on('hidden.bs.modal', async function (e) {
                this.clearForm();
            });

        }
    };
</script>
    