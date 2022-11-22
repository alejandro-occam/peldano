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
        <div class="col-12 flex-wrap justify-content-between mb-10">
            <h3 class="color-blue my-auto">Resultados</h3>
            <div class="row mt-10">
                <div class="field col-4 md:col-4 px-40">
                    <Calendar inputId="multiple" autocomplete="off" v-model="date1" dateFormat="dd-mm-yy" selectionMode="range" class="w-100 select-filter input-custom-calendar-reports mt-3 text-align-center" :hideOnRangeSelection="true" :manualInput="true" />
                    <Chart class="mt-3" type="pie" :data="this.chartData" :options="this.lightOptions" />
                </div>
                <div class="field col-4 md:col-4 px-40">
                    <Calendar inputId="multiple" autocomplete="off" v-model="date2" dateFormat="dd-mm-yy" selectionMode="range" class="w-100 select-filter input-custom-calendar-reports mt-3" :hideOnRangeSelection="true" :manualInput="true" />
                    <Chart class="mt-3" type="pie" :data="this.chartData" :options="this.lightOptions" />
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 mt-15">
        <table width="100%" cellpadding="2" cellspacing="1">
            <thead class="custom-columns-datatable">
                <tr>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>SEC</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>TIPO</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 125px;"><span>PERIODO</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>*Jan-22</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Feb-22</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Mar-22</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Apr-22</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>May-22</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Jun-22</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Jul-22</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Aug-22</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Sep-22</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Oct-22</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>Nov-22</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>*Dec-22</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>TOTAL</span></th>
                </tr>
            </thead>
            <tbody>  
                <!--CAR DIGITAL-->
                <tr class="row-product text-align-center bg-white">
                    <td class="td-border-right bg-light-blue-table" :rowspan="4">CAR</td>
                    <td class="td-border-right" :rowspan="4">DIGITAL</td>
                </tr>
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3">01-01-2020 a 30-12-2020</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                </tr>   
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3 bg-light-blue-table">01-01-2021 a 30-12-2021</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                </tr>   
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3 bg-purple color-white">Diferencia%</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                </tr>   
                <!--CAR EXPERIENCIA-->
                <tr class="row-product text-align-center bg-white">
                    <td class="td-border-right bg-light-blue-table" :rowspan="4">CAR</td>
                    <td class="td-border-right" :rowspan="4">EXPERIENCIA</td>
                </tr>
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3">01-01-2020 a 30-12-2020</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                </tr>   
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3 bg-light-blue-table">01-01-2021 a 30-12-2021</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                </tr>   
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3 bg-purple color-white">Diferencia%</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                </tr>   
                <!--CAR OTROS-->
                <tr class="row-product text-align-center bg-white">
                    <td class="td-border-right bg-light-blue-table" :rowspan="4">CAR</td>
                    <td class="td-border-right" :rowspan="4">OTROS</td>
                </tr>
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3">01-01-2020 a 30-12-2020</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                </tr>   
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3 bg-light-blue-table">01-01-2021 a 30-12-2021</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                </tr>   
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3 bg-purple color-white">Diferencia%</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                </tr>   
                <!--CARAVANING-->
                <tr class="row-product bg-white">
                    <td class="td-border-right bg-light-blue-table pl-5" :rowspan="4" colspan="2">CARAVANING</td>
                </tr>
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3">01-01-2020 a 30-12-2020</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                </tr>   
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3 bg-light-blue-table">01-01-2021 a 30-12-2021</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                </tr>   
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3 bg-purple color-white">Diferencia%</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                </tr>   
                <!--OTROS-->
                <tr class="row-product bg-white">
                    <td class="td-border-right bg-light-blue-diference-table pl-5" :rowspan="4" colspan="2">OTROS</td>
                </tr>
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3">01-01-2020 a 30-12-2020</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                </tr>   
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3 bg-light-blue-table">01-01-2021 a 30-12-2021</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                </tr>   
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3 bg-purple color-white">Diferencia%</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                </tr>   
                <!--EXPERIENCIA-->
                <tr class="row-product bg-white">
                    <td class="td-border-right bg-light-blue-diference-table pl-5" :rowspan="4" colspan="2">EXPERIENCIA</td>
                </tr>
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3">01-01-2020 a 30-12-2020</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                </tr>   
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3 bg-light-blue-table">01-01-2021 a 30-12-2021</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                </tr>   
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3 bg-purple color-white">Diferencia%</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                </tr>   
                <!--DIGITAL-->
                <tr class="row-product bg-white">
                    <td class="td-border-right bg-light-blue-diference-table pl-5" :rowspan="4" colspan="2">DIGITAL</td>
                </tr>
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3">01-01-2020 a 30-12-2020</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                </tr>   
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3 bg-light-blue-table">01-01-2021 a 30-12-2021</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                </tr>   
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3 bg-purple color-white">Diferencia%</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                </tr>   
                <!--TOTAL-->
                <tr class="row-product bg-white">
                    <td class="td-border-right bg-light-blue-table pl-5" :rowspan="4" colspan="2">TOTAL</td>
                </tr>
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3">01-01-2020 a 30-12-2020</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                    <td class="text-align-center td-border-right">0</td>
                </tr>   
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3 bg-light-blue-table">01-01-2021 a 30-12-2021</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                    <td class="text-align-center td-border-right bg-light-blue-table">0</td>
                </tr>   
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3 bg-purple color-white">Diferencia%</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                    <td class="text-align-center td-border-right bg-light-blue-diference-table">-</td>
                </tr>   
            </tbody>
        </table>
    </div>
</template>

<script>
    import { mapMutations, mapActions, mapState } from "vuex";

    import AddButtonComponent from "../../../Partials/AddButtonComponent.vue";
    import Calendar from 'primevue/calendar';
    import Divider from 'primevue/divider';
    import Chart from 'primevue/chart';

    export default {
        name: "TableComponentOption1",
        components: {
            AddButtonComponent,
            Calendar,
            Divider,
            Chart
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
                date1: '',
                date2: '',
                chartData: {
                    labels: ['A','B','C'],
                    datasets: [
                        {
                            data: [300, 50, 100],
                            backgroundColor: ["#42A5F5","#66BB6A","#FFA726"],
                            hoverBackgroundColor: ["#64B5F6","#81C784","#FFB74D"]
                        }
                    ]
                },
                lightOptions: {
                    plugins: {
                        legend: {
                            labels: {
                                color: '#495057'
                            }
                        }
                    }
                }
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
            ...mapMutations(["changeViewStatusReports"]),
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