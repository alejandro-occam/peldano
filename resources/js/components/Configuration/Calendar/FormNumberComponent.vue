<template>
    <div class="modal fade" id="modal_form_number_calendar" tabindex="-1" role="dialog" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal Header -->
            <div class="modal-content">
                <form @submit.prevent="">
                    <div class="modal-header">
                        <h2 class="mx-auto color-blue" id="title_modal">Añadir número</h2>
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
                                    <option :value="position.id" v-for="position in config.users.array_positions" :key="position.id" v-text="position.name" ></option>
                                </select>
                                <small class="text-danger " v-if="name_error">El nombre no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Número</span>
                            </div>
                            <div class="">
                                <input v-model="name" type="number" class="form-control borders-box" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" />
                                <small class="text-danger " v-if="name_error">El nombre no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Título</span>
                            </div>
                            <div class="">
                                <input v-model="name" type="text" class="form-control borders-box" placeholder="" />
                                <small class="text-danger " v-if="name_error">El nombre no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Temas</span>
                            </div>
                            <div class="">
                                <Calendar v-model="date1" autocomplete="off" />
                                <small class="text-danger " v-if="name_error">El nombre no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Redacción</span>
                            </div>
                            <div class="">
                                <input v-model="name" type="text" class="form-control borders-box" placeholder="" />
                                <small class="text-danger " v-if="name_error">El nombre no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Publicidad</span>
                            </div>
                            <div class="">
                                <input v-model="name" type="text" class="form-control borders-box" placeholder="" />
                                <small class="text-danger " v-if="name_error">El nombre no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Salida</span>
                            </div>
                            <div class="">
                                <input v-model="name" type="text" class="form-control borders-box" placeholder="" />
                                <small class="text-danger " v-if="name_error">El nombre no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Facturación</span>
                            </div>
                            <div class="">
                                <input v-model="name" type="text" class="form-control borders-box" placeholder="" />
                                <small class="text-danger " v-if="name_error">El nombre no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Portada</span>
                            </div>
                            <div class="">
                                <input v-model="name" type="text" class="form-control borders-box" placeholder="" />
                                <small class="text-danger " v-if="name_error">El nombre no es válido</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-close ml-auto px-10 color-blue font-weight-bold bg-blue-light-white" data-dismiss="modal" @click="this.closeModal()">
                            Cancelar
                        </button>
                        <button type="submit" class="btn bg-azul color-white px-10 font-weight-bold" @click="this.deleteUser(config.users.user_obj.id)">
                            Añadir
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
                date1: null
            };
        },
        computed: {
            ...mapState(["config", "errors"]),
        },
        methods: {
            ...mapActions(["deleteUser"]),
            closeModal(){
                $("#modal_form_number_calendar").modal("hide");
            }
        },
        mounted() {}
    };
    </script>
    