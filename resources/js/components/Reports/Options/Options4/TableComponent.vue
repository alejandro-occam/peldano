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
                    v-on:click="downloadReportListByChannelFront()"
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
            <span class="text-dark font-weight-bold mb-2">Departamento</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_department" :name="'select_department'" :id="'select_department'" data-style="select-lightgreen" @change="getSectionSelect">
                <option value="0" selected>
                    Filtro por departamento
                </option>
                <option :value="department.id" v-for="department in config.articles.filter.array_departments" :key="department.id" v-text="department.nomenclature + '-' + department.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Sección</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_section" :name="'select_section'" :id="'select_section'" data-style="select-lightgreen" @change="getChannelSelect">
                <option value="0" selected>
                    Filtro por sección
                </option>
                <option :value="section.id" v-for="section in config.articles.filter.array_sections"  :key="section.id" v-text="section.nomenclature + '-' + section.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Canal</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_channel" :name="'select_channel'" :id="'select_channel'" data-style="select-lightgreen" @change="getProjectSelect">
                <option value="0" selected>
                    Filtro por canal
                </option>
                <option :value="channel.id" v-for="channel in config.articles.filter.array_channels" :key="channel.id" v-text="channel.nomenclature + '-' + channel.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Proyecto</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_project" :name="'select_project'" :id="'select_project'" data-style="select-lightgreen" @change="getChaptersSelect">
                <option value="0" selected>
                    Filtro por proyecto
                </option>
                <option :value="project.id" v-for="project in config.articles.filter.array_projects" :key="project.id" v-text="project.nomenclature + '-' + project.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Capítulo</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_chapter" :name="'select_chapter'" :id="'select_chapter'" data-style="select-lightgreen" @change="getBatchsSelect">
                <option value="0" selected>
                    Filtro por capítulo
                </option>
                <option :value="chapter.id" v-for="chapter in config.articles.filter.array_chapters" :key="chapter.id" v-text="chapter.nomenclature + '-' + chapter.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Consultor</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_consultant" :name="'select_consultant'" :id="'select_consultant'" data-style="select-lightgreen">
                <option value="0" selected>
                    Filtro por consultor
                </option>
                <option :value="user.id" v-for="user in proposals.array_users" :key="user.id" v-text="user.name + ' ' + user.surname"></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Órdenes</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_order" :name="'select_order'" :id="'select_order'" data-style="select-lightgreen">
                <option :value="1">Firmadas</option>
                <option :value="2">Editando</option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-auto">
            <select class="form-control bg-gray text-dark select-custom select-filter" v-model="select_exchange" :name="'select_exchange'" :id="'select_exchange'" data-style="select-lightgreen">
                <option :value="1">Sin intercambios</option>
                <option :value="2">Con intercambios</option>
                <option :value="3">Sólo intercambios</option>
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

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Datos a usar</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_data_to_use" :name="'select_data_to_use'" :id="'select_data_to_use'" data-style="select-lightgreen">
                <option :value="1">Del consultor</option>
                <option :value="2">De la cartera asignada al consultor</option>
                <option :value="3">Responsable de publicaciones</option>
            </select>
        </div>
        
        <div class="mx-2 col-12 d-flex mt-10">
            <button type="submit" class="btn bg-azul color-white px-35 font-weight-bolder" v-on:click="filteRreportListSalesOrdersSigned()">Generar informe</button>
        </div>
    </div>
    <Divider class="my-15" />
    <div class="col-12 mt-15" v-if="Number(reports.array_bills_orders.length) > 0">
        <table width="100%" cellpadding="2" cellspacing="1">
            <thead class="custom-columns-datatable">
                <tr>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>DEP</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>TIPO</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 125px;"><span>PERIODO</span></th>
                    <template v-for="index_report_date in Number(reports.array_dates.length)" :key="index_report_date.id">
                        <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>{{ reports.array_dates[index_report_date - 1].date }}</span></th>
                    </template>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>TOTAL</span></th>
                </tr>
            </thead>
            <tbody>  
                <template v-for="index_bill_order in Number(reports.array_bills_orders.length)" :key="index_bill_order.id">
                    <tr class="row-product bg-white" v-if="reports.array_bills_orders[index_bill_order - 1].type_obj == 1">
                        <td class="td-border-right bg-light-blue-table pl-5" :rowspan="4">{{ reports.array_bills_orders[index_bill_order - 1].dep }}</td>
                        <td class="td-border-right pl-5" :rowspan="4">{{ reports.array_bills_orders[index_bill_order - 1].type }}</td>
                    </tr>
                    <tr class="row-product bg-white" v-else-if="reports.array_bills_orders[index_bill_order - 1].type_obj == 2">
                        <td class="td-border-right bg-light-blue-table pl-5 text-align-center" :rowspan="4" colspan="2">{{ reports.array_bills_orders[index_bill_order - 1].dep.toUpperCase() }}</td>
                    </tr>
                    <tr class="row-product bg-white"  v-else-if="reports.array_bills_orders[index_bill_order - 1].type_obj == 3">
                        <td class="td-border-right bg-light-blue-diference-table pl-5" :rowspan="4" colspan="2">{{ reports.array_bills_orders[index_bill_order - 1].dep }}</td>
                    </tr>
                    <tr class="row-product bg-white" v-else>
                        <td class="td-border-right bg-light-blue-table pl-5" :rowspan="4" colspan="2">TOTAL</td>
                    </tr>
                    <tr class="row-product bg-white">
                        <td class="td-border-right pl-3">{{ reports.array_bills_orders[index_bill_order - 1].old.period }}</td>
                        <template v-for="index_amounts in Number(reports.array_bills_orders[index_bill_order - 1].new.amounts.length)" :key="index_amounts.id">
                            <td class="text-align-center td-border-right">{{ reports.array_bills_orders[index_bill_order - 1].old.amounts[index_amounts - 1] }}</td>
                        </template>
                    </tr>   
                    <tr class="row-product bg-white">
                        <td class="td-border-right pl-3 bg-light-blue-table">{{ reports.array_bills_orders[index_bill_order - 1].new.period }}</td>
                        <template v-for="index_amounts in Number(reports.array_bills_orders[index_bill_order - 1].new.amounts.length)" :key="index_amounts.id">
                            <td class="text-align-center td-border-right bg-light-blue-table">{{ reports.array_bills_orders[index_bill_order - 1].new.amounts[index_amounts - 1] }}</td>
                        </template>
                    </tr>   
                    <tr class="row-product bg-white">
                        <td class="td-border-right pl-3 bg-purple color-white">{{ reports.array_bills_orders[index_bill_order - 1].diference.period }}</td>
                        <template v-for="index_amounts in Number(reports.array_bills_orders[index_bill_order - 1].new.amounts.length)" :key="index_amounts.id">
                            <td class="text-align-center td-border-right bg-light-blue-diference-table">{{ reports.array_bills_orders[index_bill_order - 1].diference.amounts[index_amounts - 1] }}</td>
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
        name: "TableComponentOption4",
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
                select_project: '0',
                select_chapter: '0',
                select_consultant: '0',
                select_order: '1',
                select_exchange: '1',
                date_from: '',
                date_to: '',
                select_data_to_use: '1',
            };
        },
        computed: {
            ...mapState(["errors", "config", "proposals", "reports"]),
        },
        mounted() {
            this.getUsers(1);
            this.getNow();
            var params = {
                type: 1
            }
            this.getDepartments(params);
        },
        methods: {
            ...mapActions(["getUsers", "getDepartments", "getSections", "getChannels", "getProjects", "getChapters", "reportListSalesOrdersSigned", "downloadReportListByChannel"]),
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
                this.select_project = '0';
                this.select_chapter = '0';
                this.select_batch = '0';
                this.select_article = '0';
                this.amount = '';
                this.date = [];
                this.show_amount_dates = false;
                var params = {
                    type: 1,
                    select_articles_department: this.select_department
                }
                this.getSections(params);
            },
            getChannelSelect(){
                this.select_channel = '0';
                this.select_project = '0';
                this.select_chapter = '0';
                this.select_batch = '0';
                this.select_article = '0';
                this.amount = '';
                this.date = [];
                this.show_amount_dates = false;
                var params = {
                    type: 1,
                    select_articles_section: this.select_section
                }
                this.getChannels(params);
            },
            getProjectSelect(){
                this.select_project = '0';
                this.select_chapter = '0';
                this.select_batch = '0';
                this.select_article = '0';
                this.amount = '';
                this.date = [];
                this.show_amount_dates = false;
                var params = {
                    type: 1,
                    select_articles_channel: this.select_channel
                }
                this.getProjects(params);
            },
            getChaptersSelect(){
                this.select_chapter = '0';
                this.select_batch = '0';
                this.select_article = '0';
                this.amount = '';
                this.date = [];
                this.show_amount_dates = false;
                var params = {
                    type: 1,
                    select_articles_project: this.select_project
                }
                this.getChapters(params);
            },
            filteRreportListSalesOrdersSigned(){
                var date_from = this.date_from;
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
                this.date_to_custom = date_to;

                var params = {
                    select_department: this.select_department,
                    select_section: this.select_section,
                    select_channel: this.select_channel,
                    select_project: this.select_project,
                    select_chapter: this.select_chapter,
                    select_consultant: this.select_consultant,
                    select_order: this.select_order,
                    select_compare: this.select_compare,
                    date_from: date_from,
                    date_to: date_to
                }

                this.reportListSalesOrdersSigned(params);
            },
            downloadReportListByChannelFront(){
                this.downloadReportListByChannel();
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
            }
        }
    };
</script>