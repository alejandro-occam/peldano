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
                    @click.native="changeViewStatusProposals(3)"
                />
                <AddButtonComponent
                    :columns="'ml-auto mr-7'"
                    :text="'Volver'"
                    :id="'btn_return'"
                    :src="'/media/custom-imgs/flecha_btn_volver.svg'"
                    :width="16"
                    :height="16"
                    @click.native="changeViewStatusReports(1)"
                />
            </div>
        </div>
        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Sector</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" :name="'select_sector'" :id="'select_sector'" data-style="select-lightgreen">
                <option value="" selected>
                    Filtro por sector
                </option>
                <option :value="sector.id" v-for="sector in config.articles.filter.array_sectors"  :key="sector.id" v-text="sector.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Consultor</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" :name="'select_sector'" :id="'select_sector'" data-style="select-lightgreen">
                <option value="" selected>
                    Filtro por sector
                </option>
                <option :value="sector.id" v-for="sector in config.articles.filter.array_sectors"  :key="sector.id" v-text="sector.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Órdenes</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" :name="'select_sector'" :id="'select_sector'" data-style="select-lightgreen">
                <option :value="1">Firmadas</option>
                <option :value="2">Editando</option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-auto">
            <select class="form-control bg-gray text-dark select-custom select-filter" :name="'select_sector'" :id="'select_sector'" data-style="select-lightgreen">
                <option :value="1">Sin intercambios</option>
                <option :value="2">Con intercambios</option>
                <option :value="3">Sólo intercambios</option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Limitadas por fechas</span>
            <span class="switch switch-outline switch-icon switch-success">
                <label class="mr-auto">
                    <input class="switch-exempt" input type="checkbox" checked="checked" name="select"/>
                    <span></span>
                </label>
            </span>
        </div>


        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Fecha desde</span>
            <Calendar class="w-100 select-filter input-custom-calendar mt-3" inputId="date_from" autocomplete="off" dateFormat="dd-mm-yy" />
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Fecha hasta</span>
            <Calendar class="w-100 select-filter input-custom-calendar mt-3" inputId="date_to" autocomplete="off" dateFormat="dd-mm-yy"  />
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Datos a usar</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" :name="'select_sector'" :id="'select_sector'" data-style="select-lightgreen">
                <option :value="1">Del consultor</option>
                <option :value="2">De la cartera asignada al consultor</option>
                <option :value="3">Responsable de publicaciones</option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Comparar con</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" :name="'select_sector'" :id="'select_sector'" data-style="select-lightgreen">
                <option :value="1">Hace 1 año</option>
                <option :value="2">Hace 2 años</option>
                <option :value="3">Hace 3 años</option>
                <option :value="4">Hace 4 años</option>
                <option :value="5">Hace 5 años</option>
            </select>
        </div>
        <div class="mx-2 col-12 d-flex mt-10">
            <button type="submit" class="btn bg-azul color-white px-35 font-weight-bolder">Generar informe</button>
        </div>
    </div>
    <Divider class="my-15" />
    <div class="col-12 d-flex flex-wrap mt-6">
        <div class="col-12 d-flex flex-wrap justify-content-between mb-10">
            <h3 class="color-blue my-auto">Resultados</h3>
            <div class="field col-12 md:col-4">
                <label for="multiple">Multiple</label>
                <Calendar inputId="multiple" v-model="date" selectionMode="multiple" :manualInput="false" />
            </div>
        </div>
    </div>
    <div class="col-12 mt-15">
        <div class="datatable datatable-bordered datatable-head-custom" id="list_proposals" style="width: 100%" ></div>
    </div>
</template>

<script>
    import { mapMutations, mapActions, mapState } from "vuex";

    import AddButtonComponent from "../../../Partials/AddButtonComponent.vue";
    import Calendar from 'primevue/calendar';
    import Divider from 'primevue/divider';

    export default {
        name: "TableComponentOption1",
        components: {
            AddButtonComponent,
            Calendar,
            Divider
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
                date: ''
            };
        },
        computed: {
            ...mapState(["errors", "config"]),
        },
        mounted() {

            this.getNow();
        },
        
        methods: {
            ...mapActions([]),
            ...mapMutations([]),
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
           
        }
    };
</script>

<style>

</style>