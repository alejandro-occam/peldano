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
                                <span class="my-auto w-25">Departamento</span>
                            </div>
                            <div class="">
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_area'" :id="'select_department'" v-model="select_department" data-style="select-lightgreen" @change="getSectionSelect">
                                    <option value="" selected>
                                        Elige un departamento
                                    </option>
                                    <option :value="department.id" v-for="department in config.articles.form.array_departments" :key="department.id" v-text="department.nomenclature + '-' + department.name" ></option>
                                </select>
                                <small class="text-danger " v-if="select_department_error">El departamento no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Sección</span>
                            </div>
                            <div class="">
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_section'" :id="'select_section'" v-model="select_section" data-style="select-lightgreen" @change="getChannelSelect">
                                    <option value="" selected>
                                        Elige una sección
                                    </option>
                                    <option :value="section.id" v-for="section in config.articles.form.array_sections" :key="section.id" v-text="section.nomenclature + '-' + section.name" ></option>
                                </select>                                
                                <small class="text-danger" v-if="select_section_error">La sección no es válida</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Canal</span>
                            </div>
                            <div class="">
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_channel'" :id="'select_channel'" v-model="select_channel" data-style="select-lightgreen" @change="getProjectsSelect">
                                    <option value="" selected>
                                        Elige un canal
                                    </option>
                                    <option :value="channel.id" v-for="channel in config.articles.form.array_channels" :key="channel.id" v-text="channel.nomenclature + '-' + channel.name" ></option>
                                </select>
                                <small class="text-danger " v-if="select_channel_error">La marca no es válida</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Proyecto</span>
                            </div>
                            <div class="">
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_project'" :id="'select_project'" v-model="select_project" data-style="select-lightgreen" @change="getChaptersSelect">
                                    <option value="" selected>
                                        Elige un proyecto
                                    </option>
                                    <option :value="project.id" v-for="project in config.articles.form.array_projects" :key="project.id" v-text="project.nomenclature + '-' + project.name" ></option>
                                </select>
                                <small class="text-danger " v-if="select_project_error">El proyecto no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Capítulo</span>
                            </div>
                            <div class="">
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_chapter'" :id="'select_chapter'" v-model="select_chapter" data-style="select-lightgreen" @change="getBatchsSelect">
                                    <option value="" selected>
                                        Elige un capítulo
                                    </option>
                                    <option :value="chapter.id" v-for="chapter in config.articles.form.array_chapters" :key="chapter.id" v-text="chapter.name" ></option>
                                </select>
                                <small class="text-danger " v-if="select_chapter_error">El capítulo no es válido</small>
                            </div>
                        </div>

                        <div class="input-group my-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Lote</span>
                            </div>
                            <div class="">
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_batch'" :id="'select_batch'" v-model="select_batch" data-style="select-lightgreen" @change="getArticlesSelect">
                                    <option value="" selected>
                                        Elige un lote
                                    </option>
                                    <option :value="batch.id" v-for="batch in config.articles.form.array_batchs" :key="batch.id" v-text="batch.nomenclature + '-' + batch.name" ></option>
                                </select>
                                <small class="text-danger " v-if="select_batch_error">El lote no es válido</small>
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
                                    <option :value="article.id" v-for="article in config.articles.form.array_articles" :key="article.id" v-text="article.name + ' (' + $utils.numberWithDotAndComma($utils.roundAndFix(article.pvp)) + '€)'" ></option>
                                </select>
                                <small class="text-danger " v-if="select_article_error">El artículo no es válido</small>
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

                        <template v-if="this.array_magazines.length == 0">
                            <div class="input-group my-3 d-block">
                                <div class="mb-1">
                                    <div class="d-flex px-0 col-12 row">
                                        <div v-for="index in Number(this.amount)" class="mt-2 col-4">
                                            <span class="my-auto w-25">Fecha {{ index }}</span>
                                            <Calendar class="w-100 borders-box text-dark-gray mt-1"  autocomplete="off" v-model="this.date[index - 1]" dateFormat="dd-mm-yy"  />
                                        </div>
                                    </div>
                                    <small class="text-danger " v-if="date_error">Debes rellenar las fechas</small>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div class="input-group my-3 d-block">
                                <div class="mb-1">
                                    <div class="d-flex px-0 col-12 row ml-0">
                                        <div v-for="index in Number(this.amount)" class="mt-2 col-12 pr-0 pl-0">
                                            <span class="my-auto w-25">Fechas {{ index }}</span>
                                            <select  class="form-control bg-gray text-dark-gray select-custom mt-1" v-model="this.date[index - 1]" :name="'select_magazine'" :id="'select_magazine'" data-style="select-lightgreen">
                                                <option :value="magazine.output" v-for="magazine in this.array_magazines" :key="magazine.id" v-text="'Núm. ' + magazine.number + ' : ' + magazine.title + ' Fecha de salida : ' + magazine.output" ></option>
                                            </select>
                                        </div>
                                    </div>
                                    <small class="text-danger " v-if="date_error">Debes rellenar las fechas</small>
                                </div>
                            </div>
                        </template>
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
                select_department: '',
                select_department_error: false,
                department_obj: null,
                select_section: '',
                select_section_error: false,
                select_channel: '',
                select_channel_error: false,
                select_project: '',
                select_project_error: false,
                chapter_obj: null,
                select_chapter: '',
                select_chapter_error: false,
                select_batch: '',
                select_batch_error: false,
                select_article: '',
                article_obj: '',
                select_article_error: false,
                amount: '',
                amount_error: false,
                date: [],
                date_error: false,
                show_amount_dates: false,
                array_magazines: [],
            };
        },
        props: ["type"],
        computed: {
            ...mapState(["config", "errors", "proposals", "orders"]),
        },
        methods: {
            ...mapActions(["getDepartments", "getSections", "getChannels", "getProjects", "getChapters", "getBatchs", "getArticles", "addArticle", "updateArticle"]),
            ...mapMutations(["saveProposalObject"]),
            closeModal(){
                $("#modal_form_article_proposals").modal("hide");
                this.getDepartments({type: 2});
                this.clearForm();
            },
            //Validar datos
            validateForm(){
                this.valid = true;
                this.select_department_error = false;
                this.select_section_error = false;
                this.select_channel_error = false;
                this.select_project_error = false;
                this.select_chapter_error = false;
                this.select_batch_error = false;
                this.select_article_error = false;
                this.amount_error = false;
                this.date_error = false;

                if(this.select_department == "" || this.select_department == null || this.select_department == 0){
                    this.select_department_error = true;
                    this.valid = false;
                }

                if(this.select_section == "" || this.select_section == null || this.select_section == 0){
                    this.select_section_error = true;
                    this.valid = false;
                }

                if(this.select_channel == "" || this.select_channel == null || this.select_channel == 0){
                    this.select_channel_error = true;
                    this.valid = false;
                }

                if(this.select_project == "" || this.select_project == null || this.select_project == 0){
                    this.select_project_error = true;
                    this.valid = false;
                }

                if(this.select_chapter == "" || this.select_chapter == null || this.select_chapter == 0){
                    this.select_chapter_error = true;
                    this.valid = false;
                }

                if(this.select_batch == "" || this.select_batch == null || this.select_batch == 0){
                    this.select_batch_error = true;
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
                        if(this.array_magazines.length == 0){
                            //Formateamos la fecha dada por el calendario
                            var date_ms = Date.parse(this.date[i]);
                            this.date[i] = this.$utils.customFormDate(date_ms);
                            if(this.date[i] == '' || this.date[i] == null){
                                this.date_error = true;
                                this.valid = false;
                            }
                        }else{
                            if(this.date[i] == '' || this.date[i] == null){
                                this.date_error = true;
                                this.valid = false;
                            }
                        }
                    }
                }
                
                if(this.valid){ 
                    this.saveProposal();
                    this.getDepartments({type: 2});
                    this.clearForm();
                    $('#modal_form_article_proposals').modal('hide');

                }else{
                    swal("", "Rellena todos los datos", "warning");
                }
            },
            clearForm(){
                this.select_department = '';
                this.select_section = '';
                this.select_channel = '';
                this.select_project = '';
                this.select_chapter = '';
                this.select_batch = '';
                this.select_article = '';
                this.amount = '';
                this.show_amount_dates = false;
            },
            getSectionSelect(){
                let me = this;
                //Guardamos el objeto del departamento elegido
                me.config.articles.form.array_departments.forEach(function callback(value, index, array) {
                    if(value.id == me.select_department){
                        me.department_obj = value;
                    }
                });
                me.select_section = '';
                me.select_channel = '';
                me.select_project = '';
                me.select_chapter = '';
                me.select_batch = '';
                me.select_article = '';
                me.amount = '';
                me.date = [];
                me.show_amount_dates = false;
                var params = {
                    type: 2,
                    select_articles_department: me.select_department
                }
                me.getSections(params);
            },
            getChannelSelect(){
                let me = this;
                //Guardamos el objeto del sector elegido
                /*me.config.articles.form.array_sectors.forEach(function callback(value, index, array) {
                    if(value.id == me.select_sector){
                        me.sector_obj = value;
                    }
                });*/
                me.select_channel = '';
                me.select_project = '';
                me.select_chapter = '';
                me.select_batch = '';
                me.select_article = '';
                me.amount = '';
                me.date = [];
                me.show_amount_dates = false;
                var params = {
                    type: 2,
                    select_articles_section: me.select_section
                }
                me.getChannels(params);
            },
            getProjectsSelect(){
                this.select_project = '';
                this.select_chapter = '';
                this.select_batch = '';
                this.select_article = '';
                this.amount = '';
                this.date = [];
                this.show_amount_dates = false;
                var params = {
                    type: 2,
                    select_articles_channel: this.select_channel
                }
                this.getProjects(params);
            },
            getChaptersSelect(){
                this.select_chapter = '';
                this.select_batch = '';
                this.select_article = '';
                this.amount = '';
                this.date = [];
                this.show_amount_dates = false;
                var params = {
                    type: 2,
                    select_articles_project: this.select_project
                }
                this.getChapters(params);
            },
            getBatchsSelect(){
                let me = this;
                //Guardamos el objeto del artículo elegido
                me.config.articles.form.array_chapters.forEach(function callback(value, index, array) {
                    if(value.id == me.select_chapter){
                        me.chapter_obj = value;
                    }
                });
                me.select_batch = '';
                me.select_article = '';
                me.amount = '';
                me.date = [];
                me.show_amount_dates = false;
                var params = {
                    type: 2,
                    select_articles_chapter: me.select_chapter
                }
                me.getBatchs(params);
            },
            getArticlesSelect(){
                let me = this;
                me.select_article = '';
                me.amount = '';
                me.date = [];
                me.show_amount_dates = false;
                var params = {
                    type: 2,
                    select_articles_batch: me.select_batch
                }
                me.getArticles(params);
            },
            //Seleccionamos un artículo
            selectedArticle() {
                let me = this;
                me.array_magazines = [];
                me.amount = '';
                me.date = [];
                //Guardamos el objeto del artículo elegido
                me.config.articles.form.array_articles.forEach(function callback(value, index, array) {
                    if(value.id == me.select_article){
                        me.article_obj = value;
                        if(me.article_obj.array_calendars_magazines != undefined){
                            me.array_magazines = me.article_obj.array_calendars_magazines
                        }
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
                    department_obj: this.department_obj,
                    chapter_obj: this.chapter_obj,
                    article_obj: this.article_obj,
                    amount: this.amount,
                    dates: this.date,
                    type: this.type
                }
                this.saveProposalObject(params);
            }
        },
        mounted() {
            var params = {
                type: 2
            }
            this.getDepartments(params);
            $('#modal_form_article_proposals').on('hidden.bs.modal', async function (e) {
                this.getDepartments({type: 2});
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
    