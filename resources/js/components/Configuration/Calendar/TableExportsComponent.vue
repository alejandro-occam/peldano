<template>
    <div id="div_print">
        <div class="row m-0" >
            <div class="col-12 d-flex flex-wrap justify-content-between" >
                <h3 class="color-blue">Exportar calendario</h3>
                <AddButtonComponent
                        @click.native="changeShowViewCalendar(1)"
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
            <div class="col-12 d-flex flex-wrap justify-content-between mt-4">
                <div class="d-flex align-items-center justify-content-center w-15">
                    <select class="form-control w-100 bg-gray text-gray select-custom select-filter" :name="'select_calendar_filter'" :id="'select_calendar_filter_excel'" v-model="select_calendar_filter" data-style="select-lightgreen" @change="reloadList">
                        <option value="0" selected>
                            Elige un calendario
                        </option>
                        <option :value="calendar.id" v-for="calendar in config.calendars.array_calendars"  :key="calendar.id" v-text="calendar.name" ></option>
                    </select>
                </div>
            </div>
            <div class="col-12 mt-7" id="div_print2">
                <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-scroll datatable-loaded" id="list_calendars" style="width: 100%;">
                    <table class="datatable-table" style="display: block;">
                        <thead class="datatable-head">
                            <tr class="datatable-row" style="left: 0px;">
                                <th data-field="#calendar" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >Calendario</span>
                                </th>
                                <th data-field="#number" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >Num.</span>
                                </th>
                                <th data-field="#title" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >Título</span>
                                </th>
                                <th data-field="#drafting" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span>Redacción</span>
                                </th>
                                <th data-field="#commercial" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >Publicidad</span>
                                </th>
                                <th data-field="#output" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >Salida</span>
                                </th>
                                <th data-field="#billing" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span >Facturación</span>
                                </th>
                                <th data-field="#front_page" style="width: 85px;" class="datatable-cell-center datatable-cell">
                                    <span>Portada</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="datatable-body ps" v-html="config.calendars.html_calendar">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
     import { mapMutations, mapActions, mapState } from "vuex";
     import AddButtonComponent from "../../Partials/AddButtonComponent.vue";

     export default {
        name: "TableExportsComponent",
        components: {
            AddButtonComponent
        },
        data() {
            return {
                publicPath: window.location.origin,
                select_calendar_filter: '0'
            };
        },
        methods: {
            ...mapMutations(["changeShowViewCalendar"]),
            ...mapActions(["listCalendarsToExport", "downloadListCalendarsCsv"]),
            downloadFile(){
                window.open(this.publicPath + "/admin/download_list_calendars_csv/" + this.select_calendar_filter,"_self")
            },
            reloadList(){
                var param = {
                    select_calendar_filter: this.select_calendar_filter
                }
                this.listCalendarsToExport(param);
            },
            printPage(){
                /*$("#list_calendars").print.printThis({
                    importCSS: true,
                    loadCSS: "/public/css/custom-back.css",
                    printContainer: true,
                    formValues: true 
                });*/
                // Get HTML to print from element
                const prtHtml = document.getElementById('div_print').innerHTML;

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
                                // cssText will be a string containing the text of the file
                            
                        

                //WinPrint.focus();
                //WinPrint.print();
               
            }
        },
        computed: {
            ...mapState(["config"])
        },
        mounted() {
            var param = {
                    select_calendar_filter: this.select_calendar_filter
                }
            this.listCalendarsToExport(param);
        }
    };
</script>