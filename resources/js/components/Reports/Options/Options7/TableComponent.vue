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
                    v-on:click="changeViewStatusProposals(3)"
                />
                <RouterButton
                    :columns="'ml-auto mr-7'"
                    :text="'Volver'"
                    :id="'btn_return'"
                    :src="'/media/custom-imgs/flecha_btn_volver.svg'"
                    :width="16"
                    :height="16"
                />
            </div>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Departamento</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_department" :name="'select_department'" :id="'select_department'" data-style="select-lightgreen" @change="getSectionSelect">
                <option value="0" selected>
                    Filtro por departamento
                </option>
                <option :value="department.id" v-for="department in config.articles.filter.array_departments" :key="department.id" v-text="department.nomenclature + '-' + department.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Sección</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_section" :name="'select_section'" :id="'select_section'" data-style="select-lightgreen" @change="getChannelSelect">
                <option value="0" selected>
                    Filtro por sección
                </option>
                <option :value="section.id" v-for="section in config.articles.filter.array_sections"  :key="section.id" v-text="section.nomenclature + '-' + section.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Canal</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_channel" :name="'select_channel'" :id="'select_channel'" data-style="select-lightgreen">
                <option value="0" selected>
                    Filtro por canal
                </option>
                <option :value="channel.id" v-for="channel in config.articles.filter.array_channels" :key="channel.id" v-text="channel.nomenclature + '-' + channel.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Consultor</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_consultant" :name="'select_consultant'" :id="'select_consultant'" data-style="select-lightgreen">
                <option value="0" selected>
                    Filtro por consultor
                </option>
                <option :value="user.id" v-for="user in proposals.array_users"  :key="user.id" v-text="user.name + ' ' + user.surname" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Productos desactivados</span>
            <span class="switch switch-outline switch-icon switch-success mt-3">
                <label class="mr-auto">
                    <input class="switch-exempt" input type="checkbox" checked="checked" name="deactivated_products_switch" v-model="deactivated_products_switch"/>
                    <span></span>
                </label>
            </span>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Órdenes</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_order" :name="'select_order'" :id="'select_order'" data-style="select-lightgreen">
                <option :value="1">Firmadas</option>
                <option :value="2">Editando</option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-auto">
            <select class="form-control bg-gray text-dark select-custom select-filter" v-model="select_exchange" :name="'select_exchange'" :id="'select_exchange'" data-style="select-lightgreen">
                <option :value="1">Sin intercambios</option>
                <option :value="2">Con intercambios</option>
                <option :value="3">Sólo intercambios</option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Fecha desde</span>
            <Calendar class="w-100 select-filter input-custom-calendar mt-3" v-model="date_from" inputId="date_from" autocomplete="off" dateFormat="dd-mm-yy" />
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Fecha hasta</span>
            <Calendar class="w-100 select-filter input-custom-calendar mt-3" v-model="date_to" inputId="date_to" autocomplete="off" dateFormat="dd-mm-yy"  />
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Limitadas por fechas</span>
            <span class="switch switch-outline switch-icon switch-success mt-3">
                <label class="mr-auto">
                    <input class="switch-exempt" input type="checkbox" checked="checked" name="date_limit_switch" v-model="date_limit_switch"/>
                    <span></span>
                </label>
            </span>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Datos a usar</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_data_to_use" :name="'select_data_to_use'" :id="'select_data_to_use'" data-style="select-lightgreen">
                <option :value="1">Del consultor</option>
                <option :value="2">De la cartera asignada al consultor</option>
                <option :value="3">Responsable de publicaciones</option>
            </select>
        </div>
        
        <div class="mx-2 col-12 d-flex mt-10">
            <button v-on:click="filteRreportListBilled()" type="submit" class="btn bg-azul color-white px-35 font-weight-bolder">Generar informe</button>
        </div>
    </div>
    <Divider class="my-15" />
    <div class="col-12 mt-15">
        <table width="100%" cellpadding="2" cellspacing="1">
            <thead class="custom-columns-datatable">
                <tr>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>DEP</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>SEC</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>CAN</span></th>
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
                    <td class="td-border-right" :rowspan="4">EMA</td>
                </tr>
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3">Hace 1 año</td>
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
                    <td class="td-border-right pl-3 bg-light-blue-table">Selección</td>
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
                    <td class="td-border-right" :rowspan="4">EMA</td>
                </tr>
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3">Hace 1 año</td>
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
                    <td class="td-border-right pl-3 bg-light-blue-table">Selección</td>
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
                    <td class="td-border-right" :rowspan="4">EMA</td>
                </tr>
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3">Hace 1 año</td>
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
                    <td class="td-border-right pl-3 bg-light-blue-table">Selección</td>
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
                    <td class="td-border-right bg-light-blue-table pl-5" :rowspan="4" colspan="3">CARAVANING</td>
                </tr>
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3">Hace 1 año</td>
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
                    <td class="td-border-right pl-3 bg-light-blue-table">Selección</td>
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
                    <td class="td-border-right bg-light-blue-table pl-5" :rowspan="4" colspan="3">TOTAL</td>
                </tr>
                <tr class="row-product bg-white">
                    <td class="td-border-right pl-3">Hace 1 año</td>
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
                    <td class="td-border-right pl-3 bg-light-blue-table">Selección</td>
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
    import RouterButton from "../../../Partials/RouterButton.vue";
    import Calendar from 'primevue/calendar';
    import Divider from 'primevue/divider';
    import Chart from 'primevue/chart';

    export default {
        name: "TableComponentOption7",
        components: {
            AddButtonComponent,
            RouterButton,
            Calendar,
            Divider,
            Chart
        },
        data() {
            return {
                publicPath: window.location.origin,
                select_department: '0',
                select_section: '0',
                select_channel: '0',
                select_consultant: '0',
                deactivated_products_switch: '0',
                select_order: '1',
                select_exchange: '1',
                date_from: '',
                date_to: '',
                date_limit_switch: '0',
                select_data_to_use: '1'
            };
        },
        computed: {
            ...mapState(["errors", "config", "proposals", "reports"]),
        },
        mounted() {
            this.getUsers(1);
            this.getNow();
            var params = {
                type: 1
            }
            this.getDepartments(params);
        },
        methods: {
            ...mapActions(["getUsers",  "getDepartments", "getSections", "getChannels", "reportListBilled"]),
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
                const date_to = day + '-' + month + '-' + today.getFullYear();
                
                const date_from =  '01-01-' + today.getFullYear();
                this.date_from = date_from;
                this.date_to = date_to;
            },
            getSectionSelect(){
                this.select_section = '0';
                this.select_channel = '0';
                var params = {
                    type: 1,
                    select_articles_department: this.select_department
                }
                this.getSections(params);
            },
            getChannelSelect(){
                this.select_channel = '0';
                var params = {
                    type: 1,
                    select_articles_section: this.select_section
                }
                this.getChannels(params);
            },
            filteRreportListBilled(){
                var date_from = this.date_from;
                if(!this.isValidDate(this.date_from)){
                    var date_ms_from = Date.parse(this.date_from);
                    date_from = this.$utils.customFormDate(date_ms_from);
                }
                this.date_from_custom = date_from;
               
                var date_to = this.date_to;
                if(!this.isValidDate(this.date_to)){
                    var date_ms_to = Date.parse(this.date_to);
                    date_to = this.$utils.customFormDate(date_ms_to);
                }
                this.date_to_custom = date_to;

                var params = {
                    select_department: this.select_department,
                    select_section: this.select_section,
                    select_channel: this.select_channel,
                    select_consultant: this.select_consultant,
                    select_order: this.select_order,
                    select_compare: this.select_compare,
                    date_from: date_from,
                    date_to: date_to
                }
                this.reportListBilled(params);
            },
            // Validates that the input string is a valid date formatted as "mm/dd/yyyy"
            isValidDate(dateString) {
                var regEx = /^\d{2}-\d{2}-\d{4}$/;
                // First check for the pattern
                try {
                    if(!dateString.match(regEx)){
                        return false;
                    }else{
                        return true;
                    }
                } catch (error) {
                    return false;
                }
            },
        }
    };
</script>