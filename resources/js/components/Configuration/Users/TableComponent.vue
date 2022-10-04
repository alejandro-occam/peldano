<template>
    <div class="row">
        <div class="col-12 d-flex flex-wrap justify-content-between">
            <SearchComponent
                :columns="'col-2'"
                :model="model"
            />
            <AddButtonComponent
                @click.native="changeShowView(2)"
                :columns="'col-1'"
                :text="'Añadir usuario'"
                :id="'btn_add_user'"
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
    import SearchComponent from "../../Partials/SearchComponent.vue";
    import AddButtonComponent from "../../Partials/AddButtonComponent.vue";

    import { mapMutations } from "vuex";

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
            ...mapMutations(["changeShowView"]),
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
                                    "/admin/configuration/list_users",
                                headers: {
                                    "X-CSRF-TOKEN": $(
                                        'meta[name="csrf-token"]'
                                    ).attr("content"),
                                },
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
                                if (row.block == 0) {
                                    return (
                                        '<span class="text-dark">' +
                                        row.email +
                                        "</span>"
                                    );
                                }
    
                                if (row.block == 1) {
                                    return (
                                        '<span class="text-gray">' +
                                        row.email +
                                        "</span>"
                                    );
                                }
                            },
                        },
                        {
                            field: "#user",
                            title: "Usuario",
                            sortable: !1,
                            textAlign: "center",
                            width: 100,
                            template: function (row, data, index) {
                                if (row.block == 0) {
                                    return (
                                        '<span class="w-100 label label-lg font-weight-bold label-inline tag-rol ' +
                                        row.class_role +
                                        '">' +
                                        row.name_role +
                                        "</span>"
                                    );
                                }
    
                                if (row.block == 1) {
                                    return (
                                        '<span class="w-100 label label-lg font-weight-bold label-inline tag-rol role-block">' +
                                        row.name_role +
                                        "</span>"
                                    );
                                }
                            },
                        },
                        {
                            field: "#fullname",
                            title: "Nombre y apellido",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                if (row.block == 0) {
                                    return (
                                        '<span class="text-blue-table font-weight-bold">' +
                                        row.created_at_custom +
                                        "</span>"
                                    );
                                }
    
                                if (row.block == 1) {
                                    return (
                                        '<span class="text-gray font-weight-bold">' +
                                        row.created_at_custom +
                                        "</span>"
                                    );
                                }
                            },
                        },
                        {
                            field: "#team",
                            title: "Grupo",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                if (row.block == 0) {
                                    return (
                                        '<span class="text-blue-table font-weight-bold">' +
                                        row.created_at_custom +
                                        "</span>"
                                    );
                                }
    
                                if (row.block == 1) {
                                    return (
                                        '<span class="text-gray font-weight-bold">' +
                                        row.created_at_custom +
                                        "</span>"
                                    );
                                }
                            },
                        },
                        {
                            field: "#register_date",
                            title: "Fecha de registro",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                if (row.block == 0) {
                                    return (
                                        '<span class="text-blue-table font-weight-bold">' +
                                        row.created_at_custom +
                                        "</span>"
                                    );
                                }
    
                                if (row.block == 1) {
                                    return (
                                        '<span class="text-gray font-weight-bold">' +
                                        row.created_at_custom +
                                        "</span>"
                                    );
                                }
                            },
                        },
                        {
                            field: "#",
                            title: "",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                var html =
                                    '<div><button type="button" class="btn p-0 mx-2 btn-edit" data-id="' +
                                    row.id +
                                    '"><img class="edit-hover" src="/assets/media/custom_img/edit_button.svg" height="30px" width="auto"></button><button type="button" class="btn p-0 mx-2 btn-blocked" data-block="' +
                                    row.block +
                                    '" data-id="' +
                                    row.id +
                                    '">';
                                if (row.block == 0) {
                                    html +=
                                        '<img class="remove-hover" src="/assets/media/custom_img/block_button_disabled.svg" height="30px" width="auto">';
                                }
    
                                if (row.block == 1) {
                                    html +=
                                        '<img class="remove-hover" src="/assets/media/custom_img/block_button_enabled.svg" height="30px" width="auto">';
                                }
    
                                html +=
                                    '</button><button type="button" class="btn p-0 mx-2 btn-delete" data-email="' +
                                    row.email +
                                    '" data-id="' +
                                    row.id +
                                    '"><img class="edit-hover" src="/assets/media/custom_img/delete_button.svg" height="30px" width="auto"></button></div>';
                                return html;
                            },
                        },
                    ],
                });
    
                $("#list_users").on("click", ".btn-edit", function () {
                    var id = $(this).data("id");
                    axios
                        .get("/admin/configuration/get_info_user/" + id)
                        .then((response) => {
                            if (response.data.code == 1000) {
                                var user = response.data.user;
                                document.getElementById("id_user").value = user.id;
                                me.email_user = user.email;
                                document.getElementById("role").innerHTML =
                                    user.name_role;
                                document
                                    .getElementById("role")
                                    .classList.add(user.class_role);
                                document.getElementById(
                                    user.name_role_short
                                ).checked = true;
                                $("#modal_edit_user").modal("show");
                            } else {
                                swal(
                                    "",
                                    "Parece que ha habido un error, inténtalo de nuevo más tarde",
                                    "error"
                                );
                            }
                        })
                        .catch(function (error) {
                            swal(
                                "",
                                "Parece que ha habido un error, inténtalo de nuevo más tarde",
                                "error"
                            );
                        });
                });
    
                $("#list_users").on("click", ".btn-blocked", function () {
                    var id = $(this).data("id");
                    var block = $(this).data("block");
                    let params = {
                        id_user: id,
                        block: block,
                    };
    
                    axios
                        .post(
                            "/admin/configuration/change_status_block_user",
                            params
                        )
                        .then((response) => {
                            swal(
                                "",
                                "Se ha cambiado el estado del usuario",
                                "success"
                            );
                            $("#list_users").KTDatatable("reload");
                        })
                        .catch(function (error) {
                            if (
                                error.response?.data ==
                                "No se puede bloquear a sí mismo"
                            ) {
                                swal(
                                    "",
                                    "No se puede cambiar el estado a sí mismo",
                                    "error"
                                );
                            } else {
                                swal(
                                    "",
                                    "Parece que ha habido un error, inténtalo de nuevo más tarde",
                                    "error"
                                );
                            }
                        });
                });
    
                $("#list_users").on("click", ".btn-delete", function () {
                    $("#id_user").val($(this).data("id"));
                    me.email_user = $(this).data("email");
                    $("#modal_delete_user").modal("show");
                });
            }
        },
        mounted() {
            //this.listUsers();
        },
    };
    </script>