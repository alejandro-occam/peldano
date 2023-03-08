<template>
    <div class="row">
        <div class="col-12 d-flex flex-wrap justify-content-between">
            <AddButtonComponent
                :columns="'px-4 ml-auto mr-7'"
                :text="'Exportar'"
                :id="'btn_export'"
                :src="'/media/custom-imgs/icono_btn_exportar.svg'"
                :width="16"
                :height="16"
                v-on:click="changeViewStatusOrders(4)"
            />
            <AddButtonComponent
                :columns="'px-4'"
                :text="'Nueva propuesta'"
                :src="'/media/custom-imgs/icono_btn_annadir_propuesta.svg'"
                :width="25"
                :height="25"
                v-on:click="changeViewStatusOrders(2)"
            />
        </div>
        <div class="col-12 d-flex flex-wrap mt-6">
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
                <span class="text-dark font-weight-bold mb-2">Departamento</span>
                <select class="form-control bg-gray text-dark select-custom select-filter mt-3" :name="'select_department'" :id="'select_department'" v-model="select_department" data-style="select-lightgreen" @change="getConsultantSelect">
                    <option value="" selected>
                        Filtro por departamento
                    </option>
                    <option :value="department.id" v-for="department in config.articles.filter.array_departments" :key="department.id" v-text="department.name" ></option>
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
                <span class="text-dark font-weight-bold mb-2">Num. orden</span>
                <input v-model="num_order" type="text" class="form-control bg-gray mt-3 select-filter text-dark-gray" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" />
            </div>

            <div class="mx-2 mt-5 col-2 d-grid">
                <span class="text-dark font-weight-bold mb-2">Estado</span>
                <select class="form-control bg-gray text-dark select-custom select-filter mt-auto" :name="'select_status_order'" :id="'select_status_order'" v-model="select_status_order" data-style="select-lightgreen" @change="getProductsSelect">
                    <option value="1" selected>Cualquiera</option>
                    <option value="2">FIRMADA</option>
                    <option value="3">ANULADA</option>
                    <option value="4">EDITANDO</option>
                </select>
            </div>
            <div class="mx-2 mt-5 col-2 d-grid">
                <span class="text-dark font-weight-bold mb-2"></span>
                <select class="form-control bg-gray text-dark select-custom select-filter mt-auto" :name="'select_status_order'" :id="'select_status_order'" v-model="select_status_order" data-style="select-lightgreen" @change="getProductsSelect">
                    <option value="1" selected>Excluyendo intercambios</option>
                    <option value="2">Todas</option>
                    <option value="3">Solo intercambios</option>
                </select>
            </div>
            <div class="mx-2 mt-auto col-2 d-grid">
                <div v-if="this.show_all == 0">
                    <button class="purple-border btn mr-4 font-weight-bold d-flex py-4" @click="this.changeStatusShowAll(1)">
                        <div class="purple-circle mr-auto my-auto">
                            <div class="white-circle-purple"></div>
                        </div>
                        <span class="px-10">Mostrar todo</span>
                    </button>
                </div>
                <div v-else>
                    <button  class="bg-purple btn mr-4 font-weight-bold color-white d-flex py-4" @click="this.changeStatusShowAll(0)">
                        <div class="white-circle mr-auto my-auto">
                            <div class="purple-circle-white"></div>
                        </div>
                        <span class="px-10">Mostrar todo</span>
                    </button>
                </div>
            </div>
            <div class="mx-2 col-12 d-flex mt-10">
                <button type="submit" class="btn bg-azul color-white px-35 font-weight-bolder" v-on:click="this.listOrders(1)">Aplicar filtro</button>
            </div>
        </div>
        <div class="col-12 mt-15">
            <div class="datatable datatable-bordered datatable-head-custom" id="list_orders" style="width: 100%" ></div>
        </div>
    </div>
</template>

<script>
    import { mapMutations, mapActions, mapState } from "vuex";

    import AddButtonComponent from "../Partials/AddButtonComponent.vue";
    import Calendar from 'primevue/calendar';
