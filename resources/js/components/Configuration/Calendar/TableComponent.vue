<template>
    <div class="row">
        <div class="col-12 d-flex flex-wrap justify-content-between">
            <div class="d-flex align-items-center justify-content-center w-15">
                <select class="form-control w-100 bg-gray text-gray select-custom select-filter" :name="'select_calendar_filter'" :id="'select_calendar_filter'" v-model="select_calendar_filter" data-style="select-lightgreen" @change="reloadList">
                    <option value="" selected>
                        Elige un calendario
                    </option>
                    <option :value="calendar.id" v-for="calendar in config.calendars.array_calendars"  :key="calendar.id" v-text="calendar.name" ></option>
                </select>
            </div>
            <AddButtonComponent
                @click.native="changeShowViewCalendar(2)"
                :columns="'px-4 ml-auto mr-7'"
                :text="'Exportar'"
                :id="'btn_export'"
                :src="'/media/custom-imgs/icono_btn_exportar.svg'"
                :width="16"
                :height="16"
            />
            <AddButtonComponent
                @click.native="openFormModal()"
                :columns="'px-4'"
                :text="'Añadir número'"
                :id="'btn_add_number'"
                :src="'/media/custom-imgs/icono_btn_annadir_numero.svg'"
                :width="25"
                :height="25"
            />
        </div>
        <div class="col-12  mt-7">
            <div
                class="datatable datatable-bordered datatable-head-custom"
                id="list_calendars"
                style="width: 100%"
            ></div>
        </div>
    </div>
</template>

<script>
    import { mapMutations, mapActions, mapState } from "vuex";

    import AddButtonComponent from "../../Partials/AddButtonComponent.vue";
    import { throwStatement } from "@babel/types";

    export default {
        name: "TableComponent",
        components: {
            AddButtonComponent,
        },
        data() {
            return {
                publicPath: window.location.origin,
                select_calendar_filter: '',
            };
        },
        methods: {
            ...mapActions(["deleteCalendar", "getInfoCalendar"]),
            ...mapMutations(["controlFormCalendars", "changeShowViewCalendar"]),
            openFormModal(){
                this.controlFormCalendars(0);
                $('#modal_form_number_calendar').modal('show');
            },
            listCalendars() {
                let me = this;
    
                $("#list_calendars").KTDatatable("destroy");
                $("#list_calendars").KTDatatable("init");
                $("#list_calendars").KTDatatable({
                    data: {
                        type: "remote",
                        source: {
                            read: {
                                url:
                                    this.publicPath +
                                    "/admin/list_calendars",
                                headers: {
                                    "X-CSRF-TOKEN": $(
                                        'meta[name="csrf-token"]'
                                    ).attr("content"),
                                },
                                method: 'POST',
                                params: {
                                    select_calendar_filter: this.select_calendar_filter
                                }
                            },
                        },
                        pageSize: 10,
                        serverPaging: !0,
                        serverFiltering: !0,
                        serverSorting: !0,
                    },
                    layout: {
                        scroll: true,
                        customScrollbar: true,
                        scrollX: true,
                        footer: !1,
                        spinner: {
                            color: "#FFF",
                        },
                    },
                    sortable: !0,
                    pagination: !0,
                    search: {
                        input: $("#search_users"),
                        key: "search_users",
                    },
                    translate: {
                        records: {
                            processing: "Cargando...",
                            noRecords: "Sin resultados",
                        },
                        toolbar: {
                            pagination: {
                                items: {
                                    info: "Mostrando {{start}} - {{end}} de {{total}} resultados",
                                },
                            },
                        },
                    },
                    rows: {
                        autoHide: false,
                    },
                    columns: [
                        {
                            field: "#calendar",
                            title: "Calendario",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-dark">' +
                                        row.calendar_name +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#number",
                            title: "Num.",
                            sortable: !1,
                            textAlign: "center",
                            width: 100,
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-dark">' +
                                        row.number +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#title",
                            title: "Título",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-dark">' +
                                        row.title +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#drafting",
                            title: "Redacción",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-gray font-weight-bold">' +
                                        row.drafting +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#commercial",
                            title: "Publicidad",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-gray font-weight-bold">' +
                                        row.commercial +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#output",
                            title: "Salida",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-gray font-weight-bold">' +
                                        row.output +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#billing",
                            title: "Facturación",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-gray font-weight-bold">' +
                                        row.billing +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#front_page",
                            title: "Portada",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-gray font-weight-bold">' +
                                        row.front_page +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#",
                            title: "",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                var html ='<div>';
                                html += '<button type="button" class="btn p-0 mx-2 btn-edit" data-id="' + row.id + '"><img class="edit-hover" src="/media/custom-imgs/icono_tabla_editar.svg" height="30px" width="auto"></button>';
                                html += '<button type="button" class="btn p-0 mx-2 btn-delete" data-id="' + row.id + '"><img class="edit-hover" src="/media/custom-imgs/icono_tabla_eliminar.svg" height="30px" width="auto"></button></div>';
                                return html;
                            },
                        },
                    ],
                });
    
                $("#list_calendars").on("click", ".btn-edit", function () {
                    var id = $(this).data("id");
                    me.controlFormCalendars(1);
                    me.getInfoCalendar(id);
                    $('#modal_form_number_calendar').modal('show');
                });

                $("#list_calendars").on("click", ".btn-delete", function () {
                    var id = $(this).data("id");
                    swal({
                        title: '¿Está seguro de eliminar el calendario?',
                        text: 'No podrás recuperar los datos eliminados',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#2e49ff",
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Cancelar',
                        closeOnCancel: true,
                        closeOnConfirm: false
                    }, function(isConfirm) {
                        if (isConfirm) {
                            me.deleteCalendar(id);
                        }
                    });                
                });
            },
            reloadList(){
                this.listCalendars();
            }
        },
        computed: {
                ...mapState(["errors", "config"]),
        },
        mounted() {
            this.listCalendars();
        }
    };
    </script>