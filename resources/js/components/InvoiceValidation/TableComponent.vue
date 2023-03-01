<template>
    <div class="row">
        <div class="col-12 d-flex flex-wrap justify-content-between">
        </div>
        <div class="col-12 mt-7">
            <div class="col-12 d-flex flex-wrap mt-6">
                <div class="col-12 flex-wrap justify-content-between">
                    <h3 class="color-blue my-auto">Resultados</h3>
                </div>
            </div>
            <div class="col-12 mt-15">
                <table width="100%" cellpadding="2" cellspacing="1">
                    <thead class="custom-columns-datatable">
                        <tr class="">
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 25px;"><span>N</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 25px;"><span>F</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 50px;"><span>Código</span></th>
                            <th tabindex="0" class="pb-3" aria-controls="example" rowspan="1" colspan="1" style="width: 125px;"><span>CLIENTE</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Orden</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Importe</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Consultor</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Fecha</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FP</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Venc</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Pedido</span></th>
                        </tr>
                    </thead>
                    <tbody>  
                        <template v-for="index_bill_order in Number(invoice_validations.array_bill_orders.length)" :key="index_bill_order.id">
                            <!--ROW 1-->
                            <tr class="row-product bg-white">
                                <td class="pl-3 py-5 text-dark text-align-center">{{ invoice_validations.array_bill_orders[index_bill_order - 1].id }}</td>
                                <td class="text-align-center text-gray">P </td>
                                <td class="text-align-center text-gray">NV330546</td>
                                <td class="text-dark">Beta10 Soluciones Tecnológicas, S. L.</td>
                                <td class="text-align-center text-gray">{{ invoice_validations.array_bill_orders[index_bill_order - 1].id_order }}</td>
                                <td class="text-align-center text-dark">688,20</td>
                                <td class="text-align-center text-gray">089</td>
                                <td class="text-align-center text-gray">{{ invoice_validations.array_bill_orders[index_bill_order - 1].date }}</td>
                                <td class="text-align-center text-gray">Transf</td>
                                <td class="text-align-center text-gray">Al contado</td>
                                <td class="text-align-center text-gray"></td>
                            </tr>   
                            <tr v-if="invoice_validations.array_bill_orders[index_bill_order - 1].observations != '' && invoice_validations.array_bill_orders[index_bill_order - 1].observations != undefined && invoice_validations.array_bill_orders[index_bill_order - 1].observations != null">
                                <td class="pt-3 pl-7" :colspan="11">
                                    <tr style="width: 25px;">
                                        <span class="font-weight-bolder">Observaciones: </span><span>{{ invoice_validations.array_bill_orders[index_bill_order - 1].observations }}</span>
                                    </tr>
                                </td>
                            </tr>
                            <tr v-if="invoice_validations.array_bill_orders[index_bill_order - 1].internal_observations != '' && invoice_validations.array_bill_orders[index_bill_order - 1].internal_observations != undefined && invoice_validations.array_bill_orders[index_bill_order - 1].internal_observations != null">
                                <td class="pt-3 pb-2 pl-7" :colspan="11">
                                    <tr style="width: 25px;">
                                        <span class="font-weight-bolder">Observaciones internas: </span><span>{{ invoice_validations.array_bill_orders[index_bill_order - 1].internal_observations }}</span>
                                    </tr>
                                </td>
                            </tr>
                            <tr class="row-product bg-blue-light-white">
                                <td :colspan="11" class="pl-7">
                                    <table width="100%" class="my-3 bg-blue-light-white" cellpadding="2" cellspacing="1">
                                        <tbody>  
                                            <tr class="">
                                                <td class="pl-3 py-1 text-dark">MEGABANNER DESTACADO 1</td>
                                                <td class="pl-3 py-1 text-dark">Beta10</td>
                                                <td class="pl-3 py-1 text-dark">1</td>
                                                <td class="pl-3 py-1 text-dark">1,000</td>
                                                <td class="pl-3 py-1 text-dark">740,00</td>
                                                <td class="pl-3 py-1 text-dark">740,00</td>
                                                <td class="pl-3 py-1 text-dark">7,00</td>
                                                <td class="pl-3 py-1 text-dark">0,00</td>
                                                <td class="pl-3 py-1 text-dark">688,20</td>
                                            </tr>
                                            <tr class="">
                                                <td class="pl-3 py-1 text-dark"><span style="color:#EB434D">41406NV2238</span> : 02023-MEGABANNER DESTACADO 1	</td>
                                                <td class="pl-3 py-1 text-dark">Beta10</td>
                                                <td class="pl-3 py-1 text-dark">1</td>
                                                <td class="pl-3 py-1 text-dark">1,000</td>
                                                <td class="pl-3 py-1 text-dark">740,00</td>
                                                <td class="pl-3 py-1 text-dark">740,00</td>
                                                <td class="pl-3 py-1 text-dark">7,00</td>
                                                <td class="pl-3 py-1 text-dark">0,00</td>
                                                <td class="pl-3 py-1 text-dark">688,20</td>
                                            </tr>
                                            <tr class="">
                                                <td class="pl-3 py-1 text-dark"><span style="color:#EB434D">41406NV2238</span> : 02023-MEGABANNER DESTACADO 1	</td>
                                                <td class="pl-3 py-1 text-dark">Beta10</td>
                                                <td class="pl-3 py-1 text-dark">1</td>
                                                <td class="pl-3 py-1 text-dark">1,000</td>
                                                <td class="pl-3 py-1 text-dark">740,00</td>
                                                <td class="pl-3 py-1 text-dark">740,00</td>
                                                <td class="pl-3 py-1 text-dark">7,00</td>
                                                <td class="pl-3 py-1 text-dark">0,00</td>
                                                <td class="pl-3 py-1 text-dark">688,20</td>
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
</template>

<script>
    import { mapMutations, mapActions, mapState } from "vuex";
    export default {
        name: "TableComponent",
        components: {
        },
        data() {
            return {
                publicPath: window.location.origin,
                desplegable1: 0,
                desplegable2: 0
            };
        },
        methods: {
            ...mapActions(["listBillsValidation"]),
            ...mapMutations([]),
            testDesplegable(status, dropdown){
                if(dropdown == 1){
                    this.desplegable1 = status;
                }
                if(dropdown == 2){
                    this.desplegable2 = status;
                }
            }
            
        },
        computed: {
                ...mapState(["errors", "invoice_validations"]),
        },
        mounted() {
            this.listBillsValidation();
        },
    };
    </script>