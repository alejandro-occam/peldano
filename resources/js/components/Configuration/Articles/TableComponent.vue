<template>
    <div class="row">
        <div class="col-12 d-flex flex-wrap justify-content-between">
            <AddButtonComponent
                v-on:click="changeShowViewArticles(2)"
                :columns="'px-4 ml-auto mr-7'"
                :text="'Exportar'"
                :id="'btn_export'"
                :src="'/media/custom-imgs/icono_btn_exportar.svg'"
                :width="16"
                :height="16"
            />
            <AddButtonComponent
                v-on:click="openFormModal()"
                :columns="'px-4'"
                :text="'Añadir artículo'"
                :id="'btn_add_article'"
                :src="'/media/custom-imgs/icono_btn_annadir_articulo.svg'"
                :width="25"
                :height="25"
            />
        </div>
        <div class="col-12 d-flex flex-wrap mt-6">
            <SearchComponent
                :columns="'col-2 mr-2'"
                :model="'articles'"
                :placeholder="'Buscar artículo'"
                :model2="'search_articles'"
            />
            <select class="form-control bg-gray text-dark select-custom select-filter col-2 mx-2" :name="'select_articles_filter_departments'" :id="'select_articles_filter_departments'" v-model="select_articles_filter_departments" data-style="select-lightgreen" @change="getSectionsSelect">
                <option value="" selected>
                    Filtro por departamento
                </option>
                <option :value="department.id" v-for="department in config.articles.filter.array_departments" :key="department.id" v-text="department.nomenclature + '-' + department.name" ></option>
            </select>
            <select class="form-control bg-gray text-dark select-custom select-filter col-2 mx-2" :name="'select_articles_filter_sections'" :id="'select_articles_filter_sections'" v-model="select_articles_filter_sections" data-style="select-lightgreen" @change="getChannelsSelect">
                <option value="" selected>
                    Filtro por sección
                </option>
                <option :value="section.id" v-for="section in config.articles.filter.array_sections" :key="section.id" v-text="section.nomenclature + '-' + section.name" ></option>
            </select>
            <select class="form-control bg-gray text-dark select-custom select-filter col-2 mx-2" :name="'select_articles_filter_channels'" :id="'select_articles_filter_channels'" v-model="select_articles_filter_channels" data-style="select-lightgreen" @change="getProjectsSelect">
                <option value="" selected>
                    Filtro por canal
                </option>
                <option :value="channel.id" v-for="channel in config.articles.filter.array_channels" :key="channel.id" v-text="channel.nomenclature + '-' + channel.name" ></option>
            </select>
            <select class="form-control bg-gray text-dark select-custom select-filter col-2 mx-2" :name="'select_articles_filter_projects'" :id="'select_articles_filter_projects'" v-model="select_articles_filter_projects" data-style="select-lightgreen" @change="getChaptersSelect">
                <option value="" selected>
                    Filtro por proyecto
                </option>
                <option :value="project.id" v-for="project in config.articles.filter.array_projects" :key="project.id" v-text="project.nomenclature + '-' + project.name" ></option>
            </select>
            <select class="form-control bg-gray text-dark select-custom select-filter col-2 mr-2 mt-4" :name="'select_articles_filter_chapters'" :id="'select_articles_filter_chapters'" v-model="select_articles_filter_chapters" data-style="select-lightgreen" @change="getBatchsSelect">
                <option value="" selected>
                    Filtro por capítulo
                </option>
                <option :value="chapter.id" v-for="chapter in config.articles.filter.array_chapters" :key="chapter.id" v-text="chapter.nomenclature + '-' + chapter.name" ></option>
            </select>
            <select class="form-control bg-gray text-dark select-custom select-filter col-2 mx-2 mt-4" :name="'select_articles_filter_batchs'" :id="'select_articles_filter_batchs'" v-model="select_articles_filter_batchs" data-style="select-lightgreen" @change="reloadList">
                <option value="" selected>
                    Filtro por lote
                </option>
                <option :value="batch.id" v-for="batch in config.articles.filter.array_batchs" :key="batch.id" v-text="batch.nomenclature + '-' + batch.name" ></option>
            </select>
            <button v-if="this.status == 0" class="purple-border btn mr-4 font-weight-bold d-flex py-2 ml-2 mt-4" @click="this.changeStatus(1)">
                <div class="purple-circle mr-auto my-auto">
                    <div class="white-circle-purple"></div>
                </div>
                <span class="px-5 my-auto">Mostrar todo</span>
            </button>
            <button v-else class="bg-purple btn mr-4 font-weight-bold color-white d-flex py-2 ml-2 mt-4" @click="this.changeStatus(0)">
                <div class="white-circle mr-auto my-auto">
                    <div class="purple-circle-white"></div>
                </div>
                <span class="px-5 my-auto">Mostrar todo</span>
            </button>
        </div>
        <div class="col-12  mt-7">
            <div class="datatable datatable-bordered datatable-head-custom" id="list_articles" style="width: 100%"></div>
        </div>
    </div>
</template>

<script>
    import { mapMutations, mapActions, mapState } from "vuex";

    import SearchComponent from "../../Partials/SearchComponent.vue";
    import AddButtonComponent from "../../Partials/AddButtonComponent.vue";

    export default {
        name: "TableComponent",
        components: {
            SearchComponent,
            AddButtonComponent,
        },
        data() {
            return {
                publicPath: window.location.origin,
                select_articles_filter_departments: '',
                select_articles_filter_sections: '',
                select_articles_filter_channels: '',
                select_articles_filter_projects: '',
                select_articles_filter_chapters: '',
                select_articles_filter_batchs: '',
                status: 0,
                search_articles: '',
                datatable: null
            };
        },
        methods: {
            ...mapActions(["getDepartments", "getSections", "getChannels", "getProjects", "getChapters", "getBatchs", "getInfoArticle"]),
            ...mapMutations(["controlFormArticles", "changeShowViewArticles"]),
            openFormModal(){
                this.controlFormArticles(0)
                $('#modal_form_article').modal('show');
            },
            listArticles(type) {
                let me = this;
                $("#list_articles").KTDatatable("destroy");
                $("#list_articles").KTDatatable("init");
                if(type == 1 || type == undefined){
                    if(me.datatable != null){
                        me.datatable.setDataSourceParam('select_articles_filter_departments', me.select_articles_filter_departments);
                        me.datatable.setDataSourceParam('select_articles_filter_sections', me.select_articles_filter_sections);
                        me.datatable.setDataSourceParam('select_articles_filter_channels', me.select_articles_filter_channels);
                        me.datatable.setDataSourceParam('select_articles_filter_projects', me.select_articles_filter_projects);
                        me.datatable.setDataSourceParam('select_articles_filter_chapters', me.select_articles_filter_chapters);
                        me.datatable.setDataSourceParam('select_articles_filter_batchs', me.select_articles_filter_batchs);
                    }
                }
                this.datatable = $("#list_articles").KTDatatable({
                    data: {
                        type: "remote",
                        source: {
                            read: {
                                url:
                                    this.publicPath +
                                    "/admin/list_articles",
                                headers: {
                                    "X-CSRF-TOKEN": $(
                                        'meta[name="csrf-token"]'
                                    ).attr("content"),
                                },
                                method: 'POST',
                                params: {
                                    select_articles_filter_sectors: '',
                                    select_articles_filter_brands: '',
                                    select_articles_filter_products: '',
                                    status: 0
                                }
                            },
                        },
                        pageSize: 10,
                        serverPaging: !0,
                        serverFiltering: !0,
                        serverSorting: !0,
                    },
                    layout: {
                        scroll: true,
                        customScrollbar: true,
                        scrollX: true,
                        footer: !1,
                        spinner: {
                            color: "#FFF",
                        },
                    },
                    sortable: !0,
                    pagination: !0,
                    search: {
                        input: $('#search_articles'),
                        key: "search_articles",
                    },
                    translate: {
                        records: {
                            processing: "Cargando...",
                            noRecords: "Sin resultados",
                        },
                        toolbar: {
                            pagination: {
                                items: {
                                    info: "Mostrando {{start}} - {{end}} de {{total}} resultados",
                                },
                            },
                        },
                    },
                    rows: {
                        autoHide: false,
                    },
                    columns: [
                        {
                            field: "#calendar",
                            title: "Referencia",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-dark">' +
                                        row.id_sage +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#publication",
                            title: "Publicación",
                            sortable: !1,
                            textAlign: "center",
                            width: 200,
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-dark">' +
                                        row.publication +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#name",
                            title: "Nombre",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-dark">' +
                                        row.name +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#english_name",
                            title: "Nombre Eng",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-gray font-weight-bold">' +
                                        row.english_name +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#commercial",
                            title: "Exento",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                if(row.is_exempt){
                                    return (
                                        '<span class="switch switch-outline switch-icon switch-success"><label class="mx-auto"><input data-id="'+row.id+'" class="switch-exempt" input type="checkbox" checked="checked" name="select"/><span></span></label></span>'
                                    );
                                }else{
                                    return (
                                        '<span class="switch switch-outline switch-icon switch-success"><label class="mx-auto"><input data-id="'+row.id+'" class="switch-exempt" type="checkbox" name="select"/><span></span></label></span>'
                                    );
                                }
                               
                            },
                        },
                        {
                            field: "#pvp",
                            title: "PVP",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-gray font-weight-bold">' +
                                            me.$utils.numberWithDotAndComma(me.$utils.roundAndFix(row.pvp)) +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#",
                            title: "",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                var html ='<div>';
                                //html += '<button type="button" class="btn p-0 mx-2 btn-edit" data-id="' + row.id + '"><img class="edit-hover" src="/media/custom-imgs/icono_tabla_editar.svg" height="30px" width="auto"></button>';
                                html += '<button type="button" class="btn p-0 mx-2 btn-delete" data-id="' + row.id + '"><img class="edit-hover" src="/media/custom-imgs/icono_tabla_eliminar.svg" height="30px" width="auto"></button></div>';
                                return html;
                            },
                        },
                    ],
                });
    
                $("#list_articles").on("click", ".btn-edit", function () {
                    var id = $(this).data("id");
                    me.controlFormArticles(1);
                    me.getInfoArticle(id);
                    $('#modal_form_article').modal('show');
                });

                $("#list_articles").on("click", ".btn-delete", function () {
                    var id = $(this).data("id");
                    me.getInfoArticle(id);
                    $("#modal_delete_article").modal("show");           
                });

                $("#list_articles").on("click", ".switch-exempt", function () {
                    var id = $(this).data("id");
                    me.changeExempt(id);
                });
               

                this.datatable.setDataSourceParam('select_articles_filter_sectors', this.select_articles_filter_sectors);
                this.datatable.setDataSourceParam('select_articles_filter_brands', this.select_articles_filter_brands);
                this.datatable.setDataSourceParam('select_articles_filter_products', this.select_articles_filter_products);
                this.datatable.setDataSourceParam('status', this.status);
                $('#list_articles').KTDatatable('load');
            },
            reloadList(){
                this.datatable.setDataSourceParam('select_articles_filter_departments', this.select_articles_filter_departments);
                this.datatable.setDataSourceParam('select_articles_filter_sections', this.select_articles_filter_sections);
                this.datatable.setDataSourceParam('select_articles_filter_channels', this.select_articles_filter_channels);
                this.datatable.setDataSourceParam('select_articles_filter_projects', this.select_articles_filter_projects);
                this.datatable.setDataSourceParam('select_articles_filter_chapters', this.select_articles_filter_chapters);
                this.datatable.setDataSourceParam('select_articles_filter_batchs', this.select_articles_filter_batchs);
                this.datatable.setDataSourceParam('status', this.status);
                $('#list_articles').KTDatatable('load');
            },
            getSectionsSelect(){
                this.select_articles_filter_sections = '';
                this.select_articles_filter_channels = '';
                this.select_articles_filter_projects = '';
                this.select_articles_filter_chapters = '';
                this.select_articles_filter_batchs = '';
                var params = {
                    type: 1,
                    select_articles_department: this.select_articles_filter_departments
                }
                this.getSections(params);
                this.reloadList();
            },
            getChannelsSelect(){
                this.select_articles_filter_channels = '';
                this.select_articles_filter_projects = '';
                this.select_articles_filter_chapters = '';
                this.select_articles_filter_batchs = '';
                var params = {
                    type: 1,
                    select_articles_section: this.select_articles_filter_sections
                }
                this.getChannels(params);
                this.reloadList();
            },
            getProjectsSelect(){
                this.select_articles_filter_projects = '';
                this.select_articles_filter_chapters = '';
                this.select_articles_filter_batchs = '';
                var params = {
                    type: 1,
                    select_articles_channel: this.select_articles_filter_channels
                }
                this.getProjects(params);
                this.reloadList();
            },
            getChaptersSelect(){
                this.select_articles_filter_chapters = '';
                this.select_articles_filter_batchs = '';
                var params = {
                    type: 1,
                    select_articles_project: this.select_articles_filter_projects
                }
                this.getChapters(params);
                this.reloadList();
            },
            getBatchsSelect(){
                this.select_articles_filter_batchs = '';
                var params = {
                    type: 1,
                    select_articles_chapter: this.select_articles_filter_chapters
                }
                this.getBatchs(params);
                this.reloadList();
            },
            changeStatus(status){
                this.status = status;
                this.datatable.setDataSourceParam('select_articles_filter_departments', this.select_articles_filter_departments);
                this.datatable.setDataSourceParam('select_articles_filter_sections', this.select_articles_filter_sections);
                this.datatable.setDataSourceParam('select_articles_filter_channels', this.select_articles_filter_channels);
                this.datatable.setDataSourceParam('select_articles_filter_projects', this.select_articles_filter_projects);
                this.datatable.setDataSourceParam('select_articles_filter_chapters', this.select_articles_filter_chapters);
                this.datatable.setDataSourceParam('select_articles_filter_batchs', this.select_articles_filter_batchs);
                this.datatable.setDataSourceParam('status', this.status);
                $('#list_articles').KTDatatable('load');
            },
            //Actualizar exento de IVA de un artículo
            changeExempt(id){
                let params = {
                    id: id,
                };

                axios
                    .post("/admin/change_exempt", params)
                    .then((response) => {
                        $('#list_articles').KTDatatable('load');
                    })
                    .catch(function (error) {
                        console.error(error.response);
                        swal("", "Parece que ha habido un error, inténtalo de nuevo más tarde", "error");
                    });
            }
        },
        computed: {
                ...mapState(["errors", "config"]),
        },
        mounted() {
            var params = {
                type: 1,
            }
            this.getDepartments(params);
            this.listArticles(1);
        },
        watch: {
            '$store.state.config.articles.search_articles': function() {
                $('#search_articles').val(this.config.articles.search_articles);
                this.reloadList();
            }
        }
    };
</script>