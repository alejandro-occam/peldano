<template>
    <div class="row">
        <div class="col-12 d-flex flex-wrap justify-content-between">
            <AddButtonComponent
                @click.native="changeShowViewCalendar(2)"
                :columns="'px-4 ml-auto mr-7'"
                :text="'Exportar'"
                :id="'btn_export'"
                :src="'/media/custom-imgs/icono_btn_exportar.svg'"
                :width="16"
                :height="16"
            />
            <AddButtonComponent
                @click.native="openFormModal()"
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
            <select class="form-control bg-gray text-dark select-custom select-filter col-2 mx-2" :name="'select_articles_filter_sectors'" :id="'select_articles_filter_sectors'" v-model="select_articles_filter_sectors" data-style="select-lightgreen" @change="getBrandsSelect">
                <option value="" selected>
                    Filtro por sector
                </option>
                <option :value="sector.id" v-for="sector in config.articles.filter.array_sectors"  :key="sector.id" v-text="sector.name" ></option>
            </select>
            <select class="form-control bg-gray text-dark select-custom select-filter col-2 mx-2" :name="'select_articles_filter_brands'" :id="'select_articles_filter_brands'" v-model="select_articles_filter_brands" data-style="select-lightgreen" @change="getProductsSelect">
                <option value="" selected>
                    Filtro por marca
                </option>
                <option :value="brand.id" v-for="brand in config.articles.filter.array_brands"  :key="brand.id" v-text="brand.name" ></option>
            </select>
            <select class="form-control bg-gray text-dark select-custom select-filter col-2 mx-2" :name="'select_articles_filter_products'" :id="'select_articles_filter_products'" v-model="select_articles_filter_products" data-style="select-lightgreen" @change="reloadList">
                <option value="" selected>
                    Filtro por producto
                </option>
                <option :value="product.id" v-for="product in config.articles.filter.array_products"  :key="product.id" v-text="product.name" ></option>
            </select>
            <button v-if="this.status == 0" class="purple-border btn mr-4 font-weight-bold d-flex py-2 ml-2" @click="this.changeStatus(1)">
                <div class="purple-circle mr-auto my-auto">
                    <div class="white-circle-purple"></div>
                </div>
                <span class="px-5 my-auto">Mostrar todo</span>
            </button>
            <button v-else class="bg-purple btn mr-4 font-weight-bold color-white d-flex py-2 ml-2" @click="this.changeStatus(0)">
                <div class="white-circle mr-auto my-auto">
                    <div class="purple-circle-white"></div>
                </div>
                <span class="px-5 my-auto">Mostrar todo</span>
            </button>
        </div>
        <div class="col-12  mt-7">
            <div
                class="datatable datatable-bordered datatable-head-custom"
                id="list_articles"
                style="width: 100%"
            ></div>
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
                select_articles_filter_sectors: '',
                select_articles_filter_brands: '',
                select_articles_filter_products: '',
                status: 0,
                search_articles: '',
                datatable: null
            };
        },
        methods: {
            ...mapActions(["getSectors", "getBrands", "getProducts", "getInfoArticle"]),
            ...mapMutations(["controlFormArticles"]),
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
                        me.datatable.setDataSourceParam('select_articles_filter_sectors', me.select_articles_filter_sectors);
                        me.datatable.setDataSourceParam('select_articles_filter_brands', me.select_articles_filter_brands);
                        me.datatable.setDataSourceParam('select_articles_filter_products', me.select_articles_filter_products);
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
                                        row.calendar_name +
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
                                return (
                                        '<span class="switch switch-outline switch-icon switch-success"><label class="mx-auto"><input type="checkbox" checked="checked" name="select"/><span></span></label></span>'
                                    );
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
                                html += '<button type="button" class="btn p-0 mx-2 btn-edit" data-id="' + row.id + '"><img class="edit-hover" src="/media/custom-imgs/icono_tabla_editar.svg" height="30px" width="auto"></button>';
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

                this.datatable.setDataSourceParam('select_articles_filter_sectors', this.select_articles_filter_sectors);
                this.datatable.setDataSourceParam('select_articles_filter_brands', this.select_articles_filter_brands);
                this.datatable.setDataSourceParam('select_articles_filter_products', this.select_articles_filter_products);
                this.datatable.setDataSourceParam('status', this.status);
                $('#list_articles').KTDatatable('load');
            },
            reloadList(){
                this.datatable.setDataSourceParam('select_articles_filter_sectors', this.select_articles_filter_sectors);
                this.datatable.setDataSourceParam('select_articles_filter_brands', this.select_articles_filter_brands);
                this.datatable.setDataSourceParam('select_articles_filter_products', this.select_articles_filter_products);
                this.datatable.setDataSourceParam('status', this.status);
                $('#list_articles').KTDatatable('load');
            },
            getBrandsSelect(){
                this.select_articles_filter_brands = '';
                this.select_articles_filter_products = '';
                var params = {
                    type: 1,
                    select_articles_sectors: this.select_articles_filter_sectors
                }
                this.getBrands(params);
                this.reloadList();
            },
            getProductsSelect(){
                this.select_articles_filter_products = '';
                var params = {
                    type: 1,
                    select_articles_brands: this.select_articles_filter_brands
                }
                this.getProducts(params);
                this.reloadList();
            },
            changeStatus(status){
                this.status = status;
                this.datatable.setDataSourceParam('select_articles_filter_sectors', this.select_articles_filter_sectors);
                this.datatable.setDataSourceParam('select_articles_filter_brands', this.select_articles_filter_brands);
                this.datatable.setDataSourceParam('select_articles_filter_products', this.select_articles_filter_products);
                this.datatable.setDataSourceParam('status', this.status);
                $('#list_articles').KTDatatable('load');
            },
        },
        computed: {
                ...mapState(["errors", "config"]),
        },
        mounted() {
            var params = {
                type: 1,
                select_articles_areas: 0
            }
            this.getSectors(params);
        },
        watch: {
            '$store.state.config.articles.search_articles': function() {
                $('#search_articles').val(this.config.articles.search_articles);
                this.reloadList();
            }
        }
    };
    </script>