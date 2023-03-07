<template>
    <div>
        <div class="col-12 pl-0 mt-15">
            <div class="mb-5 mt-15 col-12 row" v-if="invoice_validations.proposal_obj.chapters.length > 0">
                <div>
                    <img class="mr-2" width="150" height="150" src="/media/custom-imgs/icono_ficha_ordenes.svg" />
                </div>
                <div>
                    <div class="ml-10">
                        <div v-if="invoice_validations.status_view == 3 && invoice_validations.proposal_bd_obj != null"><h2 class="text-dark">Propuesta {{ invoice_validations.proposal_bd_obj.id_proposal_custom_aux }}</h2></div>
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
                                    <div v-for="index in Number(invoice_validations.proposal_obj.array_consultants.length)" class="f-15 text-dark">
                                        <span>{{ invoice_validations.proposal_obj.array_consultants[index - 1].name }} - {{ invoice_validations.proposal_obj.array_consultants[index - 1].percentage }}%</span>
                                        <template v-if="this.is_updating_order">
                                            <button class="ml-3 btn bg-azul color-white px-2 py-0 font-weight-bolder" v-if="index == Number(invoice_validations.proposal_obj.array_consultants.length)" v-on:click="openAddConsultant()">+</button>
                                            <button v-if="index != 1" data-id="{{ invoice_validations.proposal_obj.array_consultants[index - 1].id }}" type="button" style="color: #2e49ff;background-color: #e7e7e7;" class="btn py-0 px-1 ml-2" v-on:click="showConsultanModal(index)"><img width="12" height="12" src="/media/custom-imgs/icono_btn_editar.svg"></button>
                                            <button v-if="index != 1" data-id="{{ invoice_validations.proposal_obj.array_consultants[index - 1].id }}" type="button" style="color: #2e49ff;background-color: #ffecf7;" class="btn py-0 px-1 ml-2" v-on:click="deleteConsultanForm(index)"><img width="12" height="12" src="/media/custom-imgs/icono_btn_borrar.svg"></button>
                                        </template>
                                    </div>
                                </div>
                                <div class="d-block mx-20">
                                    <div class="f-16 color-dark-gray font-weight-bolder">
                                        <span>DEPARTAMENTO</span>
                                    </div>
                                    <div class="f-15 text-dark">
                                        <span>{{ invoice_validations.proposal_obj.chapters[0].articles[0].department_obj.name }}</span>
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
                                <div class="border-blue-article mt-8">
                                    <div class="text-align-center px-5 mt-4" v-if="this.is_change_get_info && !this.is_updating_order">
                                        <span class="badge badge-light-success py-4 f-16 w-100 fw-bold">FIRMADA #202210EP-00039334</span>
                                    </div>
                                    <div class="d-flex">
                                        <div class="d-block mr-5 px-10 py-8">
                                            <div class="f-16 color-dark-gray text-align-center">
                                                <span>OFERTA</span>
                                            </div>
                                            <span class="p-input-icon-right w-100" v-if="this.is_updating_order">
                                                <input v-model="offer" type="text" class="form-control discount bg-blue-light-white font-weight-bolder f-15 color-dark-gray not-border mt-3" style="width:150px;" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" v-on:change="changeValueBox(1, 0)"/>
                                            </span>
                                            <div class="f-15 color-dark-gray font-weight-bolder px-8 py-2 mt-3" v-else>
                                                <span >{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(this.offer)) }}€</span>
                                            </div>
                                        </div>
                                        <div class="subheader-separator subheader-separator-ver my-auto py-14 bg-gray-light"></div>
                                        <div class="d-block mx-5 px-10 py-8">
                                            <div class="f-16 color-dark-gray text-align-center">
                                                <span>DESCUENTO</span>
                                            </div>
                                            <span class="p-input-icon-right w-100" v-if="this.is_updating_order">
                                                <input v-model="discount" type="text" class="form-control discount bg-blue-light-white font-weight-bolder f-15 color-dark-gray not-border mt-3" style="width:150px;" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0"  v-on:change="changeValueBox(2, 0)"/>
                                                <img width="13" class="pi my-auto" src="/media/custom-imgs/icono_porcentaje_input.svg"/>
                                            </span>
                                            <div class="f-15 color-dark-gray font-weight-bolder px-8 py-2 mt-3" v-else>
                                                <span >{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(this.discount)) }}%</span>
                                            </div>
                                        </div>
                                        <div class="subheader-separator subheader-separator-ver my-auto py-14 bg-gray-light"></div>
                                        <div class="d-block ml-5 px-10 py-8">
                                            <div class="f-16 color-dark-gray text-align-center">
                                                <span>TARIFA</span>
                                            </div>
                                            <div class="f-15 color-dark-gray font-weight-bolder px-8 py-2 mt-3">
                                                <span >{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(this.invoice_validations.proposal_obj.total_global_normal)) }}€</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-15 pl-0" v-if="(invoice_validations.proposal_obj.chapters.length > 0) || (invoice_validations.proposal_obj.chapters.length > 0 && this.is_change_get_info == 1)">
                <table width="100%" cellpadding="2" cellspacing="1">
                    <thead class="custom-columns-datatable">
						<tr>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>SERVICIOS</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>PVP</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>N</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" v-for="index in Number(invoice_validations.proposal_obj.array_dates.length)" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>{{ invoice_validations.proposal_obj.array_dates[index - 1].date }}</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>TOTAL</span></th>
                            <th v-if="this.is_updating_order" tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>ACCIÓN</span></th>
                        </tr>
					</thead>
                    <tbody>
                        <div class="d-contents" v-for="index in Number(invoice_validations.proposal_obj.chapters.length)">
                            <tr class="row-product" style="background-color: #e4f2fd;" >
                                <td class="py-2" :colspan="invoice_validations.proposal_obj.array_dates.length + 5">
                                    <span class="ml-5">{{ invoice_validations.proposal_obj.chapters[index - 1].chapter_obj.name }}</span>
                                </td>
                            </tr>
                            <tr class="row-article" v-for="index_article in Number(invoice_validations.proposal_obj.chapters[index - 1].articles.length)">
                                <td valign="middle" class="td-border-right"><span class="ml-5">{{ invoice_validations.proposal_obj.chapters[index - 1].articles[index_article - 1].article_obj.name }}</span></td>
                                <td valign="middle" class="td-border-right text-align-center"><span class="">{{ $utils.numberWithDotAndComma($utils.roundAndFix(invoice_validations.proposal_obj.chapters[index - 1].articles[index_article - 1].article_obj.pvp)) }}€</span></td>
                                <td valign="middle" class="td-border-right text-align-center"><span class="">{{ invoice_validations.proposal_obj.chapters[index - 1].articles[index_article - 1].amount }}</span></td>
                                <td v-for="index_arr_date in Number(invoice_validations.proposal_obj.array_dates.length)" valign="middle" class="td-border-right">
                                    <template v-for="index_dates in Number(invoice_validations.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices.length)">
                                        <template v-if="invoice_validations.proposal_obj.array_dates[index_arr_date - 1].date == invoice_validations.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].date">
                                            <div class="d-grid px-5">
                                                <template v-for="index_pvp_date in Number(invoice_validations.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date.length)">
                                                    <input v-if="this.value_form1.length > 0 && this.is_updating_order" v-model="this.value_form1[index - 1].article[index_article - 1].dates[index_dates - 1].date_pvp[index_pvp_date - 1].pvp[index_pvp - 1]" 
                                                    v-for="index_pvp in Number(invoice_validations.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date[index_pvp_date - 1].arr_pvp.length)" 
                                                    @input="changeValuesOffer($event)"
                                                    type="text" class="form-control discount bg-blue-light-white text-align-center not-border my-2" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0"/>
                                                    <span v-for="index_pvp in Number(invoice_validations.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date[index_pvp_date - 1].arr_pvp.length)" v-if="this.value_form1.length > 0 && this.is_change_get_info == 1 && this.is_updating_order == 0" class="mx-auto py-3">{{ this.value_form1[index - 1].article[index_article - 1].dates[index_dates - 1].date_pvp[index_pvp_date - 1].pvp[index_pvp - 1] }}€</span>
                                                </template>
                                            </div>
                                        </template>
                                    </template>
                                </td>
                                <td v-if="this.discount != 0" valign="middle" class="td-border-right text-align-center">
                                    <span class="">{{ this.value_form1[index - 1].article[index_article - 1].total_aux }}€</span>
                                </td>
                                <td v-else valign="middle" class="td-border-right text-align-center"><span class="">{{ $utils.numberWithDotAndComma($utils.roundAndFix(invoice_validations.proposal_obj.chapters[index - 1].articles[index_article - 1].total)) }}€</span></td>
                                <td v-if="this.is_updating_order" class="text-align-center bg-white"><span class="font-weight-bolder"><button type="button" class="btn" v-on:click="deleteArticle(invoice_validations.proposal_obj.chapters[index - 1].articles[0].article_obj.id)"><img  width="40" height="40" src="/media/custom-imgs/icono_tabla_eliminar.svg" v-on:click="this.is_show_buttons_bill=false"/></button></span></td>
                            </tr>
                        </div>
                        <tr class="tr-total-datatable">
                            <td class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                            <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(invoice_validations.proposal_obj.total_individual_pvp)) }}€</span></td>
                            <td class="text-align-center"><span class="font-weight-bolder">{{ invoice_validations.proposal_obj.total_amount_global }}</span></td>
                            
                            <template v-if="this.discount != 0">
                                <td class="text-align-center" v-for="index in Number(invoice_validations.proposal_obj.array_dates.length)">
                                    <span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(this.value_form_discount[index - 1].pvp)) }}€</span>
                                </td>
                                <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(this.offer)) }}€</span></td>
                            </template>
                            <template v-else>
                                <td class="text-align-center" v-for="index in Number(invoice_validations.proposal_obj.array_dates.length)">
                                    <span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(invoice_validations.proposal_obj.array_dates[index - 1].total)) }}€</span>
                                </td>
                                <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(invoice_validations.proposal_obj.total_global)) }}€</span></td>
                            </template>
                            <td v-if="this.is_updating_order" class="text-align-center"><span class="font-weight-bolder"></span></td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
            <div class="col-12 pl-0 mt-10">
                <table width="100%" cellpadding="2" cellspacing="1">
                    <thead class="custom-columns-datatable">
						<tr>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>FACTURAS</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FECHA</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FORMA DE PAGO</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>VENCIMIENTO</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>IMPORTE</span></th>
                        </tr>
					</thead>
                    <tbody>  
                        <template v-for="(item, index) in Number(invoice_validations.bill_obj.array_bills.length)" :key="index">
                            <tr v-if="invoice_validations.bill_obj.array_bills[index].status_validate" class="row-product text-align-center bg-validate-invoice">
                                <td class="td-border-right" rowspan="5">{{ index + 1 }}</td>
                            </tr>
                            <tr v-else class="row-product text-align-center">
                                <td class="td-border-right" rowspan="5">{{ index + 1 }}</td>
                            </tr>
                            <tr class="row-product" style="background-color: #e4f2fd;">
                                <td class="text-align-center td-border-right" width="20%">{{ invoice_validations.bill_obj.array_bills[index].date }}</td>
                                <td class="text-align-center py-4 px-5 td-border-right" width="20%">
                                    <select v-if="this.is_updating_order && invoice_validations.bill_obj.array_bills[index].will_update" class="form-control text-dark select-custom select-filter bg-white" :name="'select_way_to_pay'" :id="'select_way_to_pay'" v-model="invoice_validations.bill_obj.array_bills[index].select_way_to_pay" data-style="select-lightgreen">
                                        <option v-for="(item, index) in Number(this.select_way_to_pay_options.length)" :key="index" :value="this.select_way_to_pay_options[index].value">{{ this.select_way_to_pay_options[index].text }}</option>
                                    </select>
                                    <span v-else>{{ this.select_way_to_pay_options[invoice_validations.bill_obj.array_bills[index].select_way_to_pay].text }}</span>
                                </td>
                                <td class="text-align-center py-4 px-5 td-border-right" width="20%">
                                    <select v-if="this.is_updating_order && invoice_validations.bill_obj.array_bills[index].will_update" class="form-control text-dark select-custom select-filter bg-white" :name="'select_expiration'" :id="'select_expiration'" v-model="invoice_validations.bill_obj.array_bills[index].select_expiration" data-style="select-lightgreen">
                                        <option v-for="(item, index) in Number(this.select_expiration_options.length)" :key="index" :value="this.select_expiration_options[index].value">{{ this.select_expiration_options[index].text }}</option>
                                    </select>
                                    <span v-else>{{ this.select_expiration_options[invoice_validations.bill_obj.array_bills[index].select_expiration].text }}</span>
                                </td>
                                <td class="text-align-center td-border-right" width="20%">
                                    {{ $utils.roundAndFix(invoice_validations.bill_obj.array_bills[index].amount) }}
                                </td>
                            </tr>   
                            <tr class="row-article">
                                <td class="p-5" colspan="5">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Observaciones</span>
                                        <span class="my-auto col-10">{{ invoice_validations.bill_obj.array_bills[index].observations }}</span>
                                    </div>
                                </td>
                            </tr>      
                            <tr class="row-article">
                                <td class="p-5" colspan="5">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Núm. pedido</span>
                                        <span class="my-auto col-10">{{ invoice_validations.bill_obj.array_bills[index].order_number }}</span>
                                    </div>
                                </td>
                            </tr>    
                            <tr class="row-article">
                                <td class="p-5" colspan="5">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Observaciones Internas</span>
                                        <span class="my-auto col-10" >{{ invoice_validations.bill_obj.array_bills[index].internal_observations }}</span>
                                    </div>
                                </td>
                            </tr>   
                        </template>       
                        <tr class="tr-total-datatable">
                            <td colspan="4" class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                            <td class="text-align-right"><span class="font-weight-bolder mr-7">{{ $utils.roundAndFix(invoice_validations.bill_obj.total_bill) }}€</span></td>
                        </tr>    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>

