<template>
    <div class="row">
        <div class="col-12 d-flex flex-wrap justify-content-between">
            <AddButtonComponent
                    @click.native="changeShowViewCalendar(1)"
                    :columns="'col-1 ml-auto'"
                    :text="'Volver'"
                    :id="'btn_add_user'"
                    :src="'/media/custom-imgs/flecha_btn_volver.svg'"
                    :width="16"
                    :height="16"
                />
            <AddButtonComponent
                @click.native="test()"
                :columns="'col-1 mx-7'"
                :text="'Exportar'"
                :id="'btn_export'"
                :src="'/media/custom-imgs/icono_btn_exportar.svg'"
                :width="16"
                :height="16"
            />
            <AddButtonComponent
                :columns="'col-1'"
                :text="'Imprimir'"
                :id="'btn_add_number'"
                :src="'/media/custom-imgs/icono_btn_annadir_numero.svg'"
                :width="25"
                :height="25"
            />
        </div>
        <div class="col-12 mt-7">
            <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-scroll datatable-loaded" id="list_calendars" style="width: 100%;">
                <table class="datatable-table" style="display: block;">
                    <thead class="datatable-head">
                        <tr class="datatable-row" style="left: 0px;">
                            <th data-field="#calendar" class="datatable-cell-center datatable-cell datatable-cell-sort">
                                <span style="width: 175px;">Calendario</span>
                            </th>
                            <th data-field="#number" class="datatable-cell-center datatable-cell datatable-cell-sort">
                                <span style="width: 100px;">Num.</span>
                            </th>
                            <th data-field="#title" class="datatable-cell-center datatable-cell datatable-cell-sort">
                                <span style="width: 175px;">Título</span>
                            </th>
                            <th data-field="#drafting" class="datatable-cell-center datatable-cell datatable-cell-sort">
                                <span style="width: 175px;">Redacción</span>
                            </th>
                            <th data-field="#commercial" class="datatable-cell-center datatable-cell datatable-cell-sort">
                                <span style="width: 175px;">Publicidad</span>
                            </th>
                            <th data-field="#output" class="datatable-cell-center datatable-cell datatable-cell-sort">
                                <span style="width: 175px;">Salida</span>
                            </th>
                            <th data-field="#billing" class="datatable-cell-center datatable-cell datatable-cell-sort">
                                <span style="width: 175px;">Facturación</span>
                            </th>
                            <th data-field="#front_page" class="datatable-cell-center datatable-cell datatable-cell-sort">
                                <span style="width: 175px;">Portada</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="datatable-body ps" v-html="config.calendars.html_calendar">
                    </tbody>
                </table>
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
            };
        },
        methods: {
            ...mapMutations(["changeShowViewCalendar"]),
            ...mapActions(["listCalendarsToExport", "downloadListCalendarsCsv"]),
            test(){
                window.open("http://127.0.0.1:8000/admin/download_list_calendars_csv","_self")
            }
        },
        computed: {
            ...mapState(["config"])
        },
        mounted() {
            this.listCalendarsToExport();
        }
    };
</script>