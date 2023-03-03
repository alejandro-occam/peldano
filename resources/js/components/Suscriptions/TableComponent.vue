<template>
    <div class="row">
        <div class="col-12 d-flex flex-wrap justify-content-between">
            <ValidateButtonComponent
                :columns="'mr-7 ml-auto'"
                :text="'Añadir suscriptor'"
                :id="'btn_add_suscription'"
                :width="16"
                :height="16"
                v-on:click="openModalAddSuscription()"
            />
        </div>
        <div class="col-12 mt-2">
            <div class="col-12 mt-15">
                <div class="datatable datatable-bordered datatable-head-custom" id="list_suscriptions" style="width: 100%" ></div>
            </div>
        </div>
    </div>
</template>

<script>
    import Calendar from 'primevue/calendar';
    import ValidateButtonComponent from "../Partials/ValidateButtonComponent.vue";

    import { mapMutations, mapActions, mapState } from "vuex";
    export default {
        name: "TableComponent",
        components: {
            Calendar,
            ValidateButtonComponent
        },
        data() {
            return {
                publicPath: window.location.origin,
            };
        },
        methods: {
            ...mapActions(["getInfoAddSuscriptions"]),
            ...mapMutations(["clearError"]),
            listSuscriptions() {
                let me = this;
    
                $("#list_suscriptions").KTDatatable("destroy");
                $("#list_suscriptions").KTDatatable("init");
                $("#list_suscriptions").KTDatatable({
                    data: {
                        type: "remote",
                        source: {
                            read: {
                                url:
                                    this.publicPath +
                                    "/admin/list_suscriptions",
                                headers: {
                                    "X-CSRF-TOKEN": $(
                                        'meta[name="csrf-token"]'
                                    ).attr("content"),
                                },
                                method: 'POST'
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
                            field: "#contact",
                            title: "Cliente",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-dark">' +
                                        row.contacts_name + " " + row.contacts_surname + 
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#article",
                            title: "Artículo",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-dark">' +
                                        row.article_name +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#magazine",
                            title: "Revista",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-blue-table font-weight-bold">' +
                                        row.calendars_magazines_name +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#initial_num",
                            title: "Nº inicial",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-blue-table font-weight-bold">' +
                                        row.num +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#",
                            title: "Acciones",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                var html = '<div><button type="button" class="btn p-0 mx-2 btn-edit" data-id="' + row.id + '"><img class="edit-hover" src="/media/custom-imgs/icono_tabla_editar.svg" height="30px" width="auto"></button>';
                                html += '<button type="button" class="btn p-0 mx-2 btn-delete" data-id="' + row.id + '"><img class="edit-hover" src="/media/custom-imgs/icono_tabla_eliminar.svg" height="30px" width="auto"></button></div>';
                                return html;
                            },
                        },
                    ],
                });
    
                $("#list_suscriptions").on("click", ".btn-edit", function () {
                    var id = $(this).data("id");
                    me.controlFormUsers(1);
                    me.getInfoUser(id);
                    me.changeShowView(2);
                });

                $("#list_suscriptions").on("click", ".btn-delete", function () {
                    var id = $(this).data("id");
                    me.getInfoUser(id);
                    $("#modal_delete_user").modal("show");                    
                });
            },
            openModalAddSuscription(){
                $('#modal_add_suscription').modal('show');
            }
        },
        computed: {
                ...mapState(["errors", "suscriptions"]),
        },
        mounted() {
           this.listSuscriptions();
        },
        watch: {
            '$store.state.errors.code': function() {
                if(this.errors.type_error == 'validate_bill'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            swal("", "Factura validada correctamente", "success");

                        }else{
                            swal("", "Ha habido un error. Inténtalo de nuevo más tatrde", "error");
                        }
                    
                    }else{
                        swal("", "Ha habido un error. Inténtalo de nuevo más tatrde", "error");
                    }
                }
                this.clearError();
            }
        }
    };
    </script>