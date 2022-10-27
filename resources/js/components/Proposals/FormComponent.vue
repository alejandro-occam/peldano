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
            <div class="mt-15" v-if="this.select_company != '' || this.select_company_other_values != ''">
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
                        <!--<div><h2 class="text-dark">Propuesta 56528</h2></div>-->
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
                                            <input v-model="offer" type="text" class="form-control discount bg-blue-light-white font-weight-bolder f-15 color-dark-gray not-border mt-3" style="width:150px;" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" v-on:change="changeValueBox(1, 0)"/>
                                        </span>
                                    </div>
                                    <div class="subheader-separator subheader-separator-ver my-auto py-14 bg-gray-light"></div>
                                    <div class="d-block mx-5 px-10 py-8">
                                        <div class="f-16 color-dark-gray text-align-center">
                                            <span>DESCUENTO</span>
                                        </div>
                                        <span class="p-input-icon-right w-100">
                                            <input v-model="discount" type="text" class="form-control discount bg-blue-light-white font-weight-bolder f-15 color-dark-gray not-border mt-3" style="width:150px;" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0"  v-on:change="changeValueBox(2, 0)"/>
                                            <img width="13" class="pi my-auto" src="/media/custom-imgs/icono_porcentaje_input.svg"/>
                                        </span>
                                    </div>
                                    <div class="subheader-separator subheader-separator-ver my-auto py-14 bg-gray-light"></div>
                                    <div class="d-block ml-5 px-10 py-8">
                                        <div class="f-16 color-dark-gray text-align-center">
                                            <span>TARIFA</span>
                                        </div>
                                        <div class="f-15 color-dark-gray font-weight-bolder px-8 py-2 mt-3">
                                            <span >{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(this.proposals.proposal_obj.products.total_global)) }}€</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-15 pl-0" v-if="proposals.proposal_obj.products[0].product_obj != null">
                <table width="100%" cellpadding="2" cellspacing="1">
                    <thead class="custom-columns-datatable">
						<tr>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>SERVICIOS</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>PVP</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>N</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" v-for="index in Number(proposals.proposal_obj.array_dates.length)" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>{{ proposals.proposal_obj.array_dates[index - 1].date }}</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>TOTAL</span></th>
                        </tr>
					</thead>
                    <tbody>
                        <div class="d-contents" v-for="index in Number(proposals.proposal_obj.products.length)">
                            <tr class="row-product">
                                <td class="py-2" :colspan="proposals.proposal_obj.array_dates.length + 4">
                                    <span class="ml-5">{{ proposals.proposal_obj.products[index - 1].product_obj.name }}</span>
                                </td>
                            </tr>
                            <tr class="row-article" v-for="index_article in Number(proposals.proposal_obj.products[index - 1].articles.length)">
                                <td valign="middle" class="td-border-right"><span class="ml-5">{{ proposals.proposal_obj.products[index - 1].articles[index_article - 1].article_obj.name }}</span></td>
                                <td valign="middle" class="td-border-right text-align-center"><span class="">{{ $utils.numberWithDotAndComma($utils.roundAndFix(proposals.proposal_obj.products[index - 1].articles[index_article - 1].article_obj.pvp)) }}€</span></td>
                                <td valign="middle" class="td-border-right text-align-center"><span class="">{{ proposals.proposal_obj.products[index - 1].articles[index_article - 1].amount }}</span></td>
                                <td v-for="index_arr_date in Number(proposals.proposal_obj.array_dates.length)" valign="middle" class="td-border-right">
                                    <template v-for="index_dates in Number(proposals.proposal_obj.products[index - 1].articles[index_article - 1].dates_prices.length)">
                                        <template v-if="proposals.proposal_obj.array_dates[index_arr_date - 1].date == proposals.proposal_obj.products[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].date">
                                            <div class="d-grid px-5">
                                                <template v-for="index_pvp_date in Number(proposals.proposal_obj.products[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date.length)">
                                                    <input v-model="this.value_form1[index - 1].article[index_article - 1].dates[index_dates - 1].date_pvp[index_pvp_date - 1].pvp[index_pvp - 1]" 
                                                    v-for="index_pvp in Number(proposals.proposal_obj.products[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date[index_pvp_date - 1].arr_pvp.length)" 
                                                    @input="changeValuesOffer($event)"
                                                    type="text" class="form-control discount bg-blue-light-white text-align-center not-border my-2" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0"/>
                                                </template>
                                            </div>
                                        </template>
                                    </template>
                                </td>
                                <td valign="middle" class="td-border-right text-align-center"><span class="">{{ $utils.numberWithDotAndComma($utils.roundAndFix(proposals.proposal_obj.products[index - 1].articles[index_article - 1].total)) }}€</span></td>
                            </tr>
                        </div>
                        <tr class="tr-total-datatable">
                            <td class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                            <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(proposals.proposal_obj.products.total_individual_pvp)) }}€</span></td>
                            <td class="text-align-center"><span class="font-weight-bolder">{{ proposals.proposal_obj.products.total_amount_global }}</span></td>
                            <td class="text-align-center" v-for="index in Number(proposals.proposal_obj.array_dates.length)"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(proposals.proposal_obj.array_dates[index - 1].total)) }}€</span></td>
                            <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(proposals.proposal_obj.products.total_global)) }}€</span></td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
            <div class="col-12 pl-0 mt-10" v-if="proposals.proposal_obj.products[0].product_obj != null">
                <span class="text-dark font-weight-bold mb-2">Tipo de propuesta</span>
                <select class="form-control bg-gray text-dark select-custom select-filter mt-3 col-2" :name="'select_type_proposal'" :id="'select_type_proposal'" v-model="select_type_proposal" data-style="select-lightgreen">
                    <option value="1" selected>Normal</option>
                    <option value="2">De la cartera asignada al consultor</option>
                    <option value="3">Responsable de publicaciones</option>
                </select>
                <div class="mt-10" v-if="!is_show_buttons_bill">
                    <button type="submit" class="btn bg-azul color-white px-5 font-weight-bolder mr-4" @click.native="createBills()">Crear factura simple</button>
                    <button type="submit" class="btn bg-azul color-white px-5 font-weight-bolder ml-4">Crear factura personalizada</button>
                </div>
            </div>
            <div class="col-12 pl-0 mt-10" v-if="proposals.proposal_obj.products[0].product_obj != null && is_show_buttons_bill">
                <table width="100%" cellpadding="2" cellspacing="1">
                    <thead class="custom-columns-datatable">
						<tr>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>FACTURAS</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FECHA</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FORMA DE PAGO</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>VENCIMIENTO</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>IMPORTE</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>ACCIÓN</span></th>
                        </tr>
					</thead>
                    <tbody>  
                        <template v-for="(item, index) in Number(proposals.bill_obj.array_bills.length)" :key="index">
                            <tr class="row-product text-align-center">
                                <td class="td-border-right" rowspan="5">{{ index + 1 }}</td>
                            </tr>
                            <!--<tr class="tr-total-datatable">
                                <td class="py-1 pl-5 f-14" colspan="5">BIG DATA Data Email Marketing</td>
                            </tr>-->
                            <tr class="row-product">
                                <td class="text-align-center td-border-right">{{ proposals.bill_obj.array_bills[index].date }}</td>
                                <td class="text-align-center py-4 px-5 td-border-right">
                                    <select class="form-control text-dark select-custom select-filter bg-white" :name="'select_type_proposal'" :id="'select_type_proposal'" v-model="select_type_proposal" data-style="select-lightgreen">
                                        <option value="">Forma de pago</option>
                                        <option value="1">Recibo bancario</option>
                                        <option value="2">Talón nominativo</option>
                                        <option value="3">Transferencia bancaria</option>
                                        <option value="4">Letra aceptada</option>
                                        <option value="5">Pagaré</option>
                                        <option value="7">Metálico</option>
                                        <option value="8">Especial camping</option>
                                        <option value="9">Confirming</option>
                                        <option value="18">Pago certificado</option>
                                        <option value="10">Tarjeta</option>
                                        <option value="15">Talón conformado</option>
                                        <option value="12">Paypal</option>
                                        <option value="6">* Intercambio de facturas</option>
                                        <option value="20">Especial Gaceta</option>
                                    </select>
                                </td>
                                <td class="text-align-center py-4 px-5 td-border-right">
                                    <select class="form-control text-dark select-custom select-filter bg-white" :name="'select_type_proposal'" :id="'select_type_proposal'" v-model="select_type_proposal" data-style="select-lightgreen">
                                        <option value="">Vencimiento</option>
                                        <option value="23">15 días</option>
                                        <option value="2">30 días</option>
                                        <option value="11">30 y 60 días</option>
                                        <option value="29">40 días</option>
                                        <option value="17">45 días</option>
                                        <option value="3">60 días</option>
                                        <option value="4">90 días</option>
                                        <option value="1">Al contado</option>
                                        <option value="20">Al contado y 30 días</option>
                                        <option value="19">Al contado y 60 días</option>
                                        <option value="16">Al contado, 30 y 60 días</option>
                                    </select>
                                </td>
                                <td class="text-align-center td-border-right">
                                    {{ $utils.roundAndFix(proposals.bill_obj.array_bills[index].amount) }}
                                </td>
                                <td class="td-border-right text-align-center">
                                    <button type="button" class="btn"><img  width="40" height="40" src="/media/custom-imgs/icono_tabla_aplicar_todos.svg" /></button>
                                </td>
                            </tr>   
                            <tr class="row-article">
                                <td class="p-5" colspan="5">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Observaciones</span>
                                        <input type="text" class="form-control bg-gray my-auto select-filter text-dark-gray col-10" placeholder="Observaciones" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" />
                                    </div>
                                </td>
                            </tr>      
                            <tr class="row-article">
                                <td class="p-5" colspan="5">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Núm. pedido</span>
                                        <input type="text" class="form-control bg-gray my-auto select-filter text-dark-gray col-10" placeholder="Número de pedido" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" />
                                    </div>
                                </td>
                            </tr>    
                            <tr class="row-article">
                                <td class="p-5" colspan="5">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Observaciones Internas</span>
                                        <input type="text" class="form-control bg-gray my-auto select-filter text-dark-gray col-10" placeholder="Observaciones Internas" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" />
                                    </div>
                                </td>
                            </tr>   
                        </template>       
                        <tr class="tr-total-datatable">
                            <td colspan="4" class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                            <td class="text-align-right"><span class="font-weight-bolder mr-7">{{ $utils.roundAndFix(proposals.bill_obj.total_bill) }}€</span></td>
                            <td class="text-align-center bg-white"><span class="font-weight-bolder"><button type="button" class="btn"><img  width="40" height="40" src="/media/custom-imgs/icono_tabla_eliminar.svg" @click.native="this.is_show_buttons_bill=false"/></button></span></td>
                        </tr>    
                    </tbody>
                </table>
                <div class="mt-10">
                    <button type="submit" class="btn bg-azul color-white px-30 font-weight-bolder">Finalizar propuesta</button>
                </div>
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
            fullname: '',
            select_type_proposal: '1',
            value_form1: [],
            is_show_buttons_bill: false
        };
    },
    computed: {
            ...mapState(["errors", "proposals"]),
    },
    methods: {
        ...mapMutations(["clearError", "changeViewStatusProposals", "changeProposalObj", "changeValueIsChangeArticle", "generateBill"]),
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
        changeValueBox(type, status){
            if(type == 1){
                var difference = this.proposals.proposal_obj.products.total_global - this.offer;
                this.discount = this.$utils.roundAndFix(difference / (this.proposals.proposal_obj.products.total_global) * 100);

            }else{
                var difference = ((100 - this.discount) / 100) * this.proposals.proposal_obj.products.total_global;
                this.offer = parseFloat(this.$utils.roundAndFix(difference));

            }

            if(status == 0){
                //Recorremos el array valur_form1 y miramos cuantos inputs hay. Una vez contado los inputs, repartimos el valor de la oferta entre estos a partes iguales
                var total_inputs = this.rewalkForm1(1, 0);

                var new_value = this.offer / total_inputs;
                //Modificamos los valores de los inputs
                this.rewalkForm1(2, new_value);
            }
            
        },
        rewalkForm1(type){
            let me = this;
            var value = 0;
            me.value_form1.map(function(product, key_product) {
                product.article.map(function(article_obj, key_article) {
                    article_obj.dates.map(function(date, key_dates) {
                        date.date_pvp.map(function(date_pvp, key_date_pvp) {
                            date_pvp.pvp.map(function(pvp_obj, key_pvp) {
                                if(type == 1){
                                    value += 1;
                                }else{
                                    pvp_obj = pvp_obj * (1 - (me.discount  /100));
                                    me.value_form1[key_product].article[key_article].dates[key_dates].date_pvp[key_date_pvp].pvp[key_pvp] = me.$utils.roundAndFix(pvp_obj);
                                }
                            });
                        });
                    });
                });
            });
            if(type == 1){
                return value;
            }
        },
        changeValuesOffer(e) {
            let me = this;
            var total = 0;
            me.value_form1.map(function(product, key_product) {
                product.article.map(function(article_obj, key_article) {
                    article_obj.dates.map(function(date, key_dates) {
                        date.date_pvp.map(function(date_pvp, key_date_pvp) {
                            date_pvp.pvp.map(function(pvp_obj, key_pvp) {
                                total += Number(pvp_obj);
                            });
                        });
                    });
                });
            });
            me.offer = this.$utils.roundAndFix(total);
            this.changeValueBox(1);
            this.changeProposalObj(me.value_form1);
        },
        createBills(){
            this.is_show_buttons_bill = true;
            this.generateBill(this.value_form1);
        },
        loadFormObj(){
            let me = this;
            me.value_form1 = [];
                
            //Rellenar los modelos de los inputs de la tabla
            me.proposals.proposal_obj.products.map(function(product, key_product) {
                product.articles.map(function(article, key_article) {
                    me.proposals.proposal_obj.array_dates.map(function(date_obj, key_arr_dates) {
                        article.dates_prices.map(function(date, key_dates) {
                            if(date_obj.date == date.date){
                                date.arr_pvp_date.map(function(pvp_date, key_pvp_date) {
                                    pvp_date.arr_pvp.map(function(pvp, key_pvp) {
                                        if(me.value_form1[key_product] == undefined){
                                            me.value_form1.push({
                                                article: [{
                                                    dates:[{
                                                        article: article.article_obj,
                                                        date_pvp: [{
                                                            date: pvp_date.date,
                                                            pvp: []
                                                        }]
                                                    }]
                                                }]
                                            });
                                            me.value_form1[key_product].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);

                                        }else if(me.value_form1[key_product].article[key_article] == undefined){
                                            me.value_form1[key_product].article.push({
                                                dates:[{
                                                    article: article.article_obj,
                                                    date_pvp: [{
                                                        date: pvp_date.date,
                                                        pvp: []
                                                    }]
                                                }]
                                            });
                                            me.value_form1[key_product].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);

                                        }else if(me.value_form1[key_product].article[key_article].dates[key_dates] == undefined){
                                            me.value_form1[key_product].article[key_article].dates.push({
                                                article: article.article_obj,
                                                date_pvp: [{
                                                    date: pvp_date.date,
                                                    pvp: []
                                                }]
                                            });
                                            me.value_form1[key_product].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);

                                        }else if(me.value_form1[key_product].article[key_article].dates[key_dates].date_pvp[key_pvp_date] == undefined){
                                            me.value_form1[key_product].article[key_article].dates[key_dates].date_pvp.push({
                                                date: pvp_date.date,
                                                pvp: []
                                            });

                                            me.value_form1[key_product].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);

                                        }else{
                                            me.value_form1[key_product].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);

                                        }
                                    });
                                });
                            }
                        });
                    });
                });
            });
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
                    me.offer = me.$utils.roundAndFix(me.proposals.proposal_obj.products.total_global);
                    me.total = me.$utils.roundAndFix(me.proposals.proposal_obj.products.total_global);
                }

                me.loadFormObj();
            },
        }
    
};
</script>