import { mapMutations, mapState, mapActions } from "vuex";

import AddButtonComponent from "../Partials/AddButtonComponent.vue";
import DeleteButtonComponent from "../Partials/DeleteButtonComponent.vue"
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Textarea from 'primevue/textarea';
import Calendar from 'primevue/calendar';

export default {
    name: "InfoOrderComponent",
    components: {
        AddButtonComponent,
        DataTable,
        Column,
        Textarea,
        Calendar,
        DeleteButtonComponent,
    },
    data() {
        return {
            publicPath: window.location.origin,
            select_company: '',
            array_companies: [],
            select_company_other_values: '',
            name_company: '',
            nif_company: '',
            address_company: '',
            id_company: 0,
            offer: 0,
            total: 0,
            discount: '0.00',
            fullname: '',
            select_type_proposal: '1',
            value_form1: [], //Formulario presupuesto,
            value_form_discount: [],
            not_zero_discount: true,
            select_way_to_pay_options: [
                {value: '',text: 'Forma de pago'},
                {value: '1',text: 'Recibo bancario'},
                {value: '2',text: 'Talón nominativo'},
                {value: '3',text: 'Transferencia bancaria'},
                {value: '4',text: 'Letra aceptada'},
                {value: '5',text: 'Pagaré'},
                {value: '6',text: 'Metálico'},
                {value: '7',text: 'Especial camping'},
                {value: '8',text: 'Confirming'},
                {value: '9',text: 'Pago certificado'},
                {value: '10',text: 'Tarjeta'},
                {value: '11',text: 'Talón conformado'},
                {value: '12',text: 'Paypal'},
                {value: '13',text: '* Intercambio de facturas'},
                {value: '14',text: 'Especial Gaceta'}
            ],
            select_expiration_options: [
                {value: '',text: 'Vencimiento'},
                {value: '1',text: '15 días'},
                {value: '2',text: '30 días'},
                {value: '3',text: '30 y 60 días'},
                {value: '4',text: '40 días'},
                {value: '5',text: '45 días'},
                {value: '6',text: '60 días'},
                {value: '7',text: '90 días'},
                {value: '8',text: 'Al contado'},
                {value: '9',text: 'Al contado y 30 días'},
                {value: '10',text: 'Al contado y 60 días'},
                {value: '11',text: 'Al contado, 30 y 60 días'},
            ],
            is_show_buttons_bill: false,
            is_change_get_info: 0,
            is_change_get_info_input: 0,
            is_updating: 0,
            date_now: '',
            is_updating_order: 0
        };
    },
    computed: {
        ...mapState(["errors", "invoice_validations"]),
    },
    methods: {
        ...mapMutations(["clearError", "changeViewStatusOrders", "changeProposalObj", "changeValueIsChangeArticle", "generateBill", "clearObjectsOrders", "deleteArticleOrder", "deleteConsultant"]),
        ...mapActions(["getCompanies", "updateOrder", "deleteOrder", "copyOrder", "payInvoice", "getInfoOrder"]),
        getNameCompany(id, type){
            let me = this;
            me.array_companies.forEach(function callback(value, index, array) {
                if(type == 1){
                    if(value.id == id){
                        me.name_company = value.name;
                        me.nif_company = value.nif;
                        me.address_company = value.address;
                        me.id_company = value.id;
                    }
                }else{
                    if(value.id_company == id){
                        me.name_company = value.name;
                        me.nif_company = value.nif;
                        me.address_company = value.address;
                        me.id_company = value.id;
                    }
                }
            });

        },
        changeValuesOffer(e) {
            let me = this;
            var total = 0;
            me.value_form1.map(function(chapter, key_chapter) {
                chapter.article.map(function(article_obj, key_article) {
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
            var params = {
                status: 1,
                form: me.value_form1,
                type: 3
            }
            this.is_change_get_info_input = 1;
           
            this.changeProposalObj(params);
            me.is_show_buttons_bill = false;
        },
        loadFormObj(){
            let me = this;
            me.value_form1 = [];
                
            //Rellenar los modelos de los inputs de la tabla
            me.invoice_validations.proposal_obj.chapters.map(function(chapter, key_chapter) {
                chapter.articles.map(function(article, key_article) {
                    me.invoice_validations.proposal_obj.array_dates.map(function(date_obj, key_arr_dates) {
                        article.dates_prices.map(function(date, key_dates) {
                            if(date_obj.date == date.date){
                                date.arr_pvp_date.map(function(pvp_date, key_pvp_date) {
                                    pvp_date.arr_pvp.map(function(pvp, key_pvp) {
                                        if(me.value_form1[key_chapter] == undefined){
                                            me.value_form1.push({
                                                article: [{
                                                    dates:[{
                                                        article: article.article_obj,
                                                        date_pvp: [{
                                                            date: pvp_date.date,
                                                            pvp: [],
                                                            pvp_default: []
                                                        }]
                                                    }]
                                                }]
                                            });
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp_default.push(article.article_obj.pvp);

                                        }else if(me.value_form1[key_chapter].article[key_article] == undefined){
                                            me.value_form1[key_chapter].article.push({
                                                dates:[{
                                                    article: article.article_obj,
                                                    date_pvp: [{
                                                        date: pvp_date.date,
                                                        pvp: [],
                                                        pvp_default: []
                                                    }]
                                                }]
                                            });
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp_default.push(article.article_obj.pvp);

                                        }else if(me.value_form1[key_chapter].article[key_article].dates[key_dates] == undefined){
                                            me.value_form1[key_chapter].article[key_article].dates.push({
                                                article: article.article_obj,
                                                date_pvp: [{
                                                    date: pvp_date.date,
                                                    pvp: [],
                                                    pvp_default: []
                                                }]
                                            });
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp_default.push(article.article_obj.pvp);

                                        }else if(me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date] == undefined){
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp.push({
                                                date: pvp_date.date,
                                                pvp: [],
                                                pvp_default: []
                                            });

                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp_default.push(article.article_obj.pvp);

                                        }else{
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp_default.push(article.article_obj.pvp);
                                        }
                                    });
                                });
                            }
                        });
                    });
                });
            });

            //Ponemos a cero los valores auxiliares
            me.value_form1.map(function(product, key_product) {
                product.article.map(function(article_obj, key_article) {
                    article_obj.dates.map(function(date, key_dates) {
                        if(me.value_form_discount[key_dates] != undefined){
                            me.value_form_discount[key_dates].pvp = 0;
                            me.value_form_discount[key_dates].date = '';
                        }
                    });
                });
            });

            if(this.is_change_get_info == 1){
                me.value_form1.map(function(product, key_product) {
                    product.article.map(function(article_obj, key_article) {
                        //Reseteamos el total
                        me.value_form1[key_product].article[key_article].total_aux = 0;
                        article_obj.dates.map(function(date, key_dates) {
                            date.date_pvp.map(function(date_pvp, key_date_pvp) {
                                date_pvp.pvp.map(function(pvp_obj, key_pvp) {
                                    if(me.discount != 0){
                                        if(me.is_change_get_info_input == 1){
                                            pvp_obj = Number(pvp_obj);
                                            me.value_form1[key_product].article[key_article].total_aux += pvp_obj;
                                            
                                            if(key_product == 0){
                                                if(me.value_form_discount[key_dates] != undefined){
                                                    if(me.value_form_discount[key_dates].date == ''){
                                                        me.value_form_discount[key_dates].pvp = pvp_obj;
                                                        me.value_form_discount[key_dates].date = me.$utils.changeFormatDate(date_pvp.date);
                                                    }else if(me.value_form_discount[key_dates].date == me.$utils.changeFormatDate(date_pvp.date)){
                                                        me.value_form_discount[key_dates].pvp += pvp_obj;
                                                    }
                                                }else{
                                                    var new_obj = {
                                                        pvp: pvp_obj,
                                                        date:  me.$utils.changeFormatDate(date_pvp.date)
                                                    }
                                                    me.value_form_discount.push(new_obj);
                                                }
                                            }else{
                                                let is_search = false;
                                                me.value_form_discount.map(function(form_discount, key_form_discount) {
                                                    if(form_discount.date == me.$utils.changeFormatDate(date_pvp.date)){
                                                        form_discount.pvp += pvp_obj;
                                                        is_search = true;
                                                    }
                                                });
                                                if(!is_search){
                                                    var new_obj_value_form_discount = {
                                                        pvp: pvp_obj,
                                                        date:  me.$utils.changeFormatDate(date_pvp.date)
                                                    }
                                                    me.value_form_discount.push(new_obj_value_form_discount);
                                                }
                                            }
                                        }else {
                                            pvp_obj = me.value_form1[key_product].article[key_article].dates[key_dates].article.pvp * (1 - (me.discount  /100));
                                            me.value_form1[key_product].article[key_article].total_aux += pvp_obj;
                                            
                                            if(key_product == 0){
                                                if(me.value_form_discount[key_dates] != undefined){
                                                    if(me.value_form_discount[key_dates].date == ''){
                                                        me.value_form_discount[key_dates].pvp = pvp_obj;
                                                        me.value_form_discount[key_dates].date = me.$utils.changeFormatDate(date_pvp.date);
                                                    }else if(me.value_form_discount[key_dates].date == me.$utils.changeFormatDate(date_pvp.date)){
                                                        me.value_form_discount[key_dates].pvp += pvp_obj;
                                                    }
                                                }else{
                                                    var new_obj = {
                                                        pvp: pvp_obj,
                                                        date:  me.$utils.changeFormatDate(date_pvp.date)
                                                    }
                                                    me.value_form_discount.push(new_obj);
                                                }
                                            }else{
                                                let is_search = false;
                                                me.value_form_discount.map(function(form_discount, key_form_discount) {
                                                    if(form_discount.date == me.$utils.changeFormatDate(date_pvp.date)){
                                                        form_discount.pvp += pvp_obj;
                                                        is_search = true;
                                                    }
                                                });
                                                if(!is_search){
                                                    var new_obj_value_form_discount = {
                                                        pvp: pvp_obj,
                                                        date:  me.$utils.changeFormatDate(date_pvp.date)
                                                    }
                                                    me.value_form_discount.push(new_obj_value_form_discount);
                                                }
                                            }
                                        }   
                                    }
                                    
                                });
                            });
                        });
                        //Formateamos el resultado del total con descuento
                        me.value_form1[key_product].article[key_article].total_aux = me.$utils.roundAndFix(me.value_form1[key_product].article[key_article].total_aux);
                    });
                });
            }
        },
        isDate(value) {
            var dateFormat;
            if (toString.call(value) === '[object Date]') {
                return true;
            }
            if (typeof value.replace === 'function') {
                value.replace(/^\s+|\s+$/gm, '');
            }
            dateFormat = /(^\d{1,4}[\.|\\/|-]\d{1,2}[\.|\\/|-]\d{1,4})(\s*(?:0?[1-9]:[0-5]|1(?=[012])\d:[0-5])\d\s*[ap]m)?$/;
            return dateFormat.test(value);
        },
        //Saber si estan rellenos observaciones, num pedido y observaciones internas
        countRows(obj){
            var rows = 2;
            if(obj.observations != ''){
                rows++;
            }
            if(obj.order_number != ''){
                rows++;
            }
            if(obj.internal_observations != ''){
                rows++;
            }
            return rows;
        },
        //Limpiar el data del component
        clearData(){
            let me = this;
            me.clearObjectsOrders();
            me.select_company = '';
            me.select_company_other_values = '';
            me.name_company = '';
            me.nif_company = '';
            me.address_company = '';
            me.id_company = 0;
            me.offer = 0;
            me.total = 0;
            me.discount = '0.00';
            me.fullname = '';
            me.select_type_proposal = '1';
            me.is_show_buttons_bill = false;
            me.is_updating_order = 0;
        },
        returnView(){
            this.changeViewStatusOrders(1);
            this.clearObjectsOrders();
        },
        //Cargar datos de la propuesta
        loadViewInfoProposal(){
            this.is_change_get_info = this.invoice_validations.is_change_get_info;
            this.invoice_validations.is_change_get_info = 0;
            this.id_company = this.invoice_validations.id_company;
            this.select_company = this.id_company;
            this.array_companies = this.invoice_validations.company_aux;
            this.getNameCompany(this.select_company, 2);
            this.is_show_buttons_bill = true;
            this.offer = Math.round(Number(this.invoice_validations.bill_obj.total_bill) * 100) / 100; //this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(this.proposals.bill_obj.total_bill));
            this.loadFormObj(); 
        },
    },
    mounted() {
        this.date_now = this.$utils.getNow();
        if(this.errors.type_error == "get_info_proposal"){
            this.loadViewInfoProposal();  
        }
        var params = {
            id: this.$route.params.id,
            type: 2
        }
        this.getInfoOrder(params);   
        this.clearError();
    },
    watch: {
        '$store.state.errors.code': function() {
            if(this.errors.type_error == 'get_info_proposal'){
                if(this.errors.code != ''){
                    if(this.errors.code == 1000){
                        this.loadViewInfoProposal();  
                    }
                }
            }
        }
    }
};
</script>