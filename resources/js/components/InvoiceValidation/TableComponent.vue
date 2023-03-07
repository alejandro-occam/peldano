<template>
    <div class="row">
        <div class="col-12 d-flex flex-wrap">            
            <div class="mx-2 col-2 mt-5">
                <span class="text-dark font-weight-bold mb-2">Tipo</span>
                <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_type" :name="'select_type'" :id="'select_type'" data-style="select-lightgreen" @change="loadBillsValidation">
                    <option value="0" selected>Todas</option>
                    <option value="1">Facturas personalizadas</option>
                    <option value="2">Facturas simples</option>
                    <!--<option :value="department.id" v-for="department in config.articles.filter.array_departments" :key="department.id" v-text="department.nomenclature + '-' + department.name" ></option>-->
                </select>
            </div>
            <div class="mx-2 col-2 mt-5">
                <span class="text-dark font-weight-bold mb-2">Factura hasta</span>
                <Calendar class="w-100 select-filter input-custom-calendar mt-3" v-model="date_to" inputId="date_to" autocomplete="off" dateFormat="dd-mm-yy" />
            </div>
            <div class="mx-2 col-2 mt-5">
                <span class="text-dark font-weight-bold mb-2">Validadas</span>
                <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_validate" :name="'select_validate'" :id="'select_validate'" data-style="select-lightgreen" @change="loadBillsValidation">
                    <option value="0" selected>Todas</option>
                    <option value="1">SI</option>
                    <option value="2">NO</option>
                    <!--<option :value="department.id" v-for="department in config.articles.filter.array_departments" :key="department.id" v-text="department.nomenclature + '-' + department.name" ></option>-->
                </select>
            </div>
        </div>
        <div class="col-12 mt-2">
            <div class="col-12 mt-15">
                <table width="100%" cellpadding="2" cellspacing="1" v-if="Number(invoice_validations.array_bill_orders.length) > 0 && invoice_validations.array_bill_orders != undefined">
                    <thead class="custom-columns-datatable">
                        <tr class="">
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 25px;"><span>N</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 25px;"><span>F</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 125px;"><span>Código</span></th>
                            <th tabindex="0" class="pb-3" aria-controls="example" rowspan="1" colspan="1" style="width: 125px;"><span>CLIENTE</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Orden</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Importe</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Consultor</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Fecha</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FP</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Venc</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Pedido</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Acción</span></th>
                        </tr>
                    </thead>
                    <tbody>  
                        <template v-for="index_bill_order in Number(invoice_validations.array_bill_orders.length)" :key="index_bill_order.id">
                            <!--ROW 1-->
                            <tr class="row-product bg-blue-light-white">
                                <td class="pl-3 py-5 text-dark text-align-center">{{ index_bill_order }}</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].type_order == 0" class="text-align-center text-dark">S </td>
                                <td v-else class="text-align-center text-dark">P </td>
                                <td class="text-align-center text-dark" style="color: red !important;">{{ invoice_validations.array_bill_orders[index_bill_order - 1].id_company_sage }}</td>
                                <td class="text-dark">{{ invoice_validations.array_bill_orders[index_bill_order - 1].name_company }}</td>
                                <td class="text-align-center text-dark"><a class="url-order" target="_blank" :href="'/admin/orders/'+invoice_validations.array_bill_orders[index_bill_order - 1].id_order">{{ invoice_validations.array_bill_orders[index_bill_order - 1].id_order }}</a></td>
                                <td class="text-align-center text-dark">{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(invoice_validations.array_bill_orders[index_bill_order - 1].amount)) }}</td>
                                <td class="text-align-center text-dark">
                                    <template v-for="index_consultants in Number(invoice_validations.array_bill_orders[index_bill_order - 1].array_custom_consultant.length)" >
                                        <div>{{ invoice_validations.array_bill_orders[index_bill_order - 1].array_custom_consultant[index_consultants - 1].id_consultant }}</div>
                                    </template>
                                </td>
                                <td class="text-align-center text-dark">{{ invoice_validations.array_bill_orders[index_bill_order - 1].date }}</td>
                                
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].expiration == 1" class="text-align-center text-dark">Recibo bancario</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].expiration == 2" class="text-align-center text-dark">Talón nominativo</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].expiration == 3" class="text-align-center text-dark">Transferencia bancaria</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].expiration == 4" class="text-align-center text-dark">Letra aceptada</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].expiration == 5" class="text-align-center text-dark">Pagaré</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].expiration == 6" class="text-align-center text-dark">Metálico</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].expiration == 7" class="text-align-center text-dark">Especial camping</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].expiration == 8" class="text-align-center text-dark">Confirming</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].expiration == 9" class="text-align-center text-dark">Pago certificado</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].expiration == 10" class="text-align-center text-dark">Tarjeta</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].expiration == 11" class="text-align-center text-dark">Talón conformado</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].expiration == 12" class="text-align-center text-dark">Paypal</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].expiration == 13" class="text-align-center text-dark">* Intercambio de facturas</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].expiration == 14" class="text-align-center text-dark">Especial Gaceta</td>

                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].way_to_pay == 1" class="text-align-center text-dark">15 días</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].way_to_pay == 2" class="text-align-center text-dark">30 días</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].way_to_pay == 3" class="text-align-center text-dark">30 y 60 días</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].way_to_pay == 4" class="text-align-center text-dark">40 días</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].way_to_pay == 5" class="text-align-center text-dark">45 días</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].way_to_pay == 6" class="text-align-center text-dark">60 días</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].way_to_pay == 7" class="text-align-center text-dark">90 días</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].way_to_pay == 8" class="text-align-center text-dark">Al contado</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].way_to_pay == 9" class="text-align-center text-dark">Al contado y 30 días</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].way_to_pay == 10" class="text-align-center text-dark">Al contado y 60 días</td>
                                <td v-if="invoice_validations.array_bill_orders[index_bill_order - 1].way_to_pay == 11" class="text-align-center text-dark">Al contado, 30 y 60 días</td>

                                <td class="text-align-center text-dark">{{ invoice_validations.array_bill_orders[index_bill_order - 1].num_order }}</td>
                                <td class="text-align-center text-dark"><button class="btn bg-azul color-white" v-if="!invoice_validations.array_bill_orders[index_bill_order - 1].status_validate" v-on:click="validateBillTable(invoice_validations.array_bill_orders[index_bill_order - 1].id)">Validar</button></td>
                            </tr>   
                            <tr v-if="invoice_validations.array_bill_orders[index_bill_order - 1].observations != '' && invoice_validations.array_bill_orders[index_bill_order - 1].observations != undefined && invoice_validations.array_bill_orders[index_bill_order - 1].observations != null">
                                <td class="pt-3 pl-9" :colspan="11">
                                    <tr style="width: 25px;">
                                        <span class="font-weight-bolder">Observaciones: </span><span>{{ invoice_validations.array_bill_orders[index_bill_order - 1].observations }}</span>
                                    </tr>
                                </td>
                            </tr>
                            <tr v-if="invoice_validations.array_bill_orders[index_bill_order - 1].internal_observations != '' && invoice_validations.array_bill_orders[index_bill_order - 1].internal_observations != undefined && invoice_validations.array_bill_orders[index_bill_order - 1].internal_observations != null">
                                <td class="pt-3 pb-2 pl-9" :colspan="11">
                                    <tr style="width: 25px;">
                                        <span class="font-weight-bolder">Observaciones internas: </span><span>{{ invoice_validations.array_bill_orders[index_bill_order - 1].internal_observations }}</span>
                                    </tr>
                                </td>
                            </tr>
                            <tr class="row-product bg-white">
                                <td :colspan="11" class="pl-7">
                                    <table width="100%" class="my-3 bg-white" cellpadding="2" cellspacing="1">
                                        <tbody>  
                                            <tr v-for="index_article in Number(invoice_validations.array_bill_orders[index_bill_order - 1].array_articles.length)"  class="">
                                                <td class="pl-3 py-1 text-dark" style="width: 50px;">{{ invoice_validations.array_bill_orders[index_bill_order - 1].array_articles[index_article - 1].id_sage_article }}</td>
                                                <td class="pl-3 py-1 text-dark" style="width: 750px;">{{ invoice_validations.array_bill_orders[index_bill_order - 1].array_articles[index_article - 1].name }}</td>
                                                <td class="pl-3 py-1 text-dark" style="color: red !important;">Beta10</td>
                                                <td class="pl-3 py-1 text-dark">{{ invoice_validations.array_bill_orders[index_bill_order - 1].array_articles[index_article - 1].amount }}</td>
                                                <td class="pl-3 py-1 text-dark">{{ this.$utils.numberWithDotAndComma(invoice_validations.array_bill_orders[index_bill_order - 1].array_articles[index_article - 1].amount_percent) }}</td>
                                                <td class="pl-3 py-1 text-dark">{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(invoice_validations.array_bill_orders[index_bill_order - 1].array_articles[index_article - 1].price_article)) }}</td>
                                                <td class="pl-3 py-1 text-dark">{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(invoice_validations.array_bill_orders[index_bill_order - 1].array_articles[index_article - 1].price_article_percent)) }}</td>
                                                <td class="pl-3 py-1 text-dark">{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(invoice_validations.array_bill_orders[index_bill_order - 1].array_articles[index_article - 1].discount_percent)) }}</td>
                                                <td class="pl-3 py-1 text-dark" style="color: red !important;">0,00</td>
                                                <td class="pl-3 py-1 text-dark">{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(invoice_validations.array_bill_orders[index_bill_order - 1].array_articles[index_article - 1].real_amount)) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </template>
                        <!--ROW 2-->
                        <!--<tr class="row-product bg-white">
                            <td class="pl-3 py-5 text-dark text-align-center">Elizabeth Canto</td>
                            <td class="text-align-center text-gray">
                                <div v-if="this.desplegable2 == 0"  class="dropdown cursor-pointer out py-1" v-on:click="testDesplegable(1, 2)">
                                    <span>A222156-1</span>
                                    <img class="ml-3" width="15" src="/media/custom-imgs/flecha_abajo_badge.svg" />
                                </div>
                                <div v-else class="dropdown cursor-pointer in py-1" v-on:click="testDesplegable(0, 2)">
                                    <span>A2202156-1</span>
                                    <img class="ml-3" width="15" src="/media/custom-imgs/flecha_arriba_badge.svg" />
                                </div>
                                
                            </td>
                            <td class="text-align-center text-gray">38286</td>
                            <td class="text-dark">001797: <br>Manifone Iberia, SL</td>
                            <td class="text-align-center text-gray">4.186,67</td>
                            <td class="text-align-center text-dark">5.065,87</td>
                            <td class="text-align-center text-gray">5.065,87</td>
                            <td class="text-align-center text-gray">06-06-2022</td>
                            <td class="text-align-center text-gray">06-07-2022</td>
                            <td class="text-align-center text-gray">31-08-2022</td>
                            <td class="text-align-center text-gray">167,47</td>
                        </tr>   
                        <template v-if="this.desplegable2 == 1">
                            <tr class="row-product bg-blue-light-white">
                                <td></td>
                                <td :colspan="7">
                                    <table width="100%" class="my-10 bg-blue-light-white" cellpadding="2" cellspacing="1">
                                        <thead class="custom-columns-datatable">
                                            <tr class="">
                                                <th tabindex="0" class="pb-3 pl-3" aria-controls="example" rowspan="4" colspan="1"><span>ARTICULO</span></th>
                                                <th tabindex="0" class="pb-3 pl-3" aria-controls="example" rowspan="4" colspan="1" style="width: 125px;"><span>IMPORTE</span></th>
                                                <th tabindex="0" class="pb-3 pl-3" aria-controls="example" rowspan="1" colspan="1" style="width: 125px;"><span>PARTICIPA %</span></th>
                                                <th tabindex="0" class="pb-3 pl-3" aria-controls="example" rowspan="1" colspan="1" style="width: 125px;"><span>COM. %</span></th>
                                                <th tabindex="0" class="pb-3 pl-3" aria-controls="example" rowspan="1" colspan="1" style="width: 125px;"><span>COMISION</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>  
                                            <tr class="">
                                                <td class="pl-3 py-1 text-dark">90101005 : PAGINA</td>
                                                <td class="pl-3 py-1 text-dark">526,66</td>
                                                <td class="pl-3 py-1 text-dark">100,00</td>
                                                <td class="pl-3 py-1 text-dark">4,00</td>
                                                <td class="pl-3 py-1 text-dark">21,07</td>
                                            </tr>
                                            <tr class="">
                                                <td class="pl-3 py-1 text-dark">90101017 : ESPECIAL CORPORATIVO (CONTENIDO + PUBLICIDAD)</td>
                                                <td class="pl-3 py-1 text-dark">526,66</td>
                                                <td class="pl-3 py-1 text-dark">100,00</td>
                                                <td class="pl-3 py-1 text-dark">4,00</td>
                                                <td class="pl-3 py-1 text-dark">21,07</td>
                                            </tr>
                                            <tr class="">
                                                <td class="pl-3 py-1 text-dark">90101017 : ESPECIAL CORPORATIVO (CONTENIDO + PUBLICIDAD)</td>
                                                <td class="pl-3 py-1 text-dark">526,66</td>
                                                <td class="pl-3 py-1 text-dark">100,00</td>
                                                <td class="pl-3 py-1 text-dark">4,00</td>
                                                <td class="pl-3 py-1 text-dark">21,07</td>
                                            </tr>
                                            <tr class="">
                                                <td class="pl-3 py-1 text-dark">90210020 : PATROCINIO GOLD 2021</td>
                                                <td class="pl-3 py-1 text-dark">526,66</td>
                                                <td class="pl-3 py-1 text-dark">100,00</td>
                                                <td class="pl-3 py-1 text-dark">4,00</td>
                                                <td class="pl-3 py-1 text-dark">21,07</td>
                                            </tr>
                                            <tr class="">
                                                <td class="pl-3 py-1 text-dark">90418001 : CLUB DE SOCIOS</td>
                                                <td class="pl-3 py-1 text-dark">526,66</td>
                                                <td class="pl-3 py-1 text-dark">100,00</td>
                                                <td class="pl-3 py-1 text-dark">4,00</td>
                                                <td class="pl-3 py-1 text-dark">21,07</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td :colspan="3"></td>
                            </tr>
                        </template>
                        <tr class="row-product bg-white">
                            <td :colspan="11">
                                <div class="bg-white d-flex py-5">
                                    <span class="pl-10 font-weight-bold text-dark f-16">FACTURAS COBRADAS EN AGOSTO 2022</span>
                                    <span class="pr-10 font-weight-bold text-dark f-16 ml-auto">167,47</span>
                                </div>
                            </td>
                        </tr>
                        <tr class="tr-total-datatable">
                            <td colspan="10" class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                            <td  class="text-align-center"><span class="font-weight-bolder">2.895,00€</span></td>
                        </tr>-->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <ProgressSpinner v-if="invoice_validations.is_loading" style="position: fixed; top:50vh; left: 50%; " aria-label="Loading" />
</template>

<script>
    import Calendar from 'primevue/calendar';
    import ProgressSpinner from 'primevue/progressspinner';

    import { mapMutations, mapActions, mapState } from "vuex";
    export default {
        name: "TableComponent",
        components: {
            Calendar,
            ProgressSpinner
        },
        data() {
            return {
                publicPath: window.location.origin,
                select_type: 0,
                select_validate: 0,
                date_to: '',
                date_to_custom: ''
            };
        },
        methods: {
            ...mapActions(["listBillsValidation", "validateBill"]),
            ...mapMutations(["clearError"]),
            // Validates that the input string is a valid date formatted as "mm/dd/yyyy"
            isValidDate(dateString) {
                var regEx = /^\d{2}-\d{2}-\d{4}$/;
                // First check for the pattern
                try {
                    if(!dateString.match(regEx)){
                        return false;
                    }else{
                        return true;
                    }
                } catch (error) {
                    return false;
                }
            },
            loadBillsValidation(){
                var date_to = this.date_to;
                if(!this.isValidDate(this.date_to)){
                    var date_ms_to = Date.parse(this.date_to);
                    date_to = this.$utils.customFormDate(date_ms_to);
                }
                this.date_to_custom = date_to;
                var params = {
                    select_type: this.select_type,
                    date: this.date_to_custom,
                    select_validate: this.select_validate
                }
                this.listBillsValidation(params);
            },
            getNow() {
                const today = new Date();
                var day = today.getUTCDate();
                if(day < 10){
                    day = '0'+day;
                }
                var month = today.getMonth() + 1;
                if(month < 10){
                    month = '0'+month;
                }
                const date_to =  day + '-'+ month + '-' + today.getFullYear();
                this.date_to = date_to;
            },
            //Validar factura
            validateBillTable(id){
                var params = {
                    id_bill: id
                }
                this.invoice_validations.is_loading = true;
                this.validateBill(params)
            }
        },
        computed: {
                ...mapState(["errors", "invoice_validations"]),
        },
        mounted() {
            this.getNow();
            this.loadBillsValidation();
        },
        watch: {
            date_to: {
                handler: async function(val) {
                    this.loadBillsValidation();
                }
            },
            '$store.state.errors.code': function() {
                if(this.errors.type_error == 'validate_bill'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            this.loadBillsValidation();
                            swal("", "Factura validada correctamente", "success");

                        }else{
                            swal("", "Ha habido un error. Inténtalo de nuevo más tatrde", "error");
                        }
                    
                    }else{
                        swal("", "Ha habido un error. Inténtalo de nuevo más tatrde", "error");
                    }
                    this.invoice_validations.is_loading = false;
                }
                this.clearError();
            }
        }
    };
    </script>