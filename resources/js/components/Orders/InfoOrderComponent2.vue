<template>
    <div class="col-12 pl-0 mt-15">        
        <div class="mb-5 mt-15 col-12 row">
            <div>
                <img class="mr-2" width="150" height="150" src="/media/custom-imgs/icono_ficha_ordenes.svg" />
            </div>
            <div>
                <div class="ml-10">
                    <div><h2 class="text-dark">Propuesta {{ invoice_validations.order.id_proposal_custom_aux }}</h2></div>
                    <div class="f-20">
                        <span class="text-dark font-weight-bold">Cliente: <span class="color-dark-gray font-weight-bold">{{ invoice_validations.order.name_company }}</span></span>
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
                                <div v-for="index in Number(invoice_validations.order.array_consultants.length)" class="f-15 text-dark">
                                    <span>{{ invoice_validations.order.array_consultants[index - 1].name }} - {{ invoice_validations.order.array_consultants[index - 1].percentage }}%</span>
                                </div>
                            </div>
                            <div class="d-block mx-20">
                                <div class="f-16 color-dark-gray font-weight-bolder">
                                    <span>DEPARTAMENTO</span>
                                </div>
                                <div class="f-15 text-dark">
                                    <span v-if="invoice_validations.order.chapters[0].articles[0].department_obj != null">{{ invoice_validations.order.chapters[0].articles[0].department_obj.name }}</span>
                                </div>
                            </div>
                            <div class="d-block ml-20">
                                <div class="f-16 color-dark-gray font-weight-bolder">
                                    <span>ANUNCIANTE</span>
                                </div>
                                <div class="f-15 text-dark">
                                    {{ invoice_validations.order.name_company }}
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
                                        <div class="f-15 color-dark-gray font-weight-bolder px-8 py-2 mt-3">
                                            <span >{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(this.offer)) }}€</span>
                                        </div>
                                    </div>
                                    <div class="subheader-separator subheader-separator-ver my-auto py-14 bg-gray-light"></div>
                                    <div class="d-block mx-5 px-10 py-8">
                                        <div class="f-16 color-dark-gray text-align-center">
                                            <span>DESCUENTO</span>
                                        </div>
                                        <div class="f-15 color-dark-gray font-weight-bolder px-8 py-2 mt-3">
                                            <span >{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(this.discount)) }}%</span>
                                        </div>
                                    </div>
                                    <div class="subheader-separator subheader-separator-ver my-auto py-14 bg-gray-light"></div>
                                    <div class="d-block ml-5 px-10 py-8">
                                        <div class="f-16 color-dark-gray text-align-center">
                                            <span>TARIFA</span>
                                        </div>
                                        <div class="f-15 color-dark-gray font-weight-bolder px-8 py-2 mt-3">
                                            <span >{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(this.invoice_validations.order.total_global_normal)) }}€</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-15 pl-0" v-if="(orders.proposal_obj.chapters.length > 0) || (orders.proposal_obj.chapters.length > 0 && this.is_change_get_info == 1)">
            <table width="100%" cellpadding="2" cellspacing="1">
                <thead class="custom-columns-datatable">
                    <tr>
                        <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>SERVICIOS</span></th>
                        <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>PVP</span></th>
                        <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>N</span></th>
                        <th tabindex="0" class="pb-3 text-align-center" v-for="index in Number(orders.proposal_obj.array_dates.length)" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>{{ orders.proposal_obj.array_dates[index - 1].date }}</span></th>
                        <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>TOTAL</span></th>
                        <th v-if="this.is_updating_order" tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>ACCIÓN</span></th>
                    </tr>
                </thead>
                <tbody>
                    
                    <div class="d-contents" v-for="index in Number(orders.proposal_obj.chapters.length)">
                        <tr class="row-product">
                            <td class="py-2" :colspan="orders.proposal_obj.array_dates.length + 5">
                                <span class="ml-5">{{ orders.proposal_obj.chapters[index - 1].chapter_obj.name }}</span>
                            </td>
                        </tr>
                        <tr class="row-article" v-for="index_article in Number(orders.proposal_obj.chapters[index - 1].articles.length)">
                            <td valign="middle" class="td-border-right"><span class="ml-5">{{ orders.proposal_obj.chapters[index - 1].articles[index_article - 1].article_obj.name }}</span></td>
                            <td valign="middle" class="td-border-right text-align-center"><span class="">{{ $utils.numberWithDotAndComma($utils.roundAndFix(orders.proposal_obj.chapters[index - 1].articles[index_article - 1].article_obj.pvp)) }}€</span></td>
                            <td valign="middle" class="td-border-right text-align-center"><span class="">{{ orders.proposal_obj.chapters[index - 1].articles[index_article - 1].amount }}</span></td>
                            <td v-for="index_arr_date in Number(orders.proposal_obj.array_dates.length)" valign="middle" class="td-border-right">
                                <template v-for="index_dates in Number(orders.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices.length)">
                                    <template v-if="orders.proposal_obj.array_dates[index_arr_date - 1].date == orders.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].date">
                                        <div class="d-grid px-5">
                                            <template v-for="index_pvp_date in Number(orders.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date.length)">
                                                <input v-if="this.value_form1.length > 0 && this.is_updating_order" v-model="this.value_form1[index - 1].article[index_article - 1].dates[index_dates - 1].date_pvp[index_pvp_date - 1].pvp[index_pvp - 1]" 
                                                v-for="index_pvp in Number(orders.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date[index_pvp_date - 1].arr_pvp.length)" 
                                                @input="changeValuesOffer($event)"
                                                type="text" class="form-control discount bg-blue-light-white text-align-center not-border my-2" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0"/>
                                                <span v-for="index_pvp in Number(orders.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date[index_pvp_date - 1].arr_pvp.length)" v-if="this.value_form1.length > 0 && this.is_change_get_info == 1 && this.is_updating_order == 0" class="mx-auto py-3">{{ this.value_form1[index - 1].article[index_article - 1].dates[index_dates - 1].date_pvp[index_pvp_date - 1].pvp[index_pvp - 1] }}€</span>
                                            </template>
                                        </div>
                                    </template>
                                </template>
                            </td>
                            <td v-if="this.discount != 0" valign="middle" class="td-border-right text-align-center">
                                <span class="">{{ this.value_form1[index - 1].article[index_article - 1].total_aux }}€</span>
                            </td>
                            <td v-else valign="middle" class="td-border-right text-align-center"><span class="">{{ $utils.numberWithDotAndComma($utils.roundAndFix(orders.proposal_obj.chapters[index - 1].articles[index_article - 1].total)) }}€</span></td>
                            <td v-if="this.is_updating_order" class="text-align-center bg-white"><span class="font-weight-bolder"><button type="button" class="btn" v-on:click="deleteArticle(orders.proposal_obj.chapters[index - 1].articles[0].article_obj.id)"><img  width="40" height="40" src="/media/custom-imgs/icono_tabla_eliminar.svg" v-on:click="this.is_show_buttons_bill=false"/></button></span></td>
                        </tr>
                    </div>
                    <tr class="tr-total-datatable">
                        <td class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                        <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(orders.proposal_obj.total_individual_pvp)) }}€</span></td>
                        <td class="text-align-center"><span class="font-weight-bolder">{{ orders.proposal_obj.total_amount_global }}</span></td>
                        
                        <template v-if="this.discount != 0">
                            <td class="text-align-center" v-for="index in Number(orders.proposal_obj.array_dates.length)">
                                <span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(this.value_form_discount[index - 1].pvp)) }}€</span>
                            </td>
                            <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(this.offer)) }}€</span></td>
                        </template>
                        <template v-else>
                            <td class="text-align-center" v-for="index in Number(orders.proposal_obj.array_dates.length)">
                                <span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(orders.proposal_obj.array_dates[index - 1].total)) }}€</span>
                            </td>
                            <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(orders.proposal_obj.total_global)) }}€</span></td>
                        </template>
                        <td v-if="this.is_updating_order" class="text-align-center"><span class="font-weight-bolder"></span></td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
        <div class="col-12 pl-0 mt-10" v-if="(orders.proposal_obj.chapters.length > 0 && this.is_show_buttons_bill) || (orders.proposal_obj.chapters.length > 0 && this.is_change_get_info == 1)">
            <table width="100%" cellpadding="2" cellspacing="1">
                <thead class="custom-columns-datatable">
                    <tr>
                        <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>FACTURAS</span></th>
                        <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FECHA</span></th>
                        <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FORMA DE PAGO</span></th>
                        <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>VENCIMIENTO</span></th>
                        <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>IMPORTE</span></th>
                        <th tabindex="0" v-if="this.is_change_get_info == 0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>ACCIÓN</span></th>
                    </tr>
                </thead>
                <tbody>  
                    <template v-for="(item, index) in Number(orders.bill_obj.array_bills.length)" :key="index">
                        <tr v-if="orders.bill_obj.array_bills[index].status_validate" class="row-product text-align-center bg-validate-invoice">
                            <td class="td-border-right" rowspan="5">{{ index + 1 }}</td>
                        </tr>
                        <tr v-else class="row-product text-align-center">
                            <td class="td-border-right" rowspan="5">{{ index + 1 }}</td>
                        </tr>
                        <tr class="row-product">
                            <td class="text-align-center td-border-right" width="20%" v-if="orders.num_custom_invoices == 0">{{ orders.bill_obj.array_bills[index].date }}</td>
                            <td class="text-align-center td-border-right" width="20%" v-else>
                                <Calendar class="w-100 borders-box text-dark-gray px-5"  autocomplete="off" v-model="orders.bill_obj.array_bills[index].date" dateFormat="dd-mm-yy"  />
                            </td>
                            <td class="text-align-center py-4 px-5 td-border-right" width="20%">
                                <span>{{ this.select_way_to_pay_options[orders.bill_obj.array_bills[index].select_way_to_pay].text }}</span>
                            </td>
                            <td class="text-align-center py-4 px-5 td-border-right" width="20%">
                                <span>{{ this.select_expiration_options[orders.bill_obj.array_bills[index].select_expiration].text }}</span>
                            </td>
                            <td class="text-align-center td-border-right" width="20%">
                                {{ $utils.roundAndFix(orders.bill_obj.array_bills[index].amount) }}
                            </td>
                        </tr>   
                        <tr class="row-article">
                            <td v-if="this.is_updating_order" class="p-5" colspan="6">
                                <div class="d-flex">
                                    <span class="my-auto col-2">Observaciones</span>
                                    <span class="my-auto col-10">{{ orders.bill_obj.array_bills[index].observations }}</span>
                                </div>
                            </td>
                            <td v-else class="p-5" colspan="5">
                                <div class="d-flex">
                                    <span class="my-auto col-2">Observaciones</span>
                                    <span class="my-auto col-10">{{ orders.bill_obj.array_bills[index].observations }}</span>
                                </div>
                            </td>
                        </tr>      
                        <tr class="row-article">
                            <td class="p-5" colspan="5">
                                <div class="d-flex">
                                    <span class="my-auto col-2">Núm. pedido</span>
                                    <span class="my-auto col-10">{{ orders.bill_obj.array_bills[index].order_number }}</span>
                                </div>
                            </td>
                        </tr>    
                        <tr class="row-article">
                            <td class="p-5" colspan="5">
                                <div class="d-flex">
                                    <span class="my-auto col-2">Observaciones Internas</span>
                                    <span class="my-auto col-10" >{{ orders.bill_obj.array_bills[index].internal_observations }}</span>
                                </div>
                            </td>
                        </tr>   
                    </template>       
                    <tr class="tr-total-datatable">
                        <td colspan="4" class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                        <td class="text-align-right"><span class="font-weight-bolder mr-7">{{ $utils.roundAndFix(orders.bill_obj.total_bill) }}€</span></td>
                    </tr>    
                </tbody>
            </table>
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
    name: "FormComponentOrder",
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
        };
    },
    computed: {
        ...mapState(["errors", "invoice_validations"]),
    },
    methods: {
        ...mapMutations(["clearError", "changeViewStatusOrders", "changeProposalObj", "changeValueIsChangeArticle", "generateBill", "clearObjectsOrders", "deleteArticleOrder", "deleteConsultant"]),
        ...mapActions(["getCompanies", "updateOrder", "deleteOrder", "copyOrder", "payInvoice"]),
        
        
    },
    mounted() {
        console.log(this.$route.params.id);
    },
    watch: {
    },
};
</script>