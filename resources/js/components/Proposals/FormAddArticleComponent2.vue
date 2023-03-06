<template>
    <div class="modal fade" id="modal_form_article_proposals" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal Header -->
            <div class="modal-content">
                <form @submit.prevent="">
                    <div class="modal-header">
                        <h2 class="mx-auto color-blue" id="title_modal">Añadir artículo</h2>
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
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_product'" :id="'select_product'" v-model="select_product" data-style="select-lightgreen" @change="getArticlesSelect">
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
                                <span class="my-auto w-25">Artículos</span>
                            </div>
                            <div class="">
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_article'" :id="'select_article'" v-model="select_article" data-style="select-lightgreen" @change="selectedArticle()">
                                    <option value="" selected>
                                        Elige un artículo
                                    </option>
                                    <option :value="article.id" v-for="article in config.articles.form.array_articles" :key="article.id" v-text="article.name" ></option>
                                </select>
                                <small class="text-danger " v-if="select_product_error">El artículo no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-3 d-block" v-if="show_amount_dates">
                            <div class="mb-1">
                                <span class="my-auto w-25">Cantidad de artículos</span>
                            </div>
                            <div class="">
                                <span class="p-input-icon-right w-100">
                                    <input v-model="amount" type="text" class="form-control borders-box text-dark-gray" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 0" />
                                </span>
                                <small class="text-danger " v-if="amount_error">La cantidad de artículos no es válida</small>
                            </div>
                        </div>

                        <div class="input-group my-3 d-block">
                            <div class="mb-1">
                                <div class="d-flex px-0 col-12 row">
                                    <div v-for="index in Number(this.amount)" class="mt-2 col-4">
                                        <span class="my-auto w-25">Fecha {{ index }}</span>
                                        <Calendar :minDate="minDate" class="w-100 borders-box text-dark-gray mt-1"  autocomplete="off" v-model="this.date[index - 1]" dateFormat="dd-mm-yy"  />
                                    </div>
                                </div>
                                <small class="text-danger " v-if="date_error">Debes rellenar las fechas</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-close ml-auto px-10 color-blue font-weight-bold bg-blue-light-white" data-dismiss="modal" @click="this.closeModal()">
                            Cancelar
                        </button>
                        <button type="submit" class="btn bg-azul color-white px-10 font-weight-bold" @click="this.validateForm()">
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

    import { mapState, mapActions, mapMutations } from "vuex";

    export default {
        name: "FormAddArticleComponent",
        components: {
            Calendar
        },
        data() {
            return {
                publicPath: window.location.origin,
                select_area: '',
                select_area_error: false,
                select_sector: '',
                sector_obj: '',
                select_sector_error: false,
                select_brand: '',
                select_brand_error: false,
                select_product: '',
                product_obj: '',
                select_product_error: false,
                select_article: '',
                article_obj: '',
                select_article_error: false,
                amount: '',
                amount_error: false,
                date: [],
                date_error: false,
                show_amount_dates: false,
                minDate: ''
            };
        },
        props: ["type"],
        computed: {
            ...mapState(["config", "errors", "proposals", "orders"]),
        },
        methods: {
            ...mapActions(["getAreas", "getSectors", "getBrands", "getProducts", "getArticles", "addArticle", "updateArticle"]),
            ...mapMutations(["saveProposalObject"]),
            closeModal(){
                $("#modal_form_article_proposals").modal("hide");
                this.getAreas({type: 2});
                this.clearForm();
            },
            //Validar datos
            validateForm(){
                this.valid = true;
                this.select_area_error = false;
                this.select_sector_error = false;
                this.select_brand_error = false;
                this.select_product_error = false;
                this.select_article_error = false;
                this.amount_error = false;
                this.date_error = false;

                if(this.select_area == "" || this.select_area == null || this.select_area == 0){
                    this.select_area_error = true;
                    this.valid = false;
                }

                if(this.select_sector == "" || this.select_sector == null || this.select_sector == 0){
                    this.select_sector_error = true;
                    this.valid = false;
                }

                if(this.select_brand == "" || this.select_brand == null || this.select_brand == 0){
                    this.select_brand_error = true;
                    this.valid = false;
                }

                if(this.select_product == "" || this.select_product == null || this.select_product == 0){
                    this.select_product_error = true;
                    this.valid = false;
                }

                if(this.select_article == "" || this.select_article == null || this.select_article == 0){
                    this.select_article_error = true;
                    this.valid = false;
                }

                if(this.amount == "" || this.amount == null || this.amount == 0){
                    this.amount_error = true;
                    this.valid = false;
                }

                
                if(this.date.length > 0){
                    for(var i=0; i<this.date.length; i++){
                        //Formateamos la fecha dada por el calendario
                        var date_ms = Date.parse(this.date[i]);
                        this.date[i] = this.$utils.customFormDate(date_ms);
                        if(this.date[i] == '' || this.date[i] == null){
                            this.date_error = true;
                            this.valid = false;
                        }
                    }
                }
                
                if(this.valid){ 
                    this.saveProposal();
                    this.getAreas({type: 2});
                    this.clearForm();
                    $('#modal_form_article_proposals').modal('hide');

                }else{
                    swal("", "Rellena todos los datos", "warning");
                }
            },
            clearForm(){
                this.select_area = '';
                this.select_sector = '';
                this.select_brand = '';
                this.select_product = '';
                this.select_article = '';
                this.amount = '';
                this.show_amount_dates = false;
            },
            getSectorsSelect(){
                this.select_sector = '';
                this.select_brand = '';
                this.select_product = '';
                this.select_article = '';
                this.amount = '';
                this.date = [];
                this.show_amount_dates = false;
                var params = {
                    type: 2,
                    select_articles_areas: this.select_area
                }
                this.getSectors(params);
            },
            getBrandsSelect(){
                let me = this;
                //Guardamos el objeto del sector elegido
                me.config.articles.form.array_sectors.forEach(function callback(value, index, array) {
                    if(value.id == me.select_sector){
                        me.sector_obj = value;
                    }
                });
                me.select_brand = '';
                me.select_product = '';
                me.select_article = '';
                me.amount = '';
                me.date = [];
                me.show_amount_dates = false;
                var params = {
                    type: 2,
                    select_articles_sectors: me.select_sector
                }
                me.getBrands(params);
            },
            getProductsSelect(){
                this.select__product = '';
                this.select_article = '';
                this.amount = '';
                this.date = [];
                this.show_amount_dates = false;
                var params = {
                    type: 2,
                    select_articles_brands: this.select_brand
                }
                this.getProducts(params);
            },
            getArticlesSelect(){
                let me = this;
                //Guardamos el objeto del producto elegido
                me.config.articles.form.array_products.forEach(function callback(value, index, array) {
                    if(value.id == me.select_product){
                        me.product_obj = value;
                    }
                });
                me.select_article = '';
                me.amount = '';
                me.date = [];
                me.show_amount_dates = false;
                var params = {
                    type: 2,
                    select_articles_products: me.select_product
                }
                me.getArticles(params);
            },
            //Seleccionamos un artículo
            selectedArticle() {
                let me = this;
                me.amount = '';
                me.date = [];
                //Guardamos el objeto del artículo elegido
                me.config.articles.form.array_articles.forEach(function callback(value, index, array) {
                    if(value.id == me.select_article){
                        me.article_obj = value;
                    }
                });

                if(me.select_article != ''){
                    me.show_amount_dates = true;
                }else{
                    me.show_amount_dates = false;
                }
                
            },
            saveProposal() {
                var params = {
                    select_area: this.select_area,
                    sector_obj: this.sector_obj,
                    select_brand: this.select_brand,
                    product_obj: this.product_obj,
                    article_obj: this.article_obj,
                    amount: this.amount,
                    dates: this.date,
                    type: this.type
                }
                this.saveProposalObject(params);
            }
        },
        mounted() {
            this.minDate = new Date();
            var params = {
                type: 2
            }
            this.getAreas(params);
            $('#modal_form_article_proposals').on('hidden.bs.modal', async function (e) {
                this.getAreas({type: 2});
                this.clearForm();
            });

        },
        watch: {
            amount: {
                handler: async function(val) {
                    this.date = [];
                    for(var i=0; i<val; i++){
                        this.date[i] = new Date();//this.$utils.getNow();
                    }
                }
            }
        }
    };
</script>
    