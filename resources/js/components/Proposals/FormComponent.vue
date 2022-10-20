<template>
    <div>
        <div class="d-flex">
            <AddButtonComponent
                    :columns="'col-1 ml-auto'"
                    :text="'Volver'"
                    :id="'btn_add_user'"
                    :src="'/media/custom-imgs/flecha_btn_volver.svg'"
                    :width="16"
                    :height="16"
                    @click.native="changeViewStatusProposals(1)"
                />
        </div>
        <div class="col-12 pl-0 mt-15">
            <h3 class="color-blue">Datos del cliente</h3>
            <div class="my-5 col-12 row">
                <div class="input-group px-0 d-flex" v-if="this.select_company == '' && this.select_company_other_values == ''">
                    <div class="w-25">
                        <span class="w-25">Empresa o nombre y apellidos</span>
                        <div class="mt-2 select-filter">
                            <select class="form-control select2 select-filter" id="select_company" v-model="select_company">
                                <option :data-name="company.name" :value="company.id" v-for="company in proposals.array_companies" :key="company.id" v-text="company.name" ></option>
                            </select>
                        </div>
                    </div>
                    <div class="w-25 ml-10">
                        <span class="w-25">Otros (localidad, e-mail, cif/nif, tlf, cp)</span>
                        <div class="mt-2 select-filter">
                            <select class="form-control select2 select-filter" id="select_company_other_values" v-model="select_company_other_values">
                                <option :data-name="company.name" :value="company.id" v-for="company in proposals.array_companies" :key="company.id" v-text="company.email + ' - ' + company.nif" ></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="input-group px-0 d-flex mt-5" v-else>
                    <div class="bg-span-gray py-2 w-25 br-5">
                        <span class="font-weight-bolder color-white ml-5 f-15 text-dark">{{ this.name_company }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-15"  v-if="this.select_company != '' || this.select_company_other_values != ''">
                <button type="button" class="btn bg-azul color-white px-5 font-weight-bolder" @click="this.openFormArticle()">
                    <img class="mr-2" width="24" height="24" src="/media/custom-imgs/icono_btn_annadir_articulo_blanco.svg" />
                    Añadir artículo
                </button>
            </div>
            <div class="mb-5 mt-15 col-12 row" v-if="proposals.proposal_obj.products[0].product_obj != null">
                <div>
                    <img class="mr-2" width="150" height="150" src="/media/custom-imgs/icono_ficha_ordenes.svg" />
                </div>
                <div>
                    <div class="ml-10">
                        <div><h2 class="text-dark">Propuesta 56528</h2></div>
                        <div class="f-20">
                            <span class="text-dark font-weight-bold">Cliente: <span class="color-dark-gray font-weight-bold">{{ name_company }}</span></span>
                        </div>
                        <div class="mt-8">
                            <div class="d-flex">
                                <div class="d-block mr-20">
                                    <div class="f-16 color-dark-gray font-weight-bolder">
                                        <span>FECHA</span>
                                    </div>
                                    <div class="f-15 text-dark">
                                        <span>{{ this.$utils.getNow() }}</span>
                                    </div>
                                </div>
                                <div class="d-block mx-20">
                                    <div class="f-16 color-dark-gray font-weight-bolder">
                                        <span>CONSULTOR</span>
                                    </div>
                                    <div class="f-15 text-dark">
                                        <span v-if="proposals.user_obj != null">{{ proposals.user_obj.name + ' ' + proposals.user_obj.surname }}</span>
                                    </div>
                                </div>
                                <div class="d-block mx-20">
                                    <div class="f-16 color-dark-gray font-weight-bolder">
                                        <span>SECTOR</span>
                                    </div>
                                    <div class="f-15 text-dark">
                                        <span v-if="proposals.proposal_obj.products[0].articles[0].sector_obj != null">{{ proposals.proposal_obj.products[0].articles[0].sector_obj.name }}</span>
                                    </div>
                                </div>
                                <div class="d-block ml-20">
                                    <div class="f-16 color-dark-gray font-weight-bolder">
                                        <span>ANUNCIANTE</span>
                                    </div>
                                    <div class="f-15 text-dark">
                                        {{ name_company }}
                                    </div>
                                </div>
                            </div>
                            <div style="float:left;">
                                <div class="d-flex border-blue-article mt-8">
                                    <div class="d-block mr-5 px-10 py-8">
                                        <div class="f-16 color-dark-gray text-align-center">
                                            <span>OFERTA</span>
                                        </div>
                                        <span class="p-input-icon-right w-100">
                                            <input v-model="offer" type="text" class="form-control discount bg-blue-light-white font-weight-bolder f-15 color-dark-gray not-border mt-3" style="width:150px;" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" v-on:change="changeValueBox(1)"/>
                                        </span>
                                    </div>
                                    <div class="subheader-separator subheader-separator-ver my-auto py-14 bg-gray-light"></div>
                                    <div class="d-block mx-5 px-10 py-8">
                                        <div class="f-16 color-dark-gray text-align-center">
                                            <span>DESCUENTO</span>
                                        </div>
                                        <span class="p-input-icon-right w-100">
                                            <input v-model="discount" type="text" class="form-control discount bg-blue-light-white font-weight-bolder f-15 color-dark-gray not-border mt-3" style="width:150px;" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0"  v-on:change="changeValueBox(2)"/>
                                            <img width="13" class="pi my-auto" src="/media/custom-imgs/icono_porcentaje_input.svg"/>
                                        </span>
                                    </div>
                                    <div class="subheader-separator subheader-separator-ver my-auto py-14 bg-gray-light"></div>
                                    <div class="d-block ml-5 px-10 py-8">
                                        <div class="f-16 color-dark-gray text-align-center">
                                            <span>TARIFA</span>
                                        </div>
                                        <div class="f-15 color-dark-gray font-weight-bolder px-8 py-2 mt-3">
                                            <span >{{ this.total }}€</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-15" v-if="proposals.proposal_obj.products[0].product_obj != null">
                <table width="100%" cellpadding="2" cellspacing="1">
                    <thead class="custom-columns-datatable">
						<tr>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>SERVICIOS</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>PVP</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>N</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" v-for="index in Number(proposals.proposal_obj.array_dates.length)" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>{{ proposals.proposal_obj.array_dates[index - 1] }}</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>TOTAL</span></th>
                        </tr>
					</thead>
                    <tbody>
                        <tr class="row-product">
                            <td class="py-2" :colspan="this.discount.length + 4">
                                <span class="ml-5">Web campingprofesional.com (W)</span>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" class="td-border-right"><span class="ml-5">BANNER 200 X 200</span></td>
                            <td valign="middle" class="td-border-right text-align-center"><span class="">210,00€</span></td>
                            <td valign="middle" class="td-border-right text-align-center"><span class="">6</span></td>
                            <td valign="middle" v-for="index in Number(this.discount.length)" class="td-border-right">
                                <div class="d-grid">
                                    <span  class="mx-2 bg-blue-light-white py-3 text-align-center my-2 br-5">1.260,00€</span>
                                </div>
                            </td>
                        </tr>
                        <tr class="row-product">
                            <td class="py-2" :colspan="this.discount.length + 4">
                                <span class="ml-5">Newsletter CPROF (N)</span>
                            </td>
                        </tr>
                        <tr >
                            <td valign="middle" class="td-border-right"><span class="ml-5">BANNER 150 X 150</span></td>
                            <td valign="middle" class="td-border-right text-align-center"><span class="">110,00€</span></td>
                            <td valign="middle" class="td-border-right text-align-center"><span class="">1</span></td>
                            <td valign="middle" v-for="index in Number(this.discount.length)" class="td-border-right">
                                <div class="d-grid">
                                    <span class="mx-2 bg-blue-light-white py-3 text-align-center my-2 br-5">1.260,00€</span>
                                    <span class="mx-2 bg-blue-light-white py-3 text-align-center my-2 br-5">1.260,00€</span>
                                    <span class="mx-2 bg-blue-light-white py-3 text-align-center my-2 br-5">1.260,00€</span>
                                    <span class="mx-2 bg-blue-light-white py-3 text-align-center my-2 br-5">1.260,00€</span>
                                    <span class="mx-2 bg-blue-light-white py-3 text-align-center my-2 br-5">1.260,00€</span>
                                </div>
                            </td>                            
                            <td valign="middle" class="td-border-right">
                                <div class="d-grid">
                                    <span class="mx-2 bg-blue-light-white py-3 text-align-center my-2 br-5">1.260,00€</span>
                                </div>
                            </td>   
                        </tr>
                        <tr class="tr-total-datatable">
                            <td class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                            <td class="text-align-center"><span class="font-weight-bolder">1.370,00€</span></td>
                            <td class="text-align-center"><span class="font-weight-bolder">7</span></td>
                            <td class="text-align-center" v-for="index in Number(this.discount.length)"><span class="font-weight-bolder">1.370,00€</span></td>
                            <td class="text-align-center"><span class="font-weight-bolder">1.370,00€</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <FormAddArticleComponent></FormAddArticleComponent>
</template>

<script>

import { mapMutations, mapState, mapActions } from "vuex";

import AddButtonComponent from "../Partials/AddButtonComponent.vue";
import FormAddArticleComponent from "./FormAddArticleComponent.vue";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

export default {
    name: "FormComponent",
    components: {
        AddButtonComponent,
        FormAddArticleComponent,
        DataTable,
        Column
    },
    data() {
        return {
            publicPath: window.location.origin,
            select_company: '',
            select_company_other_values: '',
            name_company: '',
            offer: 0,
            total: 0,
            discount: '0.00',
            fullname: ''
        };
    },
    computed: {
            ...mapState(["errors", "proposals"]),
    },
    methods: {
        ...mapMutations(["clearError", "changeViewStatusProposals", "changeValueIsChangeArticle"]),
        ...mapActions(["getCompanies"]),
        openFormArticle(){
            $('#modal_form_article_proposals').modal('show');
        },
        getNameCompany(id){
            let me = this;
            me.proposals.array_companies.forEach(function callback(value, index, array) {
                if(value.id == id){
                    me.name_company = value.name;
                }
            });
        },
        changeValueBox(type){
            if(type == 1){
                var difference = (this.proposals.proposal_obj.article.article_obj.pvp * this.proposals.proposal_obj.article.dates.length) - this.offer;
                this.discount = this.$utils.roundAndFix(difference / (this.proposals.proposal_obj.article.article_obj.pvp * this.proposals.proposal_obj.article.dates.length) * 100);

            }else{
                if(this.discount != 0){
                    var difference = ((100 - this.discount) / 100) * this.proposals.proposal_obj.article.article_obj.pvp * this.proposals.proposal_obj.article.dates.length;
                    this.offer = this.$utils.roundAndFix(difference);
                }
            }
        },
    },
    mounted() {
        let me = this;
        $('#select_company').select2({
            placeholder: "Selecciona una empresa"
        });
        $('#select_company_other_values').select2({
            placeholder: "Selecciona una empresa"
        });
        this.getCompanies();
        $('#select_company').on("change",function(){
            me.select_company = $('#select_company').val();
            me.getNameCompany(me.select_company);
        });
        $('#select_company_other_values').on("change",function(){
            me.select_company_other_values = $('#select_company_other_values').val();
            me.getNameCompany(me.select_company_other_values);
        });
    },
    created() {
        this.$watch(() =>'$store.state.proposals', (value) => {
            console.log('hola');
        })
    },
    watch: {
            '$store.state.errors.code': function() {
                if(this.errors.type_error == 'delete_user'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            $("#list_users").KTDatatable("reload");
                            $("#modal_delete_user").modal("hide");
                            swal("", "Usuario eliminado correctamente", "success");
                        }else if(this.errors.code == 1001){
                            swal("", "El usuario no existe", "warning");
                        }else{
                            swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                        }
                        this.clearError();
                    }
                }
            },
            '$store.state.proposals.proposal_obj.is_change': function() {
                let me = this;
                if(me.proposals.proposal_obj.is_change){
                    me.changeValueIsChangeArticle();
                    var total = 0;
                    me.proposals.proposal_obj.products.forEach(function callback(product, index, array) {
                        product.articles.forEach(function callback(article, index, array) {
                            total += Number(me.$utils.roundAndFix(article.article_obj.pvp * article.dates.length));
                        });
                    });
                    me.offer = total;
                    me.total = me.$utils.roundAndFix(total);
                }
            },
            /*'$store.state.proposals.proposal_obj.products[0].product_obj': function() {
                console.log('hola');
            },*/
            
        }
    
};
</script>