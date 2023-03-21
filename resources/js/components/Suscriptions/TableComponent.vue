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
            <ValidateButtonComponent
                :columns="'mr-7'"
                :text="'Actualizar suscriptores seleccionados'"
                :id="'btn_update_suscription'"
                v-if="this.is_update"
                :width="16"
                :height="16"
                v-on:click="openModalUpdateSuscription()"
            />
        </div>
        <div class="col-12 mt-2">
            <div class="d-flex">
                <div class="mx-2 col-2 mt-5">
                    <span class="text-dark font-weight-bold mb-2">Revista</span>
                    <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_magazine" :name="'select_magazine'" :id="'select_magazine'" data-style="select-lightgreen">
                        <option value="0" selected>Filtro por revista</option>
                        <option :value="calendar_magazine.id" v-for="calendar_magazine in suscriptions.array_calendars_magazines" :key="calendar_magazine.id" v-text="calendar_magazine.name_calendar + ' ' + calendar_magazine.title" ></option>
                    </select>
                </div>
                <div class="mx-2 col-2 mt-5">
                    <span class="text-dark font-weight-bold mb-2">Nº revista final</span>
                    <input v-model="num_finish" type="number" class="form-control borders-box text-dark-gray mt-3" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 0"/>
                </div>
                <div class="mx-2 col-2 mt-5" >
                    <span class="text-dark font-weight-bold mb-2">Forma de pago</span>
                    <select class="form-control bg-gray text-dark select-custom select-filter mt-3" :name="'select_payment_method'" :id="'select_payment_method'" v-model="select_payment_method" data-style="select-lightgreen">
                        <option value="0" selected> Selecciona una forma de pago </option>
                        <option v-for="payment_method in suscriptions.array_payment_methods" :value="payment_method.id"  v-text="payment_method.name" :key="payment_method.id"></option>
                    </select>
                </div>
            </div>
            <div class="mx-2 col-12 d-flex mt-10">
                <button type="submit" class="btn bg-azul color-white px-35 font-weight-bolder" v-on:click="listSuscriptions()">Aplicar filtro</button>
            </div>
            <div class="col-12 mt-15">
                <div class="datatable datatable-bordered datatable-head-custom" id="list_suscriptions" style="width: 100%" ></div>
            </div>
        </div>
    </div>
    <ModalAddSuscriptionComponent></ModalAddSuscriptionComponent>
    <ModalUpdateSuscriptionComponent></ModalUpdateSuscriptionComponent>
</template>

<script>
    import Calendar from 'primevue/calendar';
    import ValidateButtonComponent from "../Partials/ValidateButtonComponent.vue";
    import ModalAddSuscriptionComponent from "./ModalAddSuscriptionComponent.vue";
    import ModalUpdateSuscriptionComponent from "./ModalUpdateSuscriptionComponent.vue";

    import { mapMutations, mapActions, mapState } from "vuex";
    export default {
        name: "TableComponent",
        components: {
            Calendar,
            ValidateButtonComponent,
            ModalAddSuscriptionComponent,
            ModalUpdateSuscriptionComponent
        },
        data() {
            return {
                publicPath: window.location.origin,
                select_magazine: 0,
                select_payment_method: 0,
                num_finish: '',
                array_ids_update: [],
                is_update: false
            };
        },
        methods: {
            ...mapActions(["deleteSuscription", "getAllCalendarsMagazines"]),
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
                                method: 'POST',
                                params: {
                                    select_magazine: this.select_magazine,
                                    select_payment_method: this.select_payment_method,
                                    num_finish: this.num_finish
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
                            field: 'RecordID',
                            title: '',
                            sortable: false,
                            width: 20,
                            selector: true,
                            textAlign: 'center',
                            class: 'ml-auto'
                        },
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
                            field: "#finish_num",
                            title: "Nº final",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                return (
                                        '<span class="text-blue-table font-weight-bold">' +
                                        row.num_finish +
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
                    extensions: {
                        checkbox: true/*{
                            vars: {
                                selectedAllRows: 'selectedAllRows',
                                requestIds: 'requestIds',
                                rowIds: 'meta.rowIds',
                            },
                        },*/
                    }
                });
    
                $("#list_suscriptions").on("click", ".btn-edit", function () {
                    var id = $(this).data("id");
                    var array_suscriptions = [];
                    array_suscriptions.push(id);
                    //this.$refs.modal_update_suscription.array_suscriptions = array_suscriptions;
                    document.getElementById('array_suscriptions').value = array_suscriptions;
                    $('#modal_update_suscription').modal('show');
                });

                $("#list_suscriptions").on("click", ".btn-delete", function () {
                    var id = $(this).data("id");
                    swal({
                        title: '¿Está seguro de eliminar el suscriptor?',
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
                            me.deleteSuscription(id);
                        }
                    });                      
                });

                var e = $("#list_suscriptions").KTDatatable();
                e.on("datatable-on-click-checkbox", (function(event, args) {
                    me.array_ids_update = [];
                    var a = e.checkbox().getSelectedId();
                    me.array_ids_update = a;
                    //Eliminamos el primer parámetro que guarda un array vacío cuando hacemos un checkall
                    if(a.length >= 1){
                        if(a[0].length == 0){
                            me.array_ids_update.shift();
                        }
                        me.is_update = true;
                    }
                    if(a.length == 0){
                        me.is_update = false;
                    }
                }));

                    
            },
            openModalAddSuscription(){
                $('#modal_add_suscription').modal('show');
            },
            openModalUpdateSuscription(){
                //this.$refs.modal_update_suscription.array_suscriptions = array_suscriptions;
                document.getElementById('array_suscriptions').value = this.array_ids_update;
                $('#modal_update_suscription').modal('show');
            }
        },
        computed: {
                ...mapState(["errors", "suscriptions"]),
        },
        mounted() {
            this.getAllCalendarsMagazines();
            this.listSuscriptions();
        },
        watch: {
            '$store.state.errors.code': function() {
                if(this.errors.type_error == 'delete_suscription'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            $("#list_suscriptions").KTDatatable("reload");
                            swal("", "Suscriptor eliminado correctamente", "success");

                        }else if(this.errors.code == 1001){
                            swal("", "El suscriptor no existe", "warning");

                        }else{
                            swal("", "Parece que ha habiado un error. Inténtalo de nuevo más tarde", "error");
                        }
                    }
                }else if(this.errors.type_error == 'update_suscription'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            this.is_update = false;
                            $("#list_suscriptions").KTDatatable("reload");
                            swal("", "Suscriptor/es actualizado/s correctamente", "success");
                            $("#modal_update_suscription").modal("hide");
                        }
                    }else{
                        swal("", "Parece que ha habiado un error. Inténtalo de nuevo más tarde", "error");
                    }

                }else if(this.errors.type_error == 'add_suscription'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            this.is_update = false;
                            $("#list_suscriptions").KTDatatable("reload");
                            $("#modal_add_suscription").modal("hide");
                            swal("", "Suscripción creada correctamente", "success");
                        }
                    }else{
                        swal("", "Parece que ha habiado un error. Inténtalo de nuevo más tarde", "error");
                    }
                }
                this.suscriptions.is_loading = false;
                this.clearError();
            }
        }
    };
</script>