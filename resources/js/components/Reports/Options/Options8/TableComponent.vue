<template>
    <div class="col-12 d-flex flex-wrap mt-6">
        <div class="col-12 d-flex flex-wrap justify-content-between mb-10">
            <h3 class="color-blue my-auto">Opciones de informe</h3>
            <div class="d-flex">
                <!--<AddButtonComponent
                    :columns="'px-4 ml-auto mr-7'"
                    :text="'Exportar'"
                    :id="'btn_export'"
                    :src="'/media/custom-imgs/icono_btn_exportar.svg'"
                    :width="16"
                    :height="16"
                    v-on:click="changeViewStatusProposals(3)"
                />-->
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
            <span class="text-dark font-weight-bold mb-2">Departamento</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_department" :name="'select_department'" :id="'select_department'" data-style="select-lightgreen" @change="getSectionSelect">
                <option value="0" selected>
                    Filtro por departamento
                </option>
                <option :value="department.id" v-for="department in config.articles.filter.array_departments" :key="department.id" v-text="department.nomenclature + '-' + department.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Consultor</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_consultant" :name="'select_consultant'" :id="'select_consultant'" data-style="select-lightgreen">
                <option value="0" selected>
                    Filtro por consultor
                </option>
                <option :value="user.id" v-for="user in proposals.array_users"  :key="user.id" v-text="user.name + ' ' + user.surname" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Objetivo del año</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="date_from" :name="'select_date_from'" :id="'select_date_from'" data-style="select-lightgreen">
                <option :value="year" v-for="year in array_dates_from" :key="year" v-text="year" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Comparado con meses de</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="date_to" :name="'select_date_to'" :id="'select_date_to'" data-style="select-lightgreen">
                <option :value="year" v-for="year in array_dates_to" :key="year" v-text="year" ></option>            
            </select>
        </div>
        <div class="mx-2 col-12 d-flex mt-10">
            <button v-on:click="filteRreportListGoal()" type="submit" class="btn bg-azul color-white px-35 font-weight-bolder">Generar informe</button>
        </div>
    </div>
    <Divider class="my-15" />
    <div class="col-12 mt-15" v-if="Number(reports.array_bills_orders.length) > 0">
        <table width="100%" cellpadding="2" cellspacing="1">
            <thead class="custom-columns-datatable">
                <tr>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>DEP</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>SEC</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>CAN</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>PRO</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 140px;"><span>PERIODO</span></th>
                    <template v-for="index_report_date in Number(reports.array_dates.length)" :key="index_report_date.id">
                        <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>{{ reports.array_dates[index_report_date - 1].date }}</span></th>
                    </template>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>TOTAL</span></th>
                </tr>
            </thead>
            <tbody>  
                <template v-for="index_bill_order in Number(reports.array_bills_orders.length)" :key="index_bill_order.id">
                    <tr class="row-product bg-white" v-if="reports.array_bills_orders[index_bill_order - 1].type_obj == 1">
                        <td class="td-border-right bg-light-blue-table pl-5" :rowspan="7">{{ reports.array_bills_orders[index_bill_order - 1].dep }}</td>
                        <td class="td-border-right pl-5" :rowspan="7">{{ reports.array_bills_orders[index_bill_order - 1].sec_name }}</td>
                        <td class="td-border-right pl-5" :rowspan="7">{{ reports.array_bills_orders[index_bill_order - 1].type }}</td>
                        <td class="td-border-right pl-5" :rowspan="7">{{ reports.array_bills_orders[index_bill_order - 1].pro_name }}</td>
                    </tr>
                    <tr class="row-product bg-white" v-if="reports.array_bills_orders[index_bill_order - 1].type_obj == 2">
                        <td class="td-border-right bg-light-blue-table pl-5 text-align-center" :rowspan="7" colspan="4">{{ reports.array_bills_orders[index_bill_order - 1].dep_name.toUpperCase() }}</td>
                    </tr>
                    <tr class="row-product bg-white"  v-else-if="reports.array_bills_orders[index_bill_order - 1].type_obj == 3">
                            <td class="td-border-right bg-light-blue-table pl-5" :rowspan="7" colspan="4">TOTAL</td>
                        </tr>
                    <tr class="row-product bg-white">
                        <td class="td-border-right pl-3">{{ reports.array_bills_orders[index_bill_order - 1].obj_men.period }}</td>
                        <template v-for="index_amounts in Number(reports.array_bills_orders[index_bill_order - 1].obj_men.amounts.length)" :key="index_amounts.id">
                            <td class="text-align-center td-border-right">{{ reports.array_bills_orders[index_bill_order - 1].obj_men.amounts[index_amounts - 1] }}</td>
                        </template>
                    </tr>   
                    <tr class="row-product bg-white">
                        <td class="td-border-right pl-3 bg-light-blue-table">{{ reports.array_bills_orders[index_bill_order - 1].fac_men.period }}</td>
                        <template v-for="index_amounts in Number(reports.array_bills_orders[index_bill_order - 1].fac_men.amounts.length)" :key="index_amounts.id">
                            <td class="text-align-center td-border-right bg-light-blue-table">{{ reports.array_bills_orders[index_bill_order - 1].fac_men.amounts[index_amounts - 1] }}</td>
                        </template>
                    </tr>   
                    <tr class="row-product bg-white">
                        <td class="td-border-right pl-3 bg-purple color-white">{{ reports.array_bills_orders[index_bill_order - 1].cum_men.period }}</td>
                        <template v-for="index_amounts in Number(reports.array_bills_orders[index_bill_order - 1].cum_men.amounts.length)" :key="index_amounts.id">
                            <td v-if="reports.array_bills_orders[index_bill_order - 1].cum_men.amounts[index_amounts - 1].situation == '+'" style="color: #090;" class="text-align-center td-border-right bg-light-blue-diference-table">{{ reports.array_bills_orders[index_bill_order - 1].cum_men.amounts[index_amounts - 1].amount }}</td>
                            <td v-else-if="reports.array_bills_orders[index_bill_order - 1].cum_men.amounts[index_amounts - 1].situation == '-'" style="color: #F00;" class="text-align-center td-border-right bg-light-blue-diference-table">{{ reports.array_bills_orders[index_bill_order - 1].cum_men.amounts[index_amounts - 1].amount }}</td>
                            <td v-else class="text-align-center td-border-right bg-light-blue-diference-table">{{ reports.array_bills_orders[index_bill_order - 1].cum_men.amounts[index_amounts - 1].amount }}</td>
                        </template>
                    </tr>
                    
                    <tr class="row-product bg-white">
                        <td class="td-border-right pl-3">{{ reports.array_bills_orders[index_bill_order - 1].obj_total.period }}</td>
                        <template v-for="index_amounts in Number(reports.array_bills_orders[index_bill_order - 1].obj_total.amounts.length)" :key="index_amounts.id">
                            <td class="text-align-center td-border-right">{{ reports.array_bills_orders[index_bill_order - 1].obj_total.amounts[index_amounts - 1] }}</td>
                        </template>
                    </tr>   
                    <tr class="row-product bg-white">
                        <td class="td-border-right pl-3 bg-light-blue-table">{{ reports.array_bills_orders[index_bill_order - 1].fac_total.period }}</td>
                        <template v-for="index_amounts in Number(reports.array_bills_orders[index_bill_order - 1].fac_total.amounts.length)" :key="index_amounts.id">
                            <td class="text-align-center td-border-right bg-light-blue-table">{{ reports.array_bills_orders[index_bill_order - 1].fac_total.amounts[index_amounts - 1] }}</td>
                        </template>
                    </tr>   
                    <tr class="row-product bg-white">
                        <td class="td-border-right pl-3 bg-purple color-white">{{ reports.array_bills_orders[index_bill_order - 1].cum_total.period }}</td>
                        <template v-for="index_amounts in Number(reports.array_bills_orders[index_bill_order - 1].cum_total.amounts.length)" :key="index_amounts.id">
                            <td v-if="reports.array_bills_orders[index_bill_order - 1].cum_total.amounts[index_amounts - 1].situation == '+'" style="color: #090;" class="text-align-center td-border-right bg-light-blue-diference-table">{{ reports.array_bills_orders[index_bill_order - 1].cum_total.amounts[index_amounts - 1].amount }}</td>
                            <td v-else-if="reports.array_bills_orders[index_bill_order - 1].cum_total.amounts[index_amounts - 1].situation == '-'" style="color: #F00;" class="text-align-center td-border-right bg-light-blue-diference-table">{{ reports.array_bills_orders[index_bill_order - 1].cum_total.amounts[index_amounts - 1].amount }}</td>
                            <td v-else class="text-align-center td-border-right bg-light-blue-diference-table">{{ reports.array_bills_orders[index_bill_order - 1].cum_total.amounts[index_amounts - 1].amount }}</td>
                        </template>
                    </tr>
                </template>
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
        name: "TableComponentOption8",
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
                select_department: '0',
                select_section: '0',
                select_channel: '0',
                select_consultant: '0',
                deactivated_products_switch: '0',
                select_order: '1',
                select_exchange: '1',
                date_from: '',
                date_to: '',
                date_limit_switch: '0',
                select_data_to_use: '1',
                array_dates_from: [],
                array_dates_to: []
            };
        },
        computed: {
            ...mapState(["errors", "config", "proposals", "reports"]),
        },
        mounted() {
            //Objetivo del año
            this.date_from = new Date().getFullYear();
            this.array_dates_from.push(new Date().getFullYear());
            for(var i=1; i<=4; i++){
                this.array_dates_from.push(new Date().getFullYear() - i);
            }

            //Comparado con meses de
            this.date_to = new Date().getFullYear() - 1;
            this.array_dates_to.push(new Date().getFullYear() - 1);
            for(var i=1; i<=4; i++){
                this.array_dates_to.push(new Date().getFullYear() - (i + 1));
            }
            this.getUsers(1);
            //this.getNow();
            var params = {
                type: 1
            }
            this.getDepartments(params);
        },
        methods: {
            ...mapActions(["getUsers",  "getDepartments", "getSections", "getChannels", "reportGoal"]),
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
                const date_to = day + '-' + month + '-' + today.getFullYear();
                
                const date_from =  '01-01-' + today.getFullYear();
                this.date_from = date_from;
                this.date_to = date_to;
            },
            getSectionSelect(){
                this.select_section = '0';
                this.select_channel = '0';
                var params = {
                    type: 1,
                    select_articles_department: this.select_department
                }
                this.getSections(params);
            },
            getChannelSelect(){
                this.select_channel = '0';
                var params = {
                    type: 1,
                    select_articles_section: this.select_section
                }
                this.getChannels(params);
            },
            filteRreportListGoal(){
                /*var date_from = this.date_from;
                if(!this.isValidDate(this.date_from)){
                    var date_ms_from = Date.parse(this.date_from);
                    date_from = this.$utils.customFormDate(date_ms_from);
                }
                this.date_from_custom = date_from;
               
                var date_to = this.date_to;
                if(!this.isValidDate(this.date_to)){
                    var date_ms_to = Date.parse(this.date_to);
                    date_to = this.$utils.customFormDate(date_ms_to);
                }
                this.date_to_custom = date_to;*/

                var params = {
                    select_department: this.select_department,
                    /*select_section: this.select_section,
                    select_channel: this.select_channel,*/
                    select_consultant: this.select_consultant,
                    //select_order: this.select_order,
                    date_from: this.date_from,
                    date_to: this.date_to
                }
                this.reportGoal(params);
            },
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
        }
    };
</script>