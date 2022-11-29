<template>
    <div class="col-12 d-flex flex-wrap mt-6">
        <div class="col-12 d-flex flex-wrap justify-content-between mb-10">
            <h3 class="color-blue my-auto">Opciones de informe</h3>
            <div class="d-flex">
                <AddButtonComponent
                    :columns="'px-4 ml-auto mr-7'"
                    :text="'Exportar'"
                    :id="'btn_export'"
                    :src="'/media/custom-imgs/icono_btn_exportar.svg'"
                    :width="16"
                    :height="16"
                    @click.native="changeViewStatusProposals(3)"
                />
                <AddButtonComponent
                    :columns="'ml-auto mr-7'"
                    :text="'Volver'"
                    :id="'btn_return'"
                    :src="'/media/custom-imgs/flecha_btn_volver.svg'"
                    :width="16"
                    :height="16"
                    @click.native="changeViewStatusReports(1)"
                />
            </div>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Departamento</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_department" :name="'select_department'" :id="'select_department'" data-style="select-lightgreen" @change="getSectionSelect">
                <option value="" selected>
                    Filtro por departamento
                </option>
                <option :value="department.id" v-for="department in config.articles.filter.array_departments" :key="department.id" v-text="department.nomenclature + '-' + department.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Sección</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_section" :name="'select_section'" :id="'select_section'" data-style="select-lightgreen" @change="getChannelSelect">
                <option value="" selected>
                    Filtro por sección
                </option>
                <option :value="section.id" v-for="section in config.articles.filter.array_sections"  :key="section.id" v-text="section.nomenclature + '-' + section.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Canal</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_channel" :name="'select_channel'" :id="'select_channel'" data-style="select-lightgreen" @change="getProjectSelect">
                <option value="" selected>
                    Filtro por canal
                </option>
                <option :value="channel.id" v-for="channel in config.articles.filter.array_channels" :key="channel.id" v-text="channel.nomenclature + '-' + channel.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Proyecto</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_project" :name="'select_project'" :id="'select_project'" data-style="select-lightgreen" @change="getChaptersSelect">
                <option value="" selected>
                    Filtro por proyecto
                </option>
                <option :value="project.id" v-for="project in config.articles.filter.array_projects" :key="project.id" v-text="project.nomenclature + '-' + project.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Capítulo</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_chapter" :name="'select_chapter'" :id="'select_chapter'" data-style="select-lightgreen" @change="getBatchsSelect">
                <option value="" selected>
                    Filtro por capítulo
                </option>
                <option :value="chapter.id" v-for="chapter in config.articles.filter.array_chapters" :key="chapter.id" v-text="chapter.nomenclature + '-' + chapter.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Lote</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_batch" :name="'select_batch'" :id="'select_batch'" data-style="select-lightgreen" @change="getArticlesSelect">
                <option value="" selected>
                    Filtro por lote
                </option>
                <option :value="batch.id" v-for="batch in config.articles.filter.array_batchs" :key="batch.id" v-text="batch.nomenclature + '-' + batch.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Artículo</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_article" :name="'select_article'" :id="'select_article'" data-style="select-lightgreen">
                <option value="" selected>
                    Filtro por artículo
                </option>
                <option :value="article.id" v-for="article in config.articles.filter.array_articles"  :key="article.id" v-text="article.name" ></option>
            </select>
        </div>

        <!--<div class="mx-2 col-2 mt-5"></div>-->

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Fecha desde</span>
            <Calendar class="w-100 select-filter input-custom-calendar mt-3" v-model="date_from" inputId="date_from" autocomplete="off" dateFormat="dd-mm-yy" />
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Fecha hasta</span>
            <Calendar class="w-100 select-filter input-custom-calendar mt-3" v-model="date_to" inputId="date_to" autocomplete="off" dateFormat="dd-mm-yy"  />
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Consultor</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_consultant" :name="'select_consultant'" :id="'select_consultant'" data-style="select-lightgreen">
                <option value="" selected>
                    Selecciona un consultor
                </option>
                <option :value="user.id" v-for="user in proposals.array_users" :key="user.id" v-text="user.name + ' ' + user.surname"></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Ordenar por</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_sort_by" :name="'select_sort_by'" :id="'select_sort_by'" data-style="select-lightgreen">
                <option :value="1">Artículo</option>
                <option :value="2">Provincia</option>
                <option :value="3">Fecha de insercción</option>
                <option :value="4">Cliente</option>
                <option :value="5">Consultor</option>
                <option :value="6">Tipo de factura</option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-auto">
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_exchange" :name="'select_exchange'" :id="'select_exchange'" data-style="select-lightgreen">
                <option :value="1">Excluyendo intercambios</option>
                <option :value="2">Todas</option>
                <option :value="3">Solo intercambios</option>
            </select>
        </div>

        <div class="mx-2 col-12 d-flex mt-10">
            <button type="submit" class="btn bg-azul color-white px-35 font-weight-bolder">Generar informe</button>
        </div>
    </div>
    <Divider class="my-15" />
    <div class="col-12 d-flex flex-wrap mt-6">
        <div class="col-12 flex-wrap justify-content-between">
            <h3 class="color-blue my-auto">Resultados</h3>
        </div>
    </div>
    <div class="col-12 mt-15">
        <table width="100%" cellpadding="2" cellspacing="1">
            <thead class="custom-columns-datatable">
                <tr class="">
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 25px;"><span></span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>ID</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 125px;"><span>CLIENTE</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>ANUNCIANTE</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>ORDEN</span></th>
                    <th tabindex="0" class="pb-3" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>ARTÍCULO</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>TIPO</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>F</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>IMPORTE</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>CONSULTOR</span></th>
                </tr>
            </thead>
            <tbody>  
                <tr class="row-product bg-white ">
                    <td class="pl-3 py-5 text-dark text-align-center">1</td>
                    <td class="text-align-center text-dark">17228</td>
                    <td class="text-align-center text-dark">Arena Media Comunications España, SA</td>
                    <td class="text-align-center text-dark">ECKES GRANINI IBERICA</td>
                    <td class="text-align-center text-gray">39179</td>
                    <td class="text-dark">001797: <br>Manifone Iberia, SL</td>
                    <td class="text-align-center text-gray">N</td>
                    <td class="text-align-center text-gray">P</td>
                    <td class="text-align-center text-gray">1.275,00</td>
                    <td class="text-align-center text-gray">081</td>
                </tr>   
                <tr class="row-product bg-white ">
                    <td class="pl-3 py-5 text-dark text-align-center">1</td>
                    <td class="text-align-center text-dark">17228</td>
                    <td class="text-align-center text-dark">Arena Media Comunications España, SA</td>
                    <td class="text-align-center text-dark">ECKES GRANINI IBERICA</td>
                    <td class="text-align-center text-gray">39179</td>
                    <td class="text-dark">001797: <br>Manifone Iberia, SL</td>
                    <td class="text-align-center text-gray">N</td>
                    <td class="text-align-center text-gray">P</td>
                    <td class="text-align-center text-gray">1.275,00</td>
                    <td class="text-align-center text-gray">081</td>
                </tr>   
                <tr class="row-product bg-white ">
                    <td class="pl-3 py-5 text-dark text-align-center">1</td>
                    <td class="text-align-center text-dark">17228</td>
                    <td class="text-align-center text-dark">Arena Media Comunications España, SA</td>
                    <td class="text-align-center text-dark">ECKES GRANINI IBERICA</td>
                    <td class="text-align-center text-gray">39179</td>
                    <td class="text-dark">001797: <br>Manifone Iberia, SL</td>
                    <td class="text-align-center text-gray">N</td>
                    <td class="text-align-center text-gray">P</td>
                    <td class="text-align-center text-gray">1.275,00</td>
                    <td class="text-align-center text-gray">081</td>
                </tr>   
                <tr class="tr-total-datatable ">
                    <td colspan="9" class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                    <td  class="text-align-center"><span class="font-weight-bolder">2.895,00€</span></td>
                </tr>    
            </tbody>
        </table>
    </div>
</template>

<script>
    import { mapMutations, mapActions, mapState } from "vuex";

    import AddButtonComponent from "../../../Partials/AddButtonComponent.vue";
    import Calendar from 'primevue/calendar';
    import Divider from 'primevue/divider';
    import Chart from 'primevue/chart';

    export default {
        name: "TableComponentOption2",
        components: {
            AddButtonComponent,
            Calendar,
            Divider,
            Chart
        },
        data() {
            return {
                publicPath: window.location.origin,
                select_department: '',
                select_section: '',
                select_channel: '',
                select_project: '',
                select_chapter: '',
                select_batch: '',
                select_article: '',
                date_from: '',
                date_to: '',
                select_consultant: '',  
                select_sort_by: '1',  
                select_exchange: '2',  
            };
        },
        computed: {
            ...mapState(["errors", "config", "proposals"]),
        },
        mounted() {
            this.getUsers(1);
            var params = {
                type: 1
            }
            this.getDepartments(params);
            this.getNow();
        },
        methods: {
            ...mapActions(["getUsers", "getDepartments", "getSections", "getChannels", "getProjects", "getChapters", "getBatchs", "getArticles"]),
            ...mapMutations(["changeViewStatusReports"]),
            //Consultar fecha actual
            getNow() {
                const today = new Date();
                var day = today.getDate();
                if(day < 10){
                    day = '0' + today.getDate();
                }
                var month = (today.getMonth()+1);
                if(month < 10){
                    month = '0' + (today.getMonth()+1)
                }
                const date = day + '-' + month + '-' + today.getFullYear();
                this.date_from = date;
                this.date_to = date;
            },
            getSectionSelect(){
                this.select_section = '',
                this.select_channel = '',
                this.select_project = '',
                this.select_chapter = '',
                this.select_batch = '',
                this.select_article = '',
                this.amount = '';
                this.date = [];
                this.show_amount_dates = false;
                var params = {
                    type: 1,
                    select_articles_department: this.select_department
                }
                this.getSections(params);
            },
            getChannelSelect(){
                this.select_channel = '',
                this.select_project = '',
                this.select_chapter = '',
                this.select_batch = '',
                this.select_article = '',
                this.amount = '';
                this.date = [];
                this.show_amount_dates = false;
                var params = {
                    type: 1,
                    select_articles_section: this.select_section
                }
                this.getChannels(params);
            },
            getProjectSelect(){
                this.select_project = '',
                this.select_chapter = '',
                this.select_batch = '',
                this.select_article = '',
                this.amount = '';
                this.date = [];
                this.show_amount_dates = false;
                var params = {
                    type: 1,
                    select_articles_channel: this.select_channel
                }
                this.getProjects(params);
            },
            getChaptersSelect(){
                this.select_chapter = '',
                this.select_batch = '',
                this.select_article = '',
                this.amount = '';
                this.date = [];
                this.show_amount_dates = false;
                var params = {
                    type: 1,
                    select_articles_project: this.select_project
                }
                this.getChapters(params);
            },
            getBatchsSelect(){
                this.select_batch = '',
                this.select_article = '',
                this.amount = '';
                this.date = [];
                this.show_amount_dates = false;
                var params = {
                    type: 1,
                    select_articles_chapter: this.select_chapter
                }
                this.getBatchs(params);
            },
            getArticlesSelect(){
                this.select_article = '',
                this.amount = '';
                this.date = [];
                this.show_amount_dates = false;
                var params = {
                    type: 1,
                    select_articles_batch: this.select_batch
                }
                this.getArticles(params);
            },
        }
    };
</script>