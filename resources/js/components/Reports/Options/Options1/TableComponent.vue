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
            <span class="text-dark font-weight-bold mb-2">Departamentos</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_department" :name="'select_department'" :id="'select_department'" data-style="select-lightgreen">
                <option value="0" selected>
                    Filtro por departamento
                </option>
                <option :value="department.id" v-for="department in config.articles.filter.array_departments" :key="department.id" v-text="department.nomenclature + '-' + department.name" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Consultor</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" :name="'select_consultant'" :id="'select_consultant'" v-model="select_consultant" data-style="select-lightgreen" @change="getConsultantSelect">
                <option value="0" selected>
                    Cualquier consultor
                </option>
                <option :value="user.id" v-for="user in proposals.array_users"  :key="user.id" v-text="user.name + ' ' + user.surname" ></option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Órdenes</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_order"  :name="'select_order'" :id="'select_order'" data-style="select-lightgreen">
                <option :value="1">Firmadas</option>
                <option :value="2">Editando</option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-auto">
            <select class="form-control bg-gray text-dark select-custom select-filter" v-model="select_exchange"  :name="'select_exchange'" :id="'select_exchange'" data-style="select-lightgreen">
                <option :value="1">Sin intercambios</option>
                <option :value="2">Con intercambios</option>
                <option :value="3">Sólo intercambios</option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Limitadas por fechas</span>
            <span class="switch switch-outline switch-icon switch-success mt-3">
                <label class="mr-auto">
                    <input class="switch-exempt" input type="checkbox" v-model="switch_limit_date" name="select"/>
                    <span></span>
                </label>
            </span>
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
            <span class="text-dark font-weight-bold mb-2">Datos a usar</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_data_to_use" :name="'select_data_to_use'" :id="'select_data_to_use'" data-style="select-lightgreen">
                <option :value="1">Del consultor</option>
                <option :value="2">De la cartera asignada al consultor</option>
                <option :value="3">Responsable de publicaciones</option>
            </select>
        </div>

        <div class="mx-2 col-2 mt-5">
            <span class="text-dark font-weight-bold mb-2">Comparar con</span>
            <select class="form-control bg-gray text-dark select-custom select-filter mt-3" v-model="select_compare" :name="'select_compare'" :id="'select_compare'" data-style="select-lightgreen">
                <option :value="1">Hace 1 año</option>
                <option :value="2">Hace 2 años</option>
                <option :value="3">Hace 3 años</option>
                <option :value="4">Hace 4 años</option>
                <option :value="5">Hace 5 años</option>
            </select>
        </div>
        <div class="mx-2 col-12 d-flex mt-10">
            <button type="submit" class="btn bg-azul color-white px-35 font-weight-bolder" @click.native="filteRreportListByChannel()">Generar informe</button>
        </div>
    </div>
    <Divider class="my-15" />
    <div class="col-12 d-flex flex-wrap mt-6" v-if="Number(reports.array_bills_orders.length) > 0">
        <div class="col-12 flex-wrap justify-content-between mb-10">
            <h3 class="color-blue my-auto">Resultados</h3>
            <div class="row mt-10">
                <div class="field col-4 md:col-4 px-40">
                    <span class="p-calendar p-component p-inputwrapper w-100 select-filter input-custom-calendar-reports mt-3 text-align-center"><span class="p-inputtext p-component">{{ this.period_new }}</span></span>
                    <Chart class="mt-3" type="pie" :data="this.chartDataNew" :options="this.lightOptions" />
                </div>
                <div class="field col-4 md:col-4 px-40">
                    <span class="p-calendar p-component p-inputwrapper w-100 select-filter input-custom-calendar-reports mt-3 text-align-center"><span class="p-inputtext p-component">{{ this.period_old }}</span></span>
                    <Chart class="mt-3" type="pie" :data="this.chartDataOld" :options="this.lightOptions" />
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 mt-15" v-if="Number(reports.array_bills_orders.length) > 0">
        <table width="100%" cellpadding="2" cellspacing="1">
            <thead class="custom-columns-datatable">
                <tr>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>DEP</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>TIPO</span></th>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 125px;"><span>PERIODO</span></th>
                    <template v-for="index_report_date in Number(reports.array_dates.length)">
                        <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>{{ reports.array_dates[index_report_date - 1].date }}</span></th>
                    </template>
                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>TOTAL</span></th>
                </tr>
            </thead>
            <tbody>
                <template v-for="index_bill_order in Number(reports.array_bills_orders.length)">
                    <tr class="row-product bg-white" v-if="reports.array_bills_orders[index_bill_order - 1].type_obj == 1">
                        <td class="td-border-right bg-light-blue-table pl-5" :rowspan="4">{{ reports.array_bills_orders[index_bill_order - 1].dep }}</td>
                        <td class="td-border-right pl-5" :rowspan="4">{{ reports.array_bills_orders[index_bill_order - 1].type }}</td>
                    </tr>
                    <tr class="row-product bg-white" v-else-if="reports.array_bills_orders[index_bill_order - 1].type_obj == 2">
                        <td class="td-border-right bg-light-blue-table pl-5 text-align-center" :rowspan="4" colspan="2">{{ reports.array_bills_orders[index_bill_order - 1].dep.toUpperCase() }}</td>
                    </tr>
                    <tr class="row-product bg-white"  v-else-if="reports.array_bills_orders[index_bill_order - 1].type_obj == 3">
                        <td class="td-border-right bg-light-blue-diference-table pl-5" :rowspan="4" colspan="2">{{ reports.array_bills_orders[index_bill_order - 1].dep }}</td>
                    </tr>
                    <tr class="row-product bg-white" v-else>
                        <td class="td-border-right bg-light-blue-table pl-5" :rowspan="4" colspan="2">TOTAL</td>
                    </tr>
                    <tr class="row-product bg-white">
                        <td class="td-border-right pl-3">{{ reports.array_bills_orders[index_bill_order - 1].old.period }}</td>
                        <template v-for="index_amounts in Number(reports.array_bills_orders[index_bill_order - 1].new.amounts.length)">
                            <td class="text-align-center td-border-right">{{ reports.array_bills_orders[index_bill_order - 1].old.amounts[index_amounts - 1] }}</td>
                        </template>
                    </tr>   
                    <tr class="row-product bg-white">
                        <td class="td-border-right pl-3 bg-light-blue-table">{{ reports.array_bills_orders[index_bill_order - 1].new.period }}</td>
                        <template v-for="index_amounts in Number(reports.array_bills_orders[index_bill_order - 1].new.amounts.length)">
                            <td class="text-align-center td-border-right bg-light-blue-table">{{ reports.array_bills_orders[index_bill_order - 1].new.amounts[index_amounts - 1] }}</td>
                        </template>
                    </tr>   
                    <tr class="row-product bg-white">
                        <td class="td-border-right pl-3 bg-purple color-white">{{ reports.array_bills_orders[index_bill_order - 1].diference.period }}</td>
                        <template v-for="index_amounts in Number(reports.array_bills_orders[index_bill_order - 1].new.amounts.length)">
                            <td class="text-align-center td-border-right bg-light-blue-diference-table">{{ reports.array_bills_orders[index_bill_order - 1].diference.amounts[index_amounts - 1] }}</td>
                        </template>
                    </tr>
                </template>
                <!--CAR DIGITAL-->
                <!--<tr class="row-product text-align-center bg-white">
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
                </tr>-->
                <!--CAR EXPERIENCIA-->
                <!--<tr class="row-product text-align-center bg-white">
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
                </tr>-->
                <!--CAR OTROS-->
                <!--<tr class="row-product text-align-center bg-white">
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
                </tr>-->   
                <!--CARAVANING-->
                <!--<tr class="row-product bg-white">
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
                </tr>-->   
                <!--OTROS-->
                <!--<tr class="row-product bg-white">
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
                </tr>-->   
                <!--EXPERIENCIA-->
                <!--<tr class="row-product bg-white">
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
                </tr>-->  
                <!--DIGITAL-->
                <!--<tr class="row-product bg-white">
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
                </tr>-->   
                <!--TOTAL-->
                <!--<tr class="row-product bg-white">
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
                </tr>-->   
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
        name: "TableComponentOption1",
        components: {
            AddButtonComponent,
            Calendar,
            Divider,
            Chart,
            RouterButton
        },
        data() {
            return {
                publicPath: window.location.origin,
                select_department: '0',
                select_consultant: '0',
                select_order: '1',
                select_exchange: '1',
                switch_limit_date: 0,
                date_from: '',
                date_to: '',
                date_from_custom: '',
                date_to_custom: '',
                select_data_to_use: '1',
                select_compare: '1',
                period_new: '',
                period_old: '',
                chartDataNew: {
                    labels: [],
                    datasets: [
                        {
                            data: [],
                            backgroundColor: ["#ff1e60","#00c353","#007dff", "#ffd041"],
                            hoverBackgroundColor: ["#ff1e60","#00c353","#007dff", "#ffd041"]
                        }
                    ]
                },
                chartDataOld: {
                    labels: [],
                    datasets: [
                        {
                            data: [],
                            backgroundColor: ["#ff1e60","#00c353","#007dff", "#ffd041"],
                            hoverBackgroundColor: ["#ff1e60","#00c353","#007dff", "#ffd041"]
                        }
                    ]
                },
                lightOptions: {
                    plugins: {
                        legend: {
                            labels: {
                                color: '#b5b5c2'
                            }
                        }
                    }
                }
            };
        },
        computed: {
            ...mapState(["errors", "proposals", "config", "reports"]),
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
            ...mapActions(["getUsers", "getDepartments", "reportListByChannel"]),
            ...mapMutations(["changeViewStatusReports"]),
            //Consultar fecha actual
            getNow() {
                const today = new Date();
                
                const date_from =  '01-01-' + today.getFullYear();
                this.date_from = date_from;
                const date_to =  '31-12-' + today.getFullYear();
                this.date_to = date_to;
            },
            filteRreportListByChannel(){
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
                    select_consultant: this.select_consultant,
                    select_order: this.select_order,
                    select_compare: this.select_compare,
                    date_from: date_from,
                    date_to: date_to
                }
                this.reportListByChannel(params);
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
            }
        },
        watch: {
            '$store.state.reports.percent_new': function() {
                this.chartDataNew = {
                    labels: ['Otros','Experiencias','Digital', 'Print'],
                    datasets: [
                        {
                            data: this.reports.percent_new,
                            backgroundColor: ["#ff1e60","#00c353","#007dff", "#ffd041"],
                            hoverBackgroundColor: ["#ff1e60","#00c353","#007dff", "#ffd041"]
                        }
                    ]
                };
                this.period_new = this.reports.period_new;
            },
            '$store.state.reports.percent_old': function() {
                this.chartDataOld = {
                    labels: ['Otros','Experiencias','Digital', 'Print'],
                    datasets: [
                        {
                            data: this.reports.percent_old,
                            backgroundColor: ["#ff1e60","#00c353","#007dff", "#ffd041"],
                            hoverBackgroundColor: ["#ff1e60","#00c353","#007dff", "#ffd041"]
                        }
                    ]
                };
                this.period_old = this.reports.period_old;
            }
        }
    };
</script>