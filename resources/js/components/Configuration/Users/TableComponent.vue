<template>
    <div class="row">
        <div class="col-12 d-flex flex-wrap justify-content-between">
            <SearchComponent
                :columns="'col-2'"
            />
            <AddButtonComponent
                @click.native="openModalAddUser()"
                :columns="'col-1'"
                :text="'Añadir usuario'"
                :id="'btn_add_user'"
                :src="'/media/custom-imgs/icono_btn_annadir_usuario.svg'"
                :width="16"
                :height="16"
            />
        </div>
        <div class="col-12">
            <div
                class="datatable datatable-bordered datatable-head-custom"
                id="list_users"
                style="width: 100%"
            ></div>
        </div>
    </div>
</template>

<script>
    import { mapMutations, mapActions, mapState } from "vuex";


    import SearchComponent from "../../Partials/SearchComponent.vue";
    import AddButtonComponent from "../../Partials/AddButtonComponent.vue";

    export default {
        name: "TableComponent",
        components: {
            SearchComponent,
            AddButtonComponent,
        },
        data() {
            return {
                publicPath: window.location.origin,
            };
        },
        methods: {
            ...mapActions(["getInfoUser", "deleteUser"]),
            ...mapMutations(["changeShowView", "controlFormUsers"]),
            listUsers() {
                let me = this;
    
                $("#list_users").KTDatatable("destroy");
                $("#list_users").KTDatatable("init");
                $("#list_users").KTDatatable({
                    data: {
                        type: "remote",
                        source: {
                            read: {
                                url:
                                    this.publicPath +
                                    "/admin/list_users",
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
                            field: "#id",
                            title: "id",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-dark">' +
                                        row.id +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#user",
                            title: "Usuario",
                            sortable: !1,
                            textAlign: "center",
                            width: 100,
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-dark">' +
                                        row.user +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#fullname",
                            title: "Nombre y apellido",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-blue-table font-weight-bold">' +
                                        row.name + ' ' + row.surname +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#team",
                            title: "Grupo",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-blue-table font-weight-bold">' +
                                        row.position_nane +
                                        "</span>"
                                    );
                            },
                        },
                        {
                            field: "#register_date",
                            title: "Fecha de registro",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-gray font-weight-bold">' +
                                        row.custom_date +
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
                                var html ='<div><button type="button" class="btn p-0 mx-2 btn-show " data-id="' + row.id + '"><img class="edit-hover" src="/media/custom-imgs/icono_tabla_ver.svg" height="30px" width="auto"></button>';
                                html += '<button type="button" class="btn p-0 mx-2 btn-edit" data-id="' + row.id + '"><img class="edit-hover" src="/media/custom-imgs/icono_tabla_editar.svg" height="30px" width="auto"></button>';
                                html += '<button type="button" class="btn p-0 mx-2 btn-delete" data-id="' + row.id + '"><img class="edit-hover" src="/media/custom-imgs/icono_tabla_eliminar.svg" height="30px" width="auto"></button></div>';
                                return html;
                            },
                        },
                    ],
                });
    
                $("#list_users").on("click", ".btn-edit", function () {
                    var id = $(this).data("id");
                    me.controlFormUsers(1);
                    me.getInfoUser(id);
                    me.changeShowView(2);
                });

                $("#list_users").on("click", ".btn-delete", function () {
                    var id = $(this).data("id");
                    me.getInfoUser(id);
                    $("#modal_delete_user").modal("show");                    
                });

                $("#list_users").on("click", ".btn-show", function () {
                    var id = $(this).data("id");
                    me.getInfoUser(id);
                    me.changeShowView(3);           
                });
            },
            openModalAddUser(){
                this.controlFormUsers(0);
                this.changeShowView(2);
            }
        },
        computed: {
                ...mapState(["errors", "config"]),
        },
        mounted() {
            this.listUsers();
        },
    };
    </script>