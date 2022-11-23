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
            <span class="text-dark font-weight-bold mb-2">Consultor</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_consultant" :name="'select_consultant'" :id="'select_consultant'" data-style="select-lightgreen">
                <option value="" selected>
                    Selecciona un consultor
                </option>
                <option :value="user.id" v-for="user in proposals.array_users" :key="user.id" v-text="user.name + ' ' + user.surname"></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Fecha desde</span>
            <Calendar class="w-100 select-filter input-custom-calendar mt-3" v-model="date_from" inputId="date_from" autocomplete="off" dateFormat="dd-mm-yy" />
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Fecha hasta</span>
            <Calendar class="w-100 select-filter input-custom-calendar mt-3" v-model="date_to" inputId="date_to" autocomplete="off" dateFormat="dd-mm-yy"  />
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
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 25px;"><span>CONSULTOR</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>FACT. - RECIBO</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 125px;"><span>ORDEN</span></th>
                    <th tabindex="0" class="pb-3" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>CLIENTE</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>BASE IMP.</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>TOTAL</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>RECIBO</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FECHA</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>VENCIMIENTO</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>COBRADA</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>COMISIÓN</span></th>
                </tr>
            </thead>
            <tbody>  
                <!--ROW 1-->
                <tr class="row-product bg-white">
                    <td class="pl-3 py-5 text-dark text-align-center">Elizabeth Canto</td>
                    <td class="text-align-center text-gray">
                        <div v-if="this.desplegable1 == 0"  class="dropdown cursor-pointer out py-1" v-on:click="testDesplegable(1, 1)">
                            <span>A2202156-1</span>
                            <img class="ml-3" width="15" src="/media/custom-imgs/flecha_abajo_badge.svg" />
                        </div>
                        <div v-else class="dropdown cursor-pointer in py-1" v-on:click="testDesplegable(0, 1)">
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
                <template v-if="this.desplegable1 == 1">
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
                <!--ROW 2-->
                <tr class="row-product bg-white">
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
        name: "TableComponentOption3",
        components: {
            AddButtonComponent,
            Calendar,
            Divider,
            Chart
        },
        data() {
            return {
                publicPath: window.location.origin,
                select_consultant: '',
                date_from: '',
                date_to: '',
                desplegable1: 0,
                desplegable2: 0
            };
        },
        computed: {
            ...mapState(["errors", "proposals"]),
        },
        mounted() {
            this.getUsers(1);
            this.getNow();
        },
        methods: {
            ...mapActions(["getUsers"]),
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
            testDesplegable(status, dropdown){
                if(dropdown == 1){
                    this.desplegable1 = status;
                }
                if(dropdown == 2){
                    this.desplegable2 = status;
                }
            }
        }
    };
</script>