<template>
    <div id="div_print">
        <div class="row m-0" >
            <div class="col-12 d-flex flex-wrap justify-content-between" >
                <h3 class="color-blue">Exportar calendario</h3>
                <AddButtonComponent
                        @click.native="changeShowViewArticles(1)"
                        :columns="'px-4 ml-auto'"
                        :text="'Volver'"
                        :id="'btn_add_user'"
                        :src="'/media/custom-imgs/flecha_btn_volver.svg'"
                        :width="16"
                        :height="16"
                    />
                <AddButtonComponent
                    @click.native="downloadFile()"
                    :columns="'px-4 mx-7'"
                    :text="'Exportar'"
                    :id="'btn_export'"
                    :src="'/media/custom-imgs/icono_btn_exportar.svg'"
                    :width="16"
                    :height="16"
                />
                <AddButtonComponent
                    @click.native="printPage()"
                    :columns="'px-4'"
                    :text="'Imprimir'"
                    :id="'btn_print_articles_page'"
                    :src="'/media/custom-imgs/icono_btn_annadir_numero.svg'"
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
                    @CustomEventInputChanged="reloadListSearch"
                />
                <select class="form-control bg-gray text-dark select-custom select-filter col-2 mx-2" :name="'select_articles_filter_sectors'" :id="'select_articles_filter_sectors'" v-model="select_articles_filter_sectors" data-style="select-lightgreen" @change="getBrandsSelect">
                    <option value="0" selected>
                        Filtro por sector
                    </option>
                    <option :value="sector.id" v-for="sector in config.articles.filter.array_sectors"  :key="sector.id" v-text="sector.name" ></option>
                </select>
                <select class="form-control bg-gray text-dark select-custom select-filter col-2 mx-2" :name="'select_articles_filter_brands'" :id="'select_articles_filter_brands'" v-model="select_articles_filter_brands" data-style="select-lightgreen" @change="getProductsSelect">
                    <option value="0" selected>
                        Filtro por marca
                    </option>
                    <option :value="brand.id" v-for="brand in config.articles.filter.array_brands"  :key="brand.id" v-text="brand.name" ></option>
                </select>
                <select class="form-control bg-gray text-dark select-custom select-filter col-2 mx-2" :name="'select_articles_filter_products'" :id="'select_articles_filter_products'" v-model="select_articles_filter_products" data-style="select-lightgreen" @change="reloadList">
                    <option value="0" selected>
                        Filtro por producto
                    </option>
                    <option :value="product.id" v-for="product in config.articles.filter.array_products"  :key="product.id" v-text="product.name" ></option>
                </select>
            </div>
            <div class="col-12 mt-7" id="div_print2">
                <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-scroll datatable-loaded" id="list_calendars" style="width: 100%;">
                    <table class="datatable-table" style="display: block;">
                        <thead class="datatable-head">
                            <tr class="datatable-row" style="left: 0px;">
                                <th data-field="#calendar" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >Referencia</span>
                                </th>
                                <th data-field="#number" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >Publicación</span>
                                </th>
                                <th data-field="#title" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >Nombre</span>
                                </th>
                                <th data-field="#drafting" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span>Nombre eng</span>
                                </th>
                                <th data-field="#commercial" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >Exento</span>
                                </th>
                                <th data-field="#output" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >PVP</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="datatable-body ps" v-html="config.articles.html_articles">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
     import { mapMutations, mapActions, mapState } from "vuex";
     import AddButtonComponent from "../../Partials/AddButtonComponent.vue";
     import SearchComponent from "./SearchComponent.vue";


     export default {
        name: "TableExportsComponent",
        components: {
            AddButtonComponent,
            SearchComponent
        },
        data() {
            return {
                publicPath: window.location.origin,
                select_articles_filter_sectors: '0',
                select_articles_filter_brands: '0',
                select_articles_filter_products: '0',
                search_articles:''
            };
        },
        methods: {
            ...mapMutations(["changeShowViewArticles"]),
            ...mapActions(["listArticlesToExport", "getProducts", "getBrands"]),
            downloadFile(){
                window.open(this.publicPath + "/admin/download_list_articles_csv/" + this.select_calendar_filter,"_self")
            },
            reloadList(){
                var params = {
                    select_articles_filter_sectors: this.select_articles_filter_sectors,
                    select_articles_filter_brands: this.select_articles_filter_brands,
                    select_articles_filter_products: this.select_articles_filter_products,
                    search_articles: this.search_articles
                }
                this.listArticlesToExport(params);
            },
            printPage(){
                /*$("#list_calendars").print.printThis({
                    importCSS: true,
                    loadCSS: "/public/css/custom-back.css",
                    printContainer: true,
                    formValues: true 
                });*/
                // Get HTML to print from element
                const prtHtml = document.getElementById('div_print').innerHTML;

                // Get all stylesheets HTML
                let stylesHtml = '';
                for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
                    stylesHtml += node.outerHTML;
                }

                // Open the print window
                const WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');

                WinPrint.document.write(`<!DOCTYPE html>
                            <head>
                                ${stylesHtml}
                            </head>
                            <body>
                                ${prtHtml}
                            </body>
                            </html>`);

                setTimeout(() => {
                            WinPrint.document.close();
                            WinPrint.focus();
                            WinPrint.print();
                            WinPrint.close();
                            }, 500);
                                // cssText will be a string containing the text of the file
                            
                        

                //WinPrint.focus();
                //WinPrint.print();
               
            },
            getBrandsSelect(){
                this.select_articles_filter_brands = '0';
                this.select_articles_filter_products = '0';
                var params = {
                    type: 1,
                    select_articles_sectors: this.select_articles_filter_sectors,
                }
                this.getBrands(params);
                this.reloadList();
            },
            getProductsSelect(){
                this.select_articles_filter_products = '0';
                var params = {
                    type: 1,
                    select_articles_brands: this.select_articles_filter_brands
                }
                this.getProducts(params);
                this.reloadList();
            },
            reloadListSearch ( data ) {
                console.log(data);
                this.search_articles = data;
                this.reloadList();
            }
        },
        computed: {
            ...mapState(["config"])
        },
        mounted() {
            var params = {
                select_articles_filter_sectors: this.select_articles_filter_sectors,
                select_articles_filter_brands: this.select_articles_filter_brands,
                select_articles_filter_products: this.select_articles_filter_products
            }
            this.listArticlesToExport(params);
        },
        watch: {
            '$store.state.config.articles.search_articles': async function() {
                $('#search_articles').val(this.config.articles.search_articles);
                this.reloadList();
            }
        }
    };
</script>