<template>
    <div class="modal fade" id="modal_form_article" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal Header -->
            <div class="modal-content">
                <form @submit.prevent="">
                    <div class="modal-header">
                        <h2 class="mx-auto color-blue" id="title_modal" v-if="this.config.articles.is_update==0">Añadir artículo</h2>
                        <h2 class="mx-auto color-blue" id="title_modal" v-else >Modificar artículo</h2>
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
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_department'" :id="'select_department'" v-model="select_department" data-style="select-lightgreen" @change="getSectionsSelect">
                                    <option value="" selected>
                                        Elige un departamento
                                    </option>
                                    <option :value="department.id" v-for="department in config.articles.form.array_departments" :key="department.id" v-text="department.nomenclature + '-' + department.name" ></option>
                                </select>
                                <small class="text-danger " v-if="select_department_error">El departamento no es válido</small>
                            </div>
                        </div>

                        <div class="input-group mb-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Sección</span>
                            </div>
                            <div class="">
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_section'" :id="'select_section'" v-model="select_section" data-style="select-lightgreen" @change="getChannelsSelect">
                                    <option value="" selected>
                                        Elige una sección
                                    </option>
                                    <option :value="section.id" v-for="section in config.articles.form.array_sections" :key="section.id" v-text="section.nomenclature + '-' +section.name" ></option>
                                </select>
                                <small class="text-danger " v-if="select_section_error">La sección no es válida</small>
                            </div>
                        </div>

                        <div class="input-group mb-5 d-block" >
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
                                <small class="text-danger " v-if="select_channel_error">El canal no es válido</small>
                            </div>
                        </div>

                        <div class="input-group mb-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Proyecto</span>
                            </div>
                            <div class="">
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_project'" :id="'select_project'" v-model="select_project" data-style="select-lightgreen" @change="getChaptersSelect">
                                    <option value="" selected>
                                        Elige un proyecto
                                    </option>
                                    <option :value="project.id" v-for="project in config.articles.form.array_projects" :key="project.id" v-text="project.nomenclature + '-' +project.name" ></option>
                                </select>
                                <small class="text-danger " v-if="select_project_error">El proyecto no es válido</small>
                            </div>
                        </div>

                        <div class="input-group mb-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Capítulo</span>
                            </div>
                            <div class="">
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_chapter'" :id="'select_chapter'" v-model="select_chapter" data-style="select-lightgreen" @change="getBatchSelect">
                                    <option value="" selected>
                                        Elige un capítulo
                                    </option>
                                    <option :value="chapter.id" v-for="chapter in config.articles.form.array_chapters" :key="chapter.id" v-text="chapter.nomenclature + '-' + chapter.name" ></option>
                                </select>
                                <small class="text-danger " v-if="select_chapter_error">El capítulo no es válido</small>
                            </div>
                        </div>

                        <div class="input-group mb-5 d-block" >
                            <div class="mb-1">
                                <span class="my-auto w-25">Lote</span>
                            </div>
                            <div class="">
                                <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_batch'" :id="'select_batch'" v-model="select_batch" data-style="select-lightgreen">
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
                                <span class="p-input-icon-right w-100">
                                    <input v-model="price" type="text" class="form-control borders-box text-dark-gray" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" />
                                    <img width="13" class="pi" src="/media/custom-imgs/icono_euro_input.svg"/>
                                </span>
                                <small class="text-danger " v-if="price_error">El precio no es válido</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-close ml-auto px-10 color-blue font-weight-bold bg-blue-light-white" data-dismiss="modal" @click="this.closeModal()">
                            Cancelar
                        </button>
                        <button type="submit" class="btn bg-azul color-white px-10 font-weight-bold" v-if="this.config.articles.is_update == 0" @click="this.validateForm(1)">
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
                select_department: '',
                select_department_error: false,
                select_section: '',
                select_section_error: false,
                select_channel: '',
                select_channel_error: false,
                select_project: '',
                select_project_error: false,
                select_chapter: '',
                select_chapter_error: false,
                select_batch: '',
                select_batch_error: false,
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
            ...mapActions(["getDepartments", "getSections", "getChannels", "getProjects", "getChapters", "getBatchs", "addArticle", "updateArticle"]),
            closeModal(){
                $("#modal_form_article").modal("hide");
                this.getDepartments({type: 2});
                this.clearForm();
            },
            //Validar datos
            validateForm(type){
                this.valid = true;
                this.select_department_error = false;
                this.select_section_error = false;
                this.select_channel_error = false;
                this.select_project_error = false;
                this.select_chapter_error = false;
                this.select_batch_error = false;
                this.name_error = false;
                this.name_eng_error = false;
                this.price_error = false;

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

                if(this.name == "" || this.name == null){
                    this.name_error = true;
                    this.valid = false;
                }
                if(this.name_eng == "" || this.name_eng == null){
                    this.name_eng_error = true;
                    this.valid = false;
                }
                if(this.price == "" || this.price == null || this.price == 0){
                    this.price_error = true;
                    this.valid = false;
                }
                
                if(this.valid){
                    if(type == 1){
                        var params ={
                            'id_batch': this.select_batch,
                            'name': this.name,
                            'name_eng': this.name_eng,
                            'price': this.price
                        }
                        this.addArticle(params);
                    }

                    if(type == 2){
                        var params ={
                            'id_article': this.config.articles.article_obj.id,
                            'id_batch': this.select_batch,
                            'name': this.name,
                            'name_eng': this.name_eng,
                            'price': this.price
                        }
                        this.updateArticle(params);
                    }
                    

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
                this.name = '';
                this.name_eng = '';
                this.price = '';
            },
            getSectionsSelect(){
                this.select_section = '';
                this.select_channel = '';
                this.select_project = '';
                this.select_chapter = '';
                this.select_batch = '';
                var params = {
                    type: 2,
                    select_articles_department: this.select_department
                }
                this.getSections(params);
            },
            getChannelsSelect(){
                this.select_channel = '';
                this.select_project = '';
                this.select_chapter = '';
                this.select_batch = '';
                var params = {
                    type: 2,
                    select_articles_section: this.select_section
                }
                this.getChannels(params);
            },
            getProjectsSelect(){
                this.select_project = '';
                this.select_chapter = '';
                this.select_batch = '';
                var params = {
                    type: 2,
                    select_articles_channel: this.select_channel
                }
                this.getProjects(params);
            },
            getChaptersSelect(){
                this.select_chapter = '';
                this.select_batch = '';
                var params = {
                    type: 2,
                    select_articles_project: this.select_project
                }
                this.getChapters(params);
            },
            getBatchSelect(){
                this.select_batch = '';
                var params = {
                    type: 2,
                    select_articles_chapter: this.select_chapter
                }
                this.getBatchs(params);
            }
        },
        mounted() {
            var params = {
                type: 2
            }
            this.getDepartments(params);
            $('#modal_form_article').on('hidden.bs.modal', async function (e) {
                this.getDepartments({type: 2});
                this.clearForm();
            });
        },
        watch: {
            '$store.state.config.articles.article_obj': function() {
                let article = this.config.articles.article_obj;
                this.select_department = article.id_department;
                this.select_section = article.id_section;
                this.select_channel = article.id_channel;
                this.select_project = article.id_project;
                this.select_chapter = article.id_chapter;
                this.select_batch = article.id_batch;
                this.name = article.name;
                this.name_eng = article.english_name;
                this.price = article.pvp;
                this.is_update = 1;
            },
            '$store.state.config.articles.is_update': function() {
                if(this.config.articles.is_update == 0){
                    this.getDepartments({type: 2});
                    this.clearForm();
                }
            }
        }
    };
    </script>
    