import { off } from "process";

    export default {
        name: "TableComponent",
        components: {
            AddButtonComponent,
            Calendar
        },
        data() {
            return {
                publicPath: window.location.origin,
                num_order: '',
                select_consultant: '',
                date_from: '',
                date_to: '',
                select_from_consultant: '1',
                select_department: '',
                select_status_order: '1',
                datatable: null,
                show_all: 0
            };
        },
        computed: {
            ...mapState(["errors", "proposals", "config"]),
        },
        mounted() {
            this.getUsers(2);
            var params = {
                type: 1
            }
            this.getDepartments(params);
            this.getNow();
            this.listOrders(0);
        },
        
        methods: {
            ...mapActions(["getUsers", "getDepartments", "getInfoOrder"]),
            ...mapMutations(['changeViewStatusOrders']),
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
            listOrders(type) {
                let me = this;
                $("#list_orders").KTDatatable("destroy");
                $("#list_orders").KTDatatable("init");

                var date_ms_from = '';
                var date_ms_to = '';
                var date_from = '';
                var date_to = '';

                if(type == 1 || type == undefined){
                    //me.datatable.setDataSourceParam('type', type);

                    date_ms_from = Date.parse(me.date_from);
                    date_ms_to = Date.parse(me.date_to);
                    date_from = me.$utils.customFormDate(date_ms_from);
                    date_to = me.$utils.customFormDate(date_ms_to);
                    
                    /*me.datatable.setDataSourceParam('select_consultant', me.select_consultant);
                    me.datatable.setDataSourceParam('select_department', me.select_department);
                    me.datatable.setDataSourceParam('date_from', me.$utils.customFormDate(date_ms_from));
                    me.datatable.setDataSourceParam('date_to', me.$utils.customFormDate(date_ms_to));
                    me.datatable.setDataSourceParam('num_order', me.num_order);*/
                }
                me.datatable = $("#list_orders").KTDatatable({
                    data: {
                        type: "remote",
                        source: {
                            read: {
                                url:
                                me.publicPath +
                                    "/admin/list_orders",
                                headers: {
                                    "X-CSRF-TOKEN": $(
                                        'meta[name="csrf-token"]'
                                    ).attr("content"),
                                },
                                method: 'POST',
                                params: {
                                    //type: 0
                                    type: type,
                                    select_consultant: me.select_consultant,
                                    select_department: me.select_department,
                                    date_from: date_from,
                                    date_to: date_to,
                                    num_order: me.num_order,
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
                            field: "#consultant",
                            title: "Consult.",
                            sortable: !1,
                            textAlign: "center",
                            width: 75,
                            template: function (row, data, index) {
                                return (
                                    '<span class="text-dark">' +
                                    row.id_user +
                                    "</span>"
                                );
                            },
                        },
                        {
                            field: "#proposal",
                            title: "Propuesta",
                            sortable: !1,
                            textAlign: "center",
                            width: 200,
                            template: function (row, data, index) {
                            return (
                                    '<span class="text-dark">' +
                                    row.proposal_custom +
                                    "</span>"
                                );
                            },
                        },
                        {
                            field: "#code",
                            title: "Código",
                            sortable: !1,
                            textAlign: "center",
                            width: 100,
                            template: function (row, data, index) {
                                return (
                                    '<span class="text-gray font-weight-bold">010414</span>'
                                );
                            },
                        },
                        {
                            field: "#type",
                            title: "Tipo",
                            sortable: !1,
                            textAlign: "center",
                            width: 50,
                            template: function (row, data, index) {
                                return (
                                    '<span class="text-gray font-weight-bold">NP</span>'
                                );
                            },
                        },
                        {
                            field: "#name_client",
                            title: "Nombre del cliente",
                            sortable: !1,
                            textAlign: "center",
                            width: 200,
                            template: function (row, data, index) {
                                return (
                                    '<span class="text-dark font-weight-bold">' +
                                    row.name_contact +
                                    "</span>"
                                );
                            },
                        },
                        {
                            field: "#date",
                            title: "Fecha",
                            sortable: !1,
                            textAlign: "center",
                            width: 100,
                            template: function (row, data, index) {
                                return (
                                    '<span class="text-gray font-weight-bold">' +
                                    row.date_proyect +
                                    "</span>"
                                );
                            },
                        },
                        {
                            field: "#edition",
                            title: "Edición",
                            sortable: !1,
                            textAlign: "center",
                            width: 100,
                            template: function (row, data, index) {
                                return (
                                    '<span class="text-gray font-weight-bold">'+row.edit_date+'</span>'
                                );
                            },
                        },
                        {
                            field: "#status",
                            title: "Estado",
                            sortable: !1,
                            textAlign: "center",
                            template: function (row, data, index) {
                                var html = '';
                                if(row.status == 3){
                                    html = '<span class="badge badge-light-delete f-14 fw-bold">ANULADA</span>';
                                }else if(row.status == 2){
                                    html = '<span class="badge badge-light-editing f-14 fw-bold">EDITANDO</span>';
                                }else{
                                    html = '<span class="badge badge-light-success f-14 fw-bold">FIRMADA</span>';
                                }
                                return (
                                    html
                                );
                            },
                        },
                        {
                            field: "#ctrl",
                            title: "Ctrl",
                            sortable: !1,
                            textAlign: "center",
                            width: 50,
                            template: function (row, data, index) {
                                return (
                                    '<span class="text-gray font-weight-bold">NO</span>'
                                );
                            },
                        },
                        {
                            field: "#total",
                            title: "Total",
                            sortable: !1,
                            textAlign: "center",
                            width: 100,
                            template: function (row, data, index) {
                                return (
                                    '<span class="text-gray font-weight-bold">'+row.total_amount+'</span>'
                                );
                            },
                        },
                        {
                            field: "#dto",
                            title: "Dto",
                            sortable: !1,
                            textAlign: "center",
                            width: 100,
                            template: function (row, data, index) {
                                return (
                                    '<span class="text-gray font-weight-bold">'+row.discount_order+'%</span>'
                                );
                            },
                        },
                        {
                            field: "#department",
                            title: "Departamento",
                            sortable: !1,
                            textAlign: "center",
                            width: 100,
                            template: function (row, data, index) {
                                return (
                                    '<span class="text-gray font-weight-bold">' +
                                    row.department_name.toUpperCase() +
                                    "</span>"
                                );
                            },
                        },
                        {
                            field: "#new_recovered",
                            title: "Nuevo recuperado",
                            sortable: !1,
                            textAlign: "center",
                            width: 100,
                            template: function (row, data, index) {
                                return (
                                    '<span class="text-gray font-weight-bold">SÍ</span>'
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
                                html += '<button type="button" class="btn p-0 mx-2 btn-show" data-id="' + row.id_order + '"><img class="edit-hover" src="/media/custom-imgs/icono_tabla_ver.svg" height="30px" width="auto"></button>';
                                return html;
                            },
                        },
                    ],
                });

                $("#list_orders").on("click", ".btn-show", function () {
                    var id = $(this).data("id");
                    var params = {
                        id: id,
                        type: 1
                    }
                    me.getInfoOrder(params);
                });
            },
            changeStatusShowAll(status){
                this.show_all = status;
            }
        }
    };
</script>