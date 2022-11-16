<template>
    <div id="div_print">
        <div class="row m-0" >
            <div class="col-12 d-flex flex-wrap justify-content-between" >
                <h3 class="color-blue">Exportar propuestas</h3>
                <AddButtonComponent
                        v-on:click="this.changeViewStatusProposals(1)"
                        :columns="'px-4 ml-auto'"
                        :text="'Volver'"
                        :id="'btn_add_user'"
                        :src="'/media/custom-imgs/flecha_btn_volver.svg'"
                        :width="16"
                        :height="16"
                    />
                <AddButtonComponent
                    @click.native="downloadFile()"
                    :columns="'px-4 mx-7'"
                    :text="'Exportar'"
                    :id="'btn_export'"
                    :src="'/media/custom-imgs/icono_btn_exportar.svg'"
                    :width="16"
                    :height="16"
                />
                <AddButtonComponent
                    @click.native="printPage()"
                    :columns="'px-4'"
                    :text="'Imprimir'"
                    :id="'btn_print_calendars_page'"
                    :src="'/media/custom-imgs/icono_btn_annadir_numero.svg'"
                    :width="25"
                    :height="25"
                />
            </div>
            <div class="col-12 d-flex flex-wrap mt-6">
                <div class="mx-2 col-2">
                    <span class="text-dark font-weight-bold mb-2">Num. propuesta</span>
                    <input v-model="num_proposal" type="text" class="form-control bg-gray mt-3 select-filter text-dark-gray" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" />
                </div>
                <div class="mx-2 col-2">
                    <span class="text-dark font-weight-bold mb-2">Consultor</span>
                    <select class="form-control bg-gray text-dark select-custom select-filter mt-3" :name="'select_consultant'" :id="'select_consultant'" v-model="select_consultant" data-style="select-lightgreen" @change="getConsultantSelect">
                        <option value="" selected>
                            Selecciona un consultor
                        </option>
                        <option :value="user.id" v-for="user in proposals.array_users"  :key="user.id" v-text="user.name + ' ' + user.surname" ></option>
                    </select>
                </div>
            
                <div class="mx-2 col-2">
                    <span class="text-dark font-weight-bold mb-2">Fecha desde</span>
                    <Calendar class="w-100 select-filter input-custom-calendar mt-3" inputId="date_from" v-model="date_from" autocomplete="off" dateFormat="dd-mm-yy" />
                </div>

                <div class="mx-2 col-2">
                    <span class="text-dark font-weight-bold mb-2">Fecha hasta</span>
                    <Calendar class="w-100 select-filter input-custom-calendar mt-3" inputId="date_to" v-model="date_to" autocomplete="off" dateFormat="dd-mm-yy"  />
                </div>

                <div class="mx-2 col-2 d-flex">
                    <select class="form-control bg-gray text-dark select-custom select-filter mt-auto" :name="'select_from_consultant'" :id="'select_from_consultant'" v-model="select_from_consultant" data-style="select-lightgreen" @change="getProductsSelect">
                        <option value="1" selected>Del consultor</option>
                        <option value="2">De la cartera asignada al consultor</option>
                        <option value="3">Responsable de publicaciones</option>
                    </select>
                </div>

                <div class="mx-2 col-2 mt-5">
                    <span class="text-dark font-weight-bold mb-2">Sector</span>
                    <select class="form-control bg-gray text-dark select-custom select-filter mt-3" :name="'select_sector'" :id="'select_sector'" v-model="select_sector" data-style="select-lightgreen" @change="getConsultantSelect">
                        <option value="" selected>
                            Filtro por sector
                        </option>
                        <option :value="sector.id" v-for="sector in config.articles.filter.array_sectors"  :key="sector.id" v-text="sector.name" ></option>
                    </select>
                </div>

                <div class="mx-2 col-2 d-flex">
                    <select class="form-control bg-gray text-dark select-custom select-filter mt-auto" :name="'select_status_order'" :id="'select_status_order'" v-model="select_status_order" data-style="select-lightgreen" @change="getProductsSelect">
                        <option value="1" selected>No han pasado la orden</option>
                        <option value="2">Han pasado a orden</option>
                        <option value="3">Todas</option>
                    </select>
                </div>
                <div class="mx-2 col-12 d-flex mt-10">
                    <button type="submit" class="btn bg-azul color-white px-35 font-weight-bolder" v-on:click="this.listProposals(1)">Aplicar filtro</button>
                </div>
            </div>
            <div class="col-12 mt-7" id="div_print2">
                <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-scroll datatable-loaded" id="list_calendars" style="width: 100%;">
                    <table class="datatable-table" style="display: block;">
                        <thead class="datatable-head">
                            <tr class="datatable-row" style="left: 0px;">
                                <th data-field="#id_user" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >Consultor</span>
                                </th>
                                <th data-field="#proposal_custom" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >Propuesta</span>
                                </th>
                                <th data-field="#status" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >Estado</span>
                                </th>
                                <th data-field="#code" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span>Código</span>
                                </th>
                                <th data-field="#name_contact" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >Nombre del cliente</span>
                                </th>
                                <th data-field="#date_proyect" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >Fecha</span>
                                </th>
                                <th data-field="#total_amount" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >Total</span>
                                </th>
                                <th data-field="#sector_name" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span>Sector</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="datatable-body ps" v-html="proposals.html_proposal_list">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
     import { mapMutations, mapActions, mapState } from "vuex";

     import AddButtonComponent from "../Partials/AddButtonComponent.vue";
     import Calendar from 'primevue/calendar';

     export default {
        name: "TableExportsComponent",
        components: {
            AddButtonComponent,
            Calendar
        },
        data() {
            return {
                publicPath: window.location.origin,
                num_proposal: '',
                select_consultant: '',
                date_from: '',
                date_to: '',
                select_from_consultant: '1',
                select_sector: '',
                select_status_order: '1',
                datatable: null,
                type: 0
            };
        },
        methods: {
            ...mapMutations(["changeViewStatusProposals"]),
            ...mapActions(["getUsers", "listProposalsToExport", "downloadListCalendarsCsv"]),
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
            //Descargar excell
            downloadFile(){
                var date_ms_from = this.date_from;
                var date_ms_to = this.date_to;
                if(!isNaN(Date.parse(this.date_from))){
                    date_ms_from = Date.parse(this.date_from);
                    date_ms_from = this.$utils.customFormDate(date_ms_from);
                }
                if(!isNaN(Date.parse(this.date_to))){
                    date_ms_to = Date.parse(this.date_to);
                    date_ms_to = this.$utils.customFormDate(date_ms_to);
                }
            
                window.open(this.publicPath + "/admin/download_list_proposals_csv?type="+this.type
                                                                                +"&num_proposal="+this.num_proposal
                                                                                +"&select_consultant="+this.select_consultant
                                                                                +"&select_sector="+this.select_sector
                                                                                +"&date_from="+date_ms_from
                                                                                +"&date_to="+date_ms_to
                                                                                ,"_self")
            },
            printPage(){
                // Get HTML to print from element
                const prtHtml = document.getElementById('div_print2').innerHTML;

                // Get all stylesheets HTML
                let stylesHtml = '';
                for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
                    stylesHtml += node.outerHTML;
                }

                // Open the print window
                const WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');

                WinPrint.document.write(`<!DOCTYPE html>
                            <head>
                                ${stylesHtml}
                            </head>
                            <body>
                                ${prtHtml}
                            </body>
                            </html>`);

                setTimeout(() => {
                            WinPrint.document.close();
                            WinPrint.focus();
                            WinPrint.print();
                            WinPrint.close();
                            }, 500);
               
            },
            listProposals(type){
                this.type = type;
                if(type == 1 || type == undefined){

                    var date_ms_from = this.date_from;
                    var date_ms_to = this.date_to;
                    if(!isNaN(Date.parse(this.date_from))){
                        date_ms_from = Date.parse(this.date_from);
                        date_ms_from = this.$utils.customFormDate(date_ms_from);
                    }
                    if(!isNaN(Date.parse(this.date_to))){
                        date_ms_to = Date.parse(this.date_to);
                        date_ms_to = this.$utils.customFormDate(date_ms_to);
                    }
                    var param = {
                        type: type,
                        num_proposal: this.num_proposal,
                        select_consultant: this.select_consultant,
                        select_sector: this.select_sector,
                        date_from: date_ms_from,
                        date_to: date_ms_to
                    }
                    this.listProposalsToExport(param);
                }
            },
        },
        computed: {
            ...mapState(["config", "proposals"])
        },
        mounted() {
            this.getNow();
            var param = {
                type: 0
            }
            this.listProposalsToExport(param);
            this.getUsers(1);
        }
    };
</script>