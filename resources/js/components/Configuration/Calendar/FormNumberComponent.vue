<template>
    <div class="modal fade" id="modal_form_number_calendar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal Header -->
            <div class="modal-content">
                <form @submit.prevent="">
                    <div class="modal-header">
                        <h2 class="mx-auto color-blue" id="title_modal" v-if="this.config.calendars.is_update==0">Añadir número</h2>
                        <h2 class="mx-auto color-blue" id="title_modal" v-else >Modificar número</h2>
                        <button type="button" class="close position-absolute" style="right: 22px;" data-dismiss="modal" @click="this.closeModal()">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Calendario</span>
                            </div>
                            <div class="">
                                <select class="form-control w-100 bg-gray text-dark select-custom" :name="'select_calendar'" :id="'select_calendar'" v-model="select_calendar" data-style="select-lightgreen" >
                                    <option value="" selected>
                                        Elige un calendario
                                    </option>
                                    <option :value="calendar.id" v-for="calendar in config.calendars.array_calendars" :key="calendar.id" v-text="calendar.name" ></option>
                                </select>
                                <small class="text-danger " v-if="select_calendar_error">El calendario no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Número</span>
                            </div>
                            <div class="">
                                <input v-model="number" type="number" class="form-control borders-box" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" />
                                <small class="text-danger" v-if="number_error">El número no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Título</span>
                            </div>
                            <div class="">
                                <input v-model="title" type="text" class="form-control borders-box" placeholder="" />
                                <small class="text-danger " v-if="title_error">El título no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Temas</span>
                            </div>
                            <div class="">
                                <Calendar class="w-100 borders-box" inputId="topics_date" v-model="topics_date" autocomplete="off" dateFormat="dd-mm-yy" utc="true" />
                                <small class="text-danger " v-if="topics_date_error">La fecha del tema no es válida</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Redacción</span>
                            </div>
                            <div class="">
                                <Calendar class="w-100 borders-box" inputId="drafting_date" v-model="drafting_date" autocomplete="off" dateFormat="dd-mm-yy"  />
                                <small class="text-danger " v-if="drafting_date_error">La fecha de la redacción no es válida</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Publicidad</span>
                            </div>
                            <div class="">
                                <Calendar class="w-100 borders-box" inputId="commercial_date" v-model="commercial_date" autocomplete="off" dateFormat="dd-mm-yy"  />
                                <small class="text-danger " v-if="commercial_date_error">La fecha de la publicidad no es válida</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Salida</span>
                            </div>
                            <div class="">
                                <Calendar class="w-100 borders-box" inputId="output_date" v-model="output_date" autocomplete="off" dateFormat="dd-mm-yy"  />
                                <small class="text-danger " v-if="output_date_error">La fecha de la salida no es válida</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Facturación</span>
                            </div>
                            <div class="">
                                <Calendar class="w-100 borders-box" inputId="billing_date" v-model="billing_date" autocomplete="off" dateFormat="dd-mm-yy"  />
                                <small class="text-danger " v-if="billing_date_error">La fecha de la facturación no es válida</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Portada</span>
                            </div>
                            <div class="">
                                <Calendar class="w-100 borders-box" inputId="front_page_date" v-model="front_page_date" autocomplete="off" dateFormat="dd-mm-yy"  />
                                <small class="text-danger " v-if="front_page_date_error">La fecha de la portada no es válida</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-close ml-auto px-10 color-blue font-weight-bold bg-blue-light-white" data-dismiss="modal" @click="this.closeModal()">
                            Cancelar
                        </button>
                        <button type="submit" class="btn bg-azul color-white px-10 font-weight-bold" v-if="this.config.calendars.is_update == 0" @click="this.validateForm(1)">
                            Añadir
                        </button>
                        <button type="submit" class="btn bg-azul color-white px-10 font-weight-bold" v-else @click="this.validateForm(2)">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </template>
    
    <script>

    import Calendar from 'primevue/calendar';

    import { mapState, mapActions } from "vuex";

    export default {
        name: "FormNumberComponent",
        components: {
            Calendar
        },
        data() {
            return {
                publicPath: window.location.origin,
                select_calendar: '',
                select_calendar_error: false,
                number: '',
                number_error: false,
                title: '',
                title_error: false,
                topics_date: '',
                topics_date_error: false,
                drafting_date: '',
                drafting_date_error: false,
                commercial_date: '',
                commercial_date_error: false,
                output_date: '',
                output_date_error: false,
                billing_date: '',
                billing_date_error: false,
                front_page_date: '',
                front_page_date_error: false,
                is_update: 0,
            };
        },
        computed: {
            ...mapState(["config", "errors"]),
        },
        methods: {
            ...mapActions(["addCalendar", "updateCalendar"]),
            closeModal(){
                $("#modal_form_number_calendar").modal("hide");
            },
            //Validar datos
            validateForm(type){
                this.valid = true;
                this.select_calendar_error = false;
                this.number_error = false;
                this.title_error = false;
                this.topics_date_error = false;
                this.drafting_date_error = false;
                this.commercial_date_error = false;
                this.output_date_error = false;
                this.billing_date_error = false;
                this.front_page_date_error = false;

                if(this.select_calendar == "" || this.select_calendar == null || this.select_calendar == 0){
                    this.select_calendar_error = true;
                    this.valid = false;
                }
                if(this.number == "" || this.number == null){
                    this.number_error = true;
                    this.valid = false;
                }
                if(this.title == "" || this.title == null){
                    this.title_error = true;
                    this.valid = false;
                }
                if(this.topics_date == "" || this.topics_date == null){
                    this.topics_date_error = true;
                    this.valid = false;
                }
                if(this.drafting_date == "" || this.drafting_date == null){
                    this.drafting_date_error = true;
                    this.valid = false;
                }
                if(this.commercial_date == "" || this.commercial_date == null){
                    this.commercial_date_error = true;
                    this.valid = false;
                }
                if(this.output_date == "" || this.output_date == null){
                    this.output_date_error = true;
                    this.valid = false;
                }
                if(this.billing_date == "" || this.billing_date == null){
                    this.billing_date_error = true;
                    this.valid = false;
                }
                if(this.front_page_date == "" || this.front_page_date == null){
                    this.front_page_date_error = true;
                    this.valid = false;
                }

                console.log(this.topics_date);

                if(this.valid){
                    if(type == 1){
                        var params ={
                            'id_calendar': this.select_calendar,
                            'number': this.number,
                            'title': this.title,
                            'topics_date': this.topics_date,
                            'drafting_date': this.drafting_date,
                            'commercial_date': this.commercial_date,
                            'output_date': this.output_date,
                            'billing_date': this.billing_date,
                            'front_page_date': this.front_page_date
                        }
                        this.addCalendar(params);
                    }

                    if(type == 2){
                        var params = {
                            'id': this.config.calendars.calendar_obj.id,
                            'id_calendar': this.select_calendar,
                            'number': this.number,
                            'title': this.title,
                            'topics_date': this.topics_date,
                            'drafting_date': this.drafting_date,
                            'commercial_date': this.commercial_date,
                            'output_date': this.output_date,
                            'billing_date': this.billing_date,
                            'front_page_date': this.front_page_date
                        }

                        this.updateCalendar(params);
                    }
                    

                }else{
                    swal("", "Rellena todos los datos", "warning");
                }
            },
            clearForm(){
                this.select_calendar = '';
                this.title = '';
                this.number = '';
                this.topics_date = '';
                this.drafting_date = '';
                this.commercial_date = '';
                this.output_date = '';
                this.billing_date = '';
                this.front_page_date = '';
            }
        },
        mounted() {
        },
        watch: {
            '$store.state.config.calendars.calendar_obj': function() {
                let calendar = this.config.calendars.calendar_obj;
                this.select_calendar = calendar.id_calendar;
                this.title = calendar.title;
                this.number = calendar.number;
                this.topics_date = calendar.topics;
                this.drafting_date = calendar.drafting;
                this.commercial_date = calendar.commercial;
                this.output_date = calendar.output;
                this.billing_date = calendar.billing;
                this.front_page_date = calendar.front_page;
                this.is_update = 1;
            },
            '$store.state.config.calendars.is_update': function() {
                if(this.config.calendars.is_update == 0){
                    this.clearForm();
                }
            }
        }
    };
    </script>
    