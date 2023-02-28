<template>
    <div class="modal fade" id="modal_add_consultant" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <span class="my-auto w-25">Consultor</span>
                            <select class="form-control bg-gray text-dark select-custom select-filter mt-3 w-100" style="color: #181C32 !important;" :name="'select_consultant'" :id="'select_consultant'" v-model="select_consultant" data-style="select-lightgreen" @change="getConsultantSelect">
                                <option value="0" selected>
                                    Selecciona un consultor
                                </option>
                                <template v-if="this.type == 1">
                                    <template  v-for="index in Number(proposals.array_users.length)" :key="proposals.array_users[index - 1].id">
                                        <option :value="proposals.array_users[index - 1].id"  v-text="proposals.array_users[index - 1].name + ' ' + proposals.array_users[index - 1].surname" ></option>
                                    </template>
                                </template>
                                <template v-else>
                                    <template  v-for="index in Number(orders.array_users.length)" :key="orders.array_users[index - 1].id">
                                        <option :value="orders.array_users[index - 1].id"  v-text="orders.array_users[index - 1].name + ' ' + orders.array_users[index - 1].surname" ></option>
                                    </template>
                                </template>
                            </select>
                        </div>
                        <div class="input-group mb-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Porcentaje</span>
                            </div>
                            <div class="">
                                <input v-model="percentage" :id="'percentage'" type="text" class="form-control borders-box text-dark-gray" placeholder="" />
                                <small class="text-danger " v-if="percentage_error">La cantidad no es válida</small>
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
        name: "ModalAddConsultantComponent",
        components: {
        },
        data() {
            return {
                publicPath: window.location.origin,
                select_consultant: 0,
                percentage: '',
                percentage_error: false,
                id_consultant: '',
                name: '',
                valid: false,
                title_modal: 'Añadir consultor',
                is_update: 0
            };
        },
        props: ["type"],
        computed: {
            ...mapState(["proposals", "orders"]),
        },
        methods: {
            ...mapMutations(["updateConsultant"]),
            closeModal(){
                $("#modal_add_consultant").modal("hide");
            },
            //Validar datos
            validateForm(){
                let me = this;
                this.valid = true;
                this.valid2 = true;
                this.percentage_error = false;
                

                if(this.percentage == "" || this.percentage == null || this.percentage == 0){
                    this.percentage_error = true;
                    this.valid = false;
                }
                if(this.select_consultant == 0){
                    this.valid2 = false;
                }
                
                if(this.valid && this.valid2){ 
                    var consultant = {
                        id_consultant: this.id_consultant,
                        name: this.name,
                        percentage: this.percentage
                    }
                    var is_valid = true;
                    var value_percentage_aux = 0;
                    if(this.type == 1){
                        this.proposals.proposal_obj.array_consultants.forEach( function(value, index, array) {
                            if(index == 0){
                                value_percentage_aux = value.percentage;
                                if((value_percentage_aux - consultant.percentage) > 0){
                                    if(!me.is_update){
                                        value.percentage = value.percentage - consultant.percentage;
                                    }
                                }else{
                                    is_valid = false;
                                }
                            
                            }
                        });

                    }else{
                        this.orders.proposal_obj.array_consultants.forEach( function(value, index, array) {
                            if(index == 0){
                                value_percentage_aux = value.percentage;
                                if((value_percentage_aux - consultant.percentage) > 0){
                                    if(!me.is_update){
                                        value.percentage = value.percentage - consultant.percentage;
                                    }
                                }else{
                                    is_valid = false;
                                }
                            
                            }
                        });
                    }
                    
                    if(is_valid){
                        if(this.is_update){
                            this.updateConsultantModal();
                        }

                        if(!this.is_update){ 
                            this.proposals.proposal_obj.array_consultants.push(consultant);
                            this.orders.proposal_obj.array_consultants.push(consultant);
                        }
                        
                        this.clearForm();
                        $('#modal_add_consultant').modal('hide');
                    }else{
                        swal("", "Valor inválido", "warning");
                    }

                }else if(!this.valid){ 
                    swal("", "Rellena todos los datos", "warning");

                }else{
                    swal("", "Selecciona un consultor válido", "warning");
                }
            },
            clearForm(){
                this.select_consultant = 0;
                this.percentage = '';
                $('#id_consultant').val('');
                $('#select_consultant').prop('disabled', false);
                this.is_update = 0;
                this.title_modal = 'Añadir consultor';
            },
            getConsultantSelect(){
                let me = this;
                var is_select = false;
                if(this.type == 1){
                    this.proposals.proposal_obj.array_consultants.forEach( function(value, index, array) {
                        if(value.id_consultant == me.proposals.array_users[me.select_consultant - 1].id){
                            is_select = true;
                        }
                    });
                }else{
                    this.orders.proposal_obj.array_consultants.forEach( function(value, index, array) {
                        if(value.id_consultant == me.orders.array_users[me.select_consultant - 1].id){
                            is_select = true;
                        }
                    });
                }
                
                if(!is_select){
                    if(this.type == 1){
                        me.name = me.proposals.array_users[me.select_consultant - 1].name + " " + me.proposals.array_users[me.select_consultant - 1].surname;
                        me.id_consultant = me.proposals.array_users[me.select_consultant - 1].id;
                    }else{
                        me.name = me.orders.array_users[me.select_consultant - 1].name + " " + me.orders.array_users[me.select_consultant - 1].surname;
                        me.id_consultant = me.orders.array_users[me.select_consultant - 1].id;
                    }
                }else{
                    me.select_consultant = 0;
                    swal("", "Este consultor ya está seleccionado", "warning");
                }
            },
            updateConsultantModal(){
                var params = {
                    id: this.id_consultant, 
                    percentage: this.percentage,
                    type: this.type
                };
                this.updateConsultant(params);
            }
        },
        mounted() {
            $('#modal_add_consultant').on('hidden.bs.modal', async function (e) {
                this.clearForm();
            });
        },
    };
</script>
    