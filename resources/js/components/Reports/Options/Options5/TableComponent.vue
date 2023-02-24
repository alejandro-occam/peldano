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
                    v-on:click="changeViewStatusProposals(3)"
                />
                <RouterButton
                    :columns="'ml-auto mr-7'"
                    :text="'Volver'"
                    :id="'btn_return'"
                    :src="'/media/custom-imgs/flecha_btn_volver.svg'"
                    :width="16"
                    :height="16"
                />
            </div>
        </div>
        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Consultor</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_consultant" :name="'select_consultant'" :id="'select_consultant'" data-style="select-lightgreen">
                <option value="0" selected>
                    Selecciona un consultor
                </option>
                <option :value="user.id" v-for="user in proposals.array_users" :key="user.id" v-text="user.name + ' ' + user.surname"></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-auto">
            <select class="form-control bg-gray text-dark select-custom select-filter mt-auto" v-model="select_payment" :name="'select_payment'" :id="'select_payment'" data-style="select-lightgreen">
                <option :value="1">Con abonos</option>
                <option :value="2">Sin abonos</option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Vencimiento del</span>
            <Calendar class="w-100 select-filter input-custom-calendar mt-3" v-model="expiration_from" inputId="expiration_from" autocomplete="off" dateFormat="dd-mm-yy" />
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Vencimiento hasta</span>
            <Calendar class="w-100 select-filter input-custom-calendar mt-3" v-model="expiration_to" inputId="expiration_to" autocomplete="off" dateFormat="dd-mm-yy"  />
        </div>

        <div class="mx-2 col-2 mt-5"></div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Número de orden</span>
            <input type="text" class="form-control bg-gray mt-3 select-filter text-dark-gray" v-model="num_order" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" />
        </div>

        <div class="mx-2 col-2  mt-5">
            <span class="text-dark font-weight-bold mb-2">ID de contacto</span>
            <input type="text" class="form-control bg-gray mt-3 select-filter text-dark-gray" v-model="contact_id" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" />
        </div>

        <div class="mx-2 col-2  mt-5">
            <span class="text-dark font-weight-bold mb-2">ID cliente (SAGE)</span>
            <input type="text" class="form-control bg-gray mt-3 select-filter text-dark-gray" v-model="client_id_sage" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" />
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Estado de la factura</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_satus_bill" :name="'select_satus_bill'" :id="'select_satus_bill'" data-style="select-lightgreen">
                <option :value="1">No cobradas o devueltos</option>
                <option :value="2">Ni cobrado ni devuelto</option>
                <option :value="3">Cobrado</option>
                <option :value="4">Devuelto</option>
            </select>
        </div>

        <div class="mx-2 mt-auto col-2 d-grid">
            <div v-if="this.show_all == 0">
                <button class="purple-border btn mr-4 font-weight-bold d-flex py-4" @click="this.changeStatusShowAll(1)">
                    <div class="purple-circle mr-auto my-auto">
                        <div class="white-circle-purple"></div>
                    </div>
                    <span class="px-10">Mostrar todo</span>
                </button>
            </div>
            <div v-else>
                <button  class="bg-purple btn mr-4 font-weight-bold color-white d-flex py-4" @click="this.changeStatusShowAll(0)">
                    <div class="white-circle mr-auto my-auto">
                        <div class="purple-circle-white"></div>
                    </div>
                    <span class="px-10">Mostrar todo</span>
                </button>
            </div>
        </div>
        
        <div class="mx-2 col-12 d-flex mt-10">
            <button type="submit" class="btn bg-azul color-white px-35 font-weight-bolder" v-on:click="loadTable()">Aplicar filtro</button>
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
                    <th tabindex="0" class="pl-3 pb-3" aria-controls="example" rowspan="4" colspan="1" style="width: 25px;"><span>CONSULTOR</span></th>
                    <th tabindex="0" class="pl-3 pb-3" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>ORDEN</span></th>
                    <th tabindex="0" class="pl-3 pb-3" aria-controls="example" rowspan="1" colspan="1" style="width: 50px;"><span>CÓDIGO</span></th>
                    <th tabindex="0" class="pl-3 pb-3" aria-controls="example" rowspan="1" colspan="1" style="width: 150px;"><span>CLIENTE</span></th>
                    <th tabindex="0" class="pl-3 pb-3" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FACTURA</span></th>
                    <th tabindex="0" class="pl-3 pb-3" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>RECIBO</span></th>
                    <th tabindex="0" class="pl-3 pb-3" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>EMISIÓN</span></th>
                    <th tabindex="0" class="pl-3 pb-3" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>VENCIMIENTO</span></th>
                    <th tabindex="0" class="pl-3 pb-3" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FORMA PAGO</span></th>
                    <th tabindex="0" class="pl-3 pb-3" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>ESTADO</span></th>
                    <th tabindex="0" class="pl-3 pb-3" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>TOTAL</span></th>
                </tr>
            </thead>
            <tbody>  
                <tr class="row-product bg-white" v-for="index_report in Number(reports.array_bills_orders.length)" :key="index_report.id">
                    <td class="pl-3 py-5 text-dark">{{ reports.array_bills_orders[index_report - 1].id_consultant }}</td>
                    <td class="pl-3 text-gray">{{ reports.array_bills_orders[index_report - 1].id_order }}</td>
                    <td class="pl-3 text-gray">{{ reports.array_bills_orders[index_report - 1].id_sage }}</td>
                    <td class="pl-3 text-dark">{{ reports.array_bills_orders[index_report - 1].name_company }}</td>
                    <td class="pl-3 text-gray">{{ reports.array_bills_orders[index_report - 1].id }}</td>
                    <td class="pl-3 text-gray">1</td>
                    <td class="pl-3 text-gray">{{ reports.array_bills_orders[index_report - 1].date }}</td>
                    <td class="pl-3 text-gray">29-09-2022</td>
                    <td class="pl-3 text-gray">Recibo a 30 dias</td>
                    <td class="pl-3 text-gray" v-if="reports.array_bills_orders[index_report - 1].payment == 0">No cobrado</td>
                    <td class="pl-3 text-gray" v-if="reports.array_bills_orders[index_report - 1].payment == 1">Cobrado</td>
                    <td class="pl-3 text-gray">{{ reports.array_bills_orders[index_report - 1].amount }}</td>
                </tr>  
                <!--<tr class="row-product bg-white ">
                    <td class="pl-3 py-5 text-dark">058</td>
                    <td class="pl-3 text-gray">39107</td>
                    <td class="pl-3 text-gray">001137</td>
                    <td class="pl-3 text-dark">Camping Neptuno Costa Brava S</td>
                    <td class="pl-3 text-gray">2202760</td>
                    <td class="pl-3 text-gray">1</td>
                    <td class="pl-3 text-gray">30-08-2022</td>
                    <td class="pl-3 text-gray">29-09-2022</td>
                    <td class="pl-3 text-gray">Recibo a 30 dias</td>
                    <td class="pl-3 text-gray">No cobrado</td>
                    <td class="pl-3 text-gray">1.694,00</td>
                </tr>   
                <tr class="row-product bg-white ">
                    <td class="pl-3 py-5 text-dark">058</td>
                    <td class="pl-3 text-gray">39107</td>
                    <td class="pl-3 text-gray">001137</td>
                    <td class="pl-3 text-dark">Camping Neptuno Costa Brava S</td>
                    <td class="pl-3 text-gray">2202760</td>
                    <td class="pl-3 text-gray">1</td>
                    <td class="pl-3 text-gray">30-08-2022</td>
                    <td class="pl-3 text-gray">29-09-2022</td>
                    <td class="pl-3 text-gray">Recibo a 30 dias</td>
                    <td class="pl-3 text-gray">No cobrado</td>
                    <td class="pl-3 text-gray">1.694,00</td>
                </tr>   
                <tr class="row-product bg-white ">
                    <td class="pl-3 py-5 text-dark">058</td>
                    <td class="pl-3 text-gray">39107</td>
                    <td class="pl-3 text-gray">001137</td>
                    <td class="pl-3 text-dark">Camping Neptuno Costa Brava S</td>
                    <td class="pl-3 text-gray">2202760</td>
                    <td class="pl-3 text-gray">1</td>
                    <td class="pl-3 text-gray">30-08-2022</td>
                    <td class="pl-3 text-gray">29-09-2022</td>
                    <td class="pl-3 text-gray">Recibo a 30 dias</td>
                    <td class="pl-3 text-gray">No cobrado</td>
                    <td class="pl-3 text-gray">1.694,00</td>
                </tr>--> 
                <tr class="tr-total-datatable ">
                    <td colspan="10" class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                    <td  class="text-align-center"><span class="font-weight-bolder">{{ reports.total_amount }}€</span></td>
                </tr>    
            </tbody>
        </table>
    </div>
</template>

<script>
    import { mapMutations, mapActions, mapState } from "vuex";

    import AddButtonComponent from "../../../Partials/AddButtonComponent.vue";
    import RouterButton from "../../../Partials/RouterButton.vue";
    import Calendar from 'primevue/calendar';
    import Divider from 'primevue/divider';
    import Chart from 'primevue/chart';

    export default {
        name: "TableComponentOption5",
        components: {
            AddButtonComponent,
            RouterButton,
            Calendar,
            Divider,
            Chart
        },
        data() {
            return {
                publicPath: window.location.origin,
                select_consultant: '0',
                select_payment: '2',
                expiration_from: '',
                expiration_to: '',
                num_order: '',
                contact_id: '',
                client_id_sage: '',
                select_satus_bill: '1',
                show_all: 0,
            };
        },
        computed: {
            ...mapState(["errors", "config", "proposals", "reports"]),
        },
        mounted() {
            this.getNow();
            this.getUsers(1);
            //this.loadTable();
        },
        methods: {
            ...mapActions(["getUsers", "reportUnpaidInvoices"]),
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
                this.expiration_from = date;
                this.expiration_to = date;
            },
            changeStatusShowAll(status){
                this.show_all = status;
            },
            loadTable(){
                var params = {
                    select_consultant: this.select_consultant,
                    select_payment: this.select_payment,
                    expiration_from: this.expiration_from,
                    expiration_to: this.expiration_to,
                    num_order: this.num_order,
                    contact_id: this.contact_id,
                    client_id_sage: this.client_id_sage,
                    select_satus_bill: this.select_satus_bill,
                }
                this.reportUnpaidInvoices(params);
            }
        }
    };
</script>