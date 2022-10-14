<template>
    <div class="modal fade" id="modal_form_article" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal Header -->
            <div class="modal-content">
                <form @submit.prevent="">
                    <div class="modal-header">
                        <h2 class="mx-auto color-blue" id="title_modal" v-if="this.config.calendars.is_update==0">Añadir artículo</h2>
                        <h2 class="mx-auto color-blue" id="title_modal" v-else >Modificar artículo</h2>
                        <button type="button" class="close position-absolute" style="right: 22px;" data-dismiss="modal" @click="this.closeModal()">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Área</span>
                            </div>
                            <div class="">
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_area'" :id="'select_area'" v-model="select_area" data-style="select-lightgreen" @change="getSectorsSelect">
                                    <option value="" selected>
                                        Elige un área
                                    </option>
                                    <option :value="area.id" v-for="area in config.articles.form.array_areas" :key="area.id" v-text="area.name" ></option>
                                </select>
                                <small class="text-danger " v-if="select_area_error">El área no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Sector</span>
                            </div>
                            <div class="">
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_sector'" :id="'select_sector'" v-model="select_sector" data-style="select-lightgreen" @change="getBrandsSelect">
                                    <option value="" selected>
                                        Elige un sector
                                    </option>
                                    <option :value="sector.id" v-for="sector in config.articles.form.array_sectors" :key="sector.id" v-text="sector.name" ></option>
                                </select>                                
                                <small class="text-danger" v-if="select_sector_error">El sector no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Marca</span>
                            </div>
                            <div class="">
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_brand'" :id="'select_brand'" v-model="select_brand" data-style="select-lightgreen" @change="getProductsSelect">
                                    <option value="" selected>
                                        Elige una marca
                                    </option>
                                    <option :value="brand.id" v-for="brand in config.articles.form.array_brands" :key="brand.id" v-text="brand.name" ></option>
                                </select>
                                <small class="text-danger " v-if="select_brand_error">La marca no es válida</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Producto</span>
                            </div>
                            <div class="">
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_product'" :id="'select_product'" v-model="select_product" data-style="select-lightgreen" >
                                    <option value="" selected>
                                        Elige un producto
                                    </option>
                                    <option :value="product.id" v-for="product in config.articles.form.array_products" :key="product.id" v-text="product.name" ></option>
                                </select>
                                <small class="text-danger " v-if="select_product_error">El producto no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Nombre</span>
                            </div>
                            <div class="">
                                <input v-model="name" type="text" class="form-control borders-box text-dark-gray" placeholder="" />
                                <small class="text-danger " v-if="name_error">El nombre no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Nombre en inglés</span>
                            </div>
                            <div class="">
                                <input v-model="name_eng" type="text" class="form-control borders-box text-dark-gray" placeholder="" />
                                <small class="text-danger " v-if="name_eng_error">El nombre en inglés no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Precio sin IVA</span>
                            </div>
                            <div class="">
                                <input v-model="price" type="number" class="form-control borders-box text-dark-gray" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" />
                                <small class="text-danger " v-if="price_error">El precio no es válido</small>
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
                select_area: '',
                select_area_error: false,
                select_sector: '',
                select_sector_error: false,
                select_brand: '',
                select_brand_error: false,
                select_product: '',
                select_product_error: false,
                name: '',
                name_error: false,
                name_eng: '',
                name_eng_error: false,
                price: '',
                price_error: false,
                is_update: 0,
            };
        },
        computed: {
            ...mapState(["config", "errors"]),
        },
        methods: {
            ...mapActions(["getAreas", "getSectors", "getBrands", "getProducts"]),
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
            },
            getSectorsSelect(){
                this.select_sector = '';
                this.select_brand = '';
                this.select_product = '';
                var params = {
                    type: 2,
                    select_articles_areas: this.select_area
                }
                this.getSectors(params);
            },
            getBrandsSelect(){
                this.select_brand = '';
                this.select_product = '';
                var params = {
                    type: 2,
                    select_articles_sectors: this.select_sector
                }
                this.getBrands(params);
            },
            getProductsSelect(){
                this.select__product = '';
                var params = {
                    type: 2,
                    select_articles_brands: this.select_brand
                }
                this.getProducts(params);
            }
        },
        mounted() {
            var params = {
                type: 2
            }
            this.getAreas(params);
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
    