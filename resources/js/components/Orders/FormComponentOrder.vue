<template>
    <div>
        <div class="col-12 d-flex flex-wrap justify-content-between">
            <AddButtonComponent
                    :columns="'ml-auto mr-7'"
                    :text="'Volver'"
                    :id="'btn_return'"
                    :src="'/media/custom-imgs/flecha_btn_volver.svg'"
                    :width="16"
                    :height="16"
                    v-on:click="returnView()"
                />
            <template v-if="!this.is_updating_order">
                <AddButtonComponent
                    :columns="'mr-7'"
                    :text="'Copiar'"
                    :id="'btn_copy_order'"
                    :src="'/media/custom-imgs/icono_btn_crear_orden.svg'"
                    :width="16"
                    :height="16"
                    v-on:click="copyOrderBtn()"
                />
                <AddButtonComponent
                    :columns="'mr-7'"
                    :text="'Modificar'"
                    :id="'btn_update_order'"
                    :src="'/media/custom-imgs/icono_btn_editar.svg'"
                    :width="16"
                    :height="16"
                    v-on:click="updateOrderFront()"
                />
                <DeleteButtonComponent
                    :columns="''"
                    :text="'Eliminar'"
                    :id="'btn_delete_order'"
                    :src="'/media/custom-imgs/icono_btn_borrar.svg'"
                    :width="16"
                    :height="16"
                    v-on:click="deleteOrderAction()"
                />
            </template>
            
        </div>
        <div class="col-12 pl-0 mt-15">
            <h3 class="color-blue" v-if="this.is_updating_order">Datos del cliente</h3>
            <div class="my-5 col-12 row" v-if="this.is_updating_order">
                <div class="input-group px-0 d-flex mt-5">
                    <div class="bg-span-gray py-2 br-5 w-25 my-auto">
                        <span class="font-weight-bolder color-white mx-5 f-15 text-dark my-auto">{{ this.name_company }}</span>
                    </div>
                    <div class="py-2 br-5 ml-5">
                        <span class="font-weight-bolder color-white mx-5 f-15 text-dark">CIF: {{ this.nif_company }}</span><br>
                        <span class="font-weight-bolder color-white mx-5 f-15 text-dark">Dirección: {{ this.address_company }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-15" v-if="(this.select_company != '' || this.select_company_other_values != '') && this.is_updating_order">
                <button type="button" class="btn bg-azul color-white px-5 font-weight-bolder" @click="this.openFormArticle()">
                    <img class="mr-2" width="24" height="24" src="/media/custom-imgs/icono_btn_annadir_articulo_blanco.svg" />
                    Añadir artículo
                </button>
            </div>
            
            <div class="mb-5 mt-15 col-12 row" v-if="orders.proposal_obj.chapters.length > 0">
                <div>
                    <img class="mr-2" width="150" height="150" src="/media/custom-imgs/icono_ficha_ordenes.svg" />
                </div>
                <div>
                    <div class="ml-10">
                        <div v-if="orders.status_view == 3 && orders.proposal_bd_obj != null"><h2 class="text-dark">Propuesta {{ orders.proposal_bd_obj.id_proposal_custom_aux }}</h2></div>
                        <div class="f-20">
                            <span class="text-dark font-weight-bold">Cliente: <span class="color-dark-gray font-weight-bold">{{ name_company }}</span></span>
                        </div>
                        <div class="mt-8">
                            <div class="d-flex">
                                <div class="d-block mr-20">
                                    <div class="f-16 color-dark-gray font-weight-bolder">
                                        <span>FECHA</span>
                                    </div>
                                    <div class="f-15 text-dark">
                                        <span>{{ this.$utils.getNow() }}</span>
                                    </div>
                                </div>
                                <div class="d-block mx-20">
                                    <div class="f-16 color-dark-gray font-weight-bolder">
                                        <span>CONSULTOR</span>
                                    </div>
                                    <div v-for="index in Number(orders.proposal_obj.array_consultants.length)" class="f-15 text-dark">
                                        <span>{{ orders.proposal_obj.array_consultants[index - 1].name }} - {{ orders.proposal_obj.array_consultants[index - 1].percentage }}%</span>
                                        <template v-if="this.is_updating_order">
                                            <button class="ml-3 btn bg-azul color-white px-2 py-0 font-weight-bolder" v-if="index == Number(orders.proposal_obj.array_consultants.length)" v-on:click="openAddConsultant()">+</button>
                                            <button v-if="index != 1" data-id="{{ orders.proposal_obj.array_consultants[index - 1].id }}" type="button" style="color: #2e49ff;background-color: #e7e7e7;" class="btn py-0 px-1 ml-2" v-on:click="showConsultanModal(index)"><img width="12" height="12" src="/media/custom-imgs/icono_btn_editar.svg"></button>
                                            <button v-if="index != 1" data-id="{{ orders.proposal_obj.array_consultants[index - 1].id }}" type="button" style="color: #2e49ff;background-color: #ffecf7;" class="btn py-0 px-1 ml-2" v-on:click="deleteConsultanForm(index)"><img width="12" height="12" src="/media/custom-imgs/icono_btn_borrar.svg"></button>
                                        </template>
                                    </div>
                                </div>
                                <div class="d-block mx-20">
                                    <div class="f-16 color-dark-gray font-weight-bolder">
                                        <span>DEPARTAMENTO</span>
                                    </div>
                                    <div class="f-15 text-dark">
                                        <span v-if="orders.proposal_obj.chapters[0].articles[0].department_obj != null">{{ orders.proposal_obj.chapters[0].articles[0].department_obj.name }}</span>
                                    </div>
                                </div>
                                <div class="d-block ml-20">
                                    <div class="f-16 color-dark-gray font-weight-bolder">
                                        <span>ANUNCIANTE</span>
                                    </div>
                                    <div class="f-15 text-dark">
                                        <input v-if="this.is_updating_order" v-model="advertiser" type="text" class="form-control discount bg-blue-light-white font-weight-bolder f-15 color-dark-gray not-border mt-3" style="width:150px;" placeholder=""/>
                                        <span v-else >{{ this.advertiser }}</span>
                                    </div>
                                </div>
                            </div>
                            <div style="float:left;">
                                <div class="border-blue-article mt-8">
                                    <div class="text-align-center px-5 mt-4" v-if="this.is_change_get_info && !this.is_updating_order">
                                        <span class="badge badge-light-success py-4 f-16 w-100 fw-bold">FIRMADA #202210EP-00039334</span>
                                    </div>
                                    <div class="d-flex">
                                        <div class="d-block mr-5 px-10 py-8">
                                            <div class="f-16 color-dark-gray text-align-center">
                                                <span>OFERTA</span>
                                            </div>
                                            <span class="p-input-icon-right w-100" v-if="this.is_updating_order">
                                                <input v-model="offer" type="text" class="form-control discount bg-blue-light-white font-weight-bolder f-15 color-dark-gray not-border mt-3" style="width:150px;" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" v-on:change="changeValueBox(1, 0)"/>
                                            </span>
                                            <div class="f-15 color-dark-gray font-weight-bolder px-8 py-2 mt-3" v-else>
                                                <span >{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(this.offer)) }}€</span>
                                            </div>
                                        </div>
                                        <div class="subheader-separator subheader-separator-ver my-auto py-14 bg-gray-light"></div>
                                        <div class="d-block mx-5 px-10 py-8">
                                            <div class="f-16 color-dark-gray text-align-center">
                                                <span>DESCUENTO</span>
                                            </div>
                                            <span class="p-input-icon-right w-100" v-if="this.is_updating_order">
                                                <input v-model="discount" type="text" class="form-control discount bg-blue-light-white font-weight-bolder f-15 color-dark-gray not-border mt-3" style="width:150px;" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0"  v-on:change="changeValueBox(2, 0)"/>
                                                <img width="13" class="pi my-auto" src="/media/custom-imgs/icono_porcentaje_input.svg"/>
                                            </span>
                                            <div class="f-15 color-dark-gray font-weight-bolder px-8 py-2 mt-3" v-else>
                                                <span >{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(this.discount)) }}%</span>
                                            </div>
                                        </div>
                                        <div class="subheader-separator subheader-separator-ver my-auto py-14 bg-gray-light"></div>
                                        <div class="d-block ml-5 px-10 py-8">
                                            <div class="f-16 color-dark-gray text-align-center">
                                                <span>TARIFA</span>
                                            </div>
                                            <div class="f-15 color-dark-gray font-weight-bolder px-8 py-2 mt-3">
                                                <span >{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(this.orders.proposal_obj.total_global_normal)) }}€</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-15 pl-0" v-if="(orders.proposal_obj.chapters.length > 0) || (orders.proposal_obj.chapters.length > 0 && this.is_change_get_info == 1)">
                <table width="100%" cellpadding="2" cellspacing="1">
                    <thead class="custom-columns-datatable">
						<tr>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>SERVICIOS</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>PVP</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>N</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" v-for="index in Number(orders.proposal_obj.array_dates.length)" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>{{ orders.proposal_obj.array_dates[index - 1].date }}</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>TOTAL</span></th>
                            <th v-if="this.is_updating_order" tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>ACCIÓN</span></th>
                        </tr>
					</thead>
                    <tbody>
                        <div class="d-contents" v-for="index in Number(orders.proposal_obj.chapters.length)">
                            <tr class="row-product">
                                <td class="py-2" :colspan="orders.proposal_obj.array_dates.length + 5">
                                    <span class="ml-5">{{ orders.proposal_obj.chapters[index - 1].chapter_obj.name }}</span>
                                </td>
                            </tr>
                            <tr class="row-article" v-for="index_article in Number(orders.proposal_obj.chapters[index - 1].articles.length)">
                                <td valign="middle" class="td-border-right"><span class="ml-5">{{ orders.proposal_obj.chapters[index - 1].articles[index_article - 1].article_obj.name }}</span></td>
                                <td valign="middle" class="td-border-right text-align-center"><span class="">{{ $utils.numberWithDotAndComma($utils.roundAndFix(orders.proposal_obj.chapters[index - 1].articles[index_article - 1].article_obj.pvp)) }}€</span></td>
                                <td valign="middle" class="td-border-right text-align-center"><span class="">{{ orders.proposal_obj.chapters[index - 1].articles[index_article - 1].amount }}</span></td>
                                <td v-for="index_arr_date in Number(orders.proposal_obj.array_dates.length)" valign="middle" class="td-border-right">
                                    <template v-for="index_dates in Number(orders.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices.length)">
                                        <div class="d-grid px-5">
                                            <template v-if="orders.proposal_obj.array_dates[index_arr_date - 1].date == orders.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].date">
                                                <template v-for="index_pvp_date in Number(orders.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date.length)">
                                                    <template v-for="index_pvp in Number(orders.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date[index_pvp_date - 1].arr_pvp.length)" >
                                                        <div class="d-flex">
                                                            <template v-if="this.value_form1.length > 0 && this.is_updating_order && !orders.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date[index_pvp_date - 1].arr_status_validate[index_pvp - 1]" >
                                                                <input v-model="this.value_form1[index - 1].article[index_article - 1].dates[index_dates - 1].date_pvp[index_pvp_date - 1].pvp[index_pvp - 1]" 
                                                                @input="changeValuesOffer($event)"
                                                                type="text" class="form-control discount bg-blue-light-white text-align-center not-border my-2" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0"/>
                                                                <button v-on:click="deleteOneArticle(orders.proposal_obj.chapters[index - 1].articles[index_article - 1], index_article, index_dates, index_pvp_date, index_pvp)" 
                                                                                v-if="this.value_form1.length > 0 && this.is_updating_order && !orders.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date[index_pvp_date - 1].arr_status_validate[index_pvp - 1]" type="button" class="btn p-0 mx-2 btn-delete">
                                                                    <img class="edit-hover" src="/media/custom-imgs/icono_tabla_eliminar.svg" height="30" width="30">
                                                                </button>
                                                            </template>
                                                            <span v-else-if="this.value_form1.length > 0 && this.is_change_get_info == 1" class="mx-auto py-3">{{ this.value_form1[index - 1].article[index_article - 1].dates[index_dates - 1].date_pvp[index_pvp_date - 1].pvp[index_pvp - 1] }}€</span>
                                                        </div>            
                                                    </template>                                    
                                                </template>
                                            </template>
                                        </div>
                                    </template>
                                </td>
                                <td v-if="this.discount != 0" valign="middle" class="td-border-right text-align-center">
                                    <span class="">{{ this.value_form1[index - 1].article[index_article - 1].total_aux }}€</span>
                                </td>
                                <td v-else valign="middle" class="td-border-right text-align-center"><span class="">{{ $utils.numberWithDotAndComma($utils.roundAndFix(orders.proposal_obj.chapters[index - 1].articles[index_article - 1].total)) }}€</span></td>
                                <td v-if="this.is_updating_order && !this.orders.proposal_obj.chapters[index - 1].articles[index_article - 1].is_article_billing" class="text-align-center bg-white"><span class="font-weight-bolder"><button type="button" class="btn" v-on:click="deleteArticle(orders.proposal_obj.chapters[index - 1].articles[index_article - 1].article_obj.id)"><img  width="40" height="40" src="/media/custom-imgs/icono_tabla_eliminar.svg" v-on:click="this.is_show_buttons_bill=false"/></button></span></td>
                            </tr>
                        </div>
                        <tr class="tr-total-datatable">
                            <td class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                            <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(orders.proposal_obj.total_individual_pvp)) }}€</span></td>
                            <td class="text-align-center"><span class="font-weight-bolder">{{ orders.proposal_obj.total_amount_global }}</span></td>
                            
                            <template v-if="this.discount != 0">
                                <td class="text-align-center" v-for="index in Number(orders.proposal_obj.array_dates.length)">
                                    <span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(this.value_form_discount[index - 1].pvp)) }}€</span>
                                </td>
                                <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(this.offer)) }}€</span></td>
                            </template>
                            <template v-else>
                                <td class="text-align-center" v-for="index in Number(orders.proposal_obj.array_dates.length)">
                                    <span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(orders.proposal_obj.array_dates[index - 1].total)) }}€</span>
                                </td>
                                <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(orders.proposal_obj.total_global)) }}€</span></td>
                            </template>
                            <td v-if="this.is_updating_order" class="text-align-center"><span class="font-weight-bolder"></span></td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
            
            <div class="col-12 pl-0 mt-10" v-if="orders.proposal_obj.chapters.length > 0 && this.is_updating_order">
                <span class="text-dark font-weight-bold mb-2">Tipo de propuesta</span>
                <select class="form-control bg-gray text-dark select-custom select-filter mt-3 col-2" :name="'select_type_proposal'" :id="'select_type_proposal'" v-model="select_type_proposal" data-style="select-lightgreen">
                    <option value="1" selected>Normal</option>
                    <option value="2">Intercambio con facturas</option>
                    <option value="3">Necesidades Peldaño</option>
                    <option value="4">Autopromoción</option>
                </select>
            </div>
            <div class="col-12 pl-0 mt-10" v-if="(orders.proposal_obj.chapters.length > 0 && this.is_show_buttons_bill) || (orders.proposal_obj.chapters.length > 0 && this.is_change_get_info == 1)">
                <table width="100%" cellpadding="2" cellspacing="1">
                    <thead class="custom-columns-datatable">
						<tr>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>FACTURAS</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FECHA</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FORMA DE PAGO</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>VENCIMIENTO</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>IMPORTE</span></th>
                            <th tabindex="0" v-if="this.is_change_get_info == 0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>ACCIÓN</span></th>
                        </tr>
					</thead>
                    <tbody>  
                        <template v-for="(item, index) in Number(orders.bill_obj.array_bills.length)" :key="index">
                            <tr v-if="orders.bill_obj.array_bills[index].status_validate" class="row-product text-align-center bg-validate-invoice">
                                <td class="td-border-right" rowspan="5">{{ index + 1 }}</td>
                            </tr>
                            <tr v-else class="row-product text-align-center">
                                <td class="td-border-right" rowspan="5">{{ index + 1 }}</td>
                            </tr>
                            <tr class="row-product">
                                <td class="text-align-center td-border-right" width="20%" v-if="orders.num_custom_invoices == 0 || !this.is_updating_order || !orders.bill_obj.array_bills[index].will_update">{{ orders.bill_obj.array_bills[index].date }}</td>
                                <td class="text-align-center td-border-right" width="20%" v-else>
                                    <Calendar class="w-100 borders-box text-dark-gray px-5"  autocomplete="off" v-model="orders.bill_obj.array_bills[index].date" dateFormat="dd-mm-yy"  />
                                </td>
                                <td class="text-align-center py-4 px-5 td-border-right" width="20%">
                                    <select v-if="this.is_updating_order && orders.bill_obj.array_bills[index].will_update" class="form-control text-dark select-custom select-filter bg-white" :name="'select_way_to_pay'" :id="'select_way_to_pay'" v-model="orders.bill_obj.array_bills[index].select_way_to_pay" data-style="select-lightgreen">
                                        <option v-for="(item, index) in Number(this.select_way_to_pay_options.length)" :key="index" :value="this.select_way_to_pay_options[index].value">{{ this.select_way_to_pay_options[index].text }}</option>
                                    </select>
                                    <span v-else>{{ this.select_way_to_pay_options[orders.bill_obj.array_bills[index].select_way_to_pay].text }}</span>
                                </td>
                                <td class="text-align-center py-4 px-5 td-border-right" width="20%">
                                    <select v-if="this.is_updating_order && orders.bill_obj.array_bills[index].will_update" class="form-control text-dark select-custom select-filter bg-white" :name="'select_expiration'" :id="'select_expiration'" v-model="orders.bill_obj.array_bills[index].select_expiration" data-style="select-lightgreen">
                                        <option v-for="(item, index) in Number(this.select_expiration_options.length)" :key="index" :value="this.select_expiration_options[index].value">{{ this.select_expiration_options[index].text }}</option>
                                    </select>
                                    <span v-else>{{ this.select_expiration_options[orders.bill_obj.array_bills[index].select_expiration].text }}</span>
                                </td>
                                <td class="text-align-center td-border-right" width="20%">
                                    {{ $utils.roundAndFix(orders.bill_obj.array_bills[index].amount) }}
                                </td>
                                <td v-if="this.is_updating_order" class="td-border-right text-align-center">
                                    <button v-if="orders.bill_obj.array_bills[index].will_update" type="button" class="btn"><img width="40" height="40" src="/media/custom-imgs/icono_tabla_aplicar_todos.svg" v-on:click="changeOptions(index)" /></button>
                                    <button v-if="orders.bill_obj.array_bills[index].will_update && orders.user_control.role_name == 'admin'" v-on:click="payInvoiceForm(orders.bill_obj.array_bills[index].id)"  class="btn" style="background-color: #e1f0ff;color: #2459d9">Abonar</button>
                                </td>
                            </tr>   
                            <tr class="row-article">
                                <td v-if="this.is_updating_order" class="p-5" colspan="6">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Observaciones</span>
                                        <input v-if="orders.bill_obj.array_bills[index].will_update" type="text" class="form-control bg-gray my-auto select-filter text-dark-gray col-10" v-model="orders.bill_obj.array_bills[index].observations" placeholder="Observaciones" />
                                        <span class="my-auto col-10" v-else>{{ orders.bill_obj.array_bills[index].observations }}</span>
                                    </div>
                                </td>
                                <td v-else class="p-5" colspan="5">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Observaciones</span>
                                        <span class="my-auto col-10">{{ orders.bill_obj.array_bills[index].observations }}</span>
                                    </div>
                                </td>
                            </tr>      
                            <tr class="row-article">
                                <td v-if="this.is_updating_order" class="p-5" colspan="6">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Núm. pedido</span>
                                        <input v-if="orders.bill_obj.array_bills[index].will_update" type="text" class="form-control bg-gray my-auto select-filter text-dark-gray col-10" v-model="orders.bill_obj.array_bills[index].order_number" placeholder="Número de pedido" />
                                        <span class="my-auto col-10" v-else>{{ orders.bill_obj.array_bills[index].order_number }}</span>
                                    </div>
                                </td>
                                <td v-else class="p-5" colspan="5">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Núm. pedido</span>
                                        <span class="my-auto col-10">{{ orders.bill_obj.array_bills[index].order_number }}</span>
                                    </div>
                                </td>
                            </tr>    
                            <tr class="row-article">
                                <td v-if="this.is_updating_order" class="p-5" colspan="6">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Observaciones Internas</span>
                                        <input v-if="orders.bill_obj.array_bills[index].will_update" type="text" class="form-control bg-gray my-auto select-filter text-dark-gray col-10" v-model="orders.bill_obj.array_bills[index].internal_observations" placeholder="Observaciones Internas" />
                                        <span class="my-auto col-10" v-else>{{ orders.bill_obj.array_bills[index].internal_observations }}</span>
                                    </div>
                                </td>
                                <td v-else class="p-5" colspan="5">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Observaciones Internas</span>
                                        <span class="my-auto col-10" >{{ orders.bill_obj.array_bills[index].internal_observations }}</span>
                                    </div>
                                </td>
                            </tr>   
                        </template>       
                        <tr class="tr-total-datatable">
                            <td v-if="this.is_change_get_info == 0" colspan="4" class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                            <td v-else colspan="4" class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                            <td class="text-align-right"><span class="font-weight-bolder mr-7">{{ $utils.roundAndFix(orders.bill_obj.total_bill) }}€</span></td>
                            <td v-if="this.is_updating_order" class="text-align-center bg-white"><span class="font-weight-bolder"><button type="button" class="btn"><img  width="40" height="40" src="/media/custom-imgs/icono_tabla_eliminar.svg" v-on:click="this.is_show_buttons_bill=false"/></button></span></td>
                        </tr>    
                    </tbody>
                </table>
            </div>
            
            <div class="col-12 pl-0 mt-10" v-if="orders.proposal_obj.chapters.length > 0 && this.is_updating_order">
                <div class="mt-7">
                    <div class="mt-10">
                        <button v-on:click="this.updateOrdeBtn()" type="button" class="btn bg-azul color-white px-30 font-weight-bolder">Finalizar orden</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <FormAddArticleComponent :type="3"></FormAddArticleComponent>
    <ModalUpdateOrder></ModalUpdateOrder>
    <ModalAddConsultantComponent ref="modal_add_consultant" :type="2"></ModalAddConsultantComponent>
</template>

<script>

import { mapMutations, mapState, mapActions } from "vuex";

import AddButtonComponent from "../Partials/AddButtonComponent.vue";
import DeleteButtonComponent from "../Partials/DeleteButtonComponent.vue"
import FormAddArticleComponent from "../Proposals/FormAddArticleComponent.vue";
import ModalUpdateOrder from "./ModalUpdateOrder.vue";
import ModalAddConsultantComponent from "../Proposals/ModalAddConsultantComponent.vue";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Textarea from 'primevue/textarea';
import Calendar from 'primevue/calendar';

export default {
    name: "FormComponentOrder",
    components: {
        AddButtonComponent,
        FormAddArticleComponent,
        DataTable,
        Column,
        Textarea,
        Calendar,
        DeleteButtonComponent,
        ModalUpdateOrder,
        ModalAddConsultantComponent
    },
    data() {
        return {
            publicPath: window.location.origin,
            select_company: '',
            array_companies: [],
            select_company_other_values: '',
            name_company: '',
            nif_company: '',
            address_company: '',
            advertiser: '',
            id_company: 0,
            offer: 0,
            total: 0,
            discount: '0.00',
            fullname: '',
            select_type_proposal: '1',
            value_form1: [], //Formulario presupuesto,
            value_form_discount: [],
            not_zero_discount: true,
            select_way_to_pay_options: [
                {value: '',text: 'Forma de pago'},
                {value: '1',text: 'Recibo bancario'},
                {value: '2',text: 'Talón nominativo'},
                {value: '3',text: 'Transferencia bancaria'},
                {value: '4',text: 'Letra aceptada'},
                {value: '5',text: 'Pagaré'},
                {value: '6',text: 'Metálico'},
                {value: '7',text: 'Especial camping'},
                {value: '8',text: 'Confirming'},
                {value: '9',text: 'Pago certificado'},
                {value: '10',text: 'Tarjeta'},
                {value: '11',text: 'Talón conformado'},
                {value: '12',text: 'Paypal'},
                {value: '13',text: '* Intercambio de facturas'},
                {value: '14',text: 'Especial Gaceta'}
            ],
            select_expiration_options: [
                {value: '',text: 'Vencimiento'},
                {value: '1',text: '15 días'},
                {value: '2',text: '30 días'},
                {value: '3',text: '30 y 60 días'},
                {value: '4',text: '40 días'},
                {value: '5',text: '45 días'},
                {value: '6',text: '60 días'},
                {value: '7',text: '90 días'},
                {value: '8',text: 'Al contado'},
                {value: '9',text: 'Al contado y 30 días'},
                {value: '10',text: 'Al contado y 60 días'},
                {value: '11',text: 'Al contado, 30 y 60 días'},
            ],
            is_show_buttons_bill: false,
            proposal_submission_settings: {
                commercial_name: '',
                language: '1',
                type_proyect: '1',
                name_proyect: '',
                date_proyect: '',
                objetives: '',
                proposal: 'Hemos creado una propuesta con diferentes acciones de demostrada eficacia. Es una comunicación 360 grados, de fuerte impacto, de gran repercusión y de calidad, que convencerá a nuestra audiencia de la necesidad de utilizar los chapteros y servicios de su empresa.\n\nEsta propuesta incluye la inmediatez e impacto directo de las nuevas tecnologías de movilidad, la efectividad del branded content, la utilización selectiva de nuestras bases de datos y el posicionamiento estratégico y de marca de los formatos publicitarios.',
                actions: 'Acciones Print: Acciones de marketing de contenido para conseguir credibilidad de marca, acciones de publicidad corporativa bien posicionadas para reforzar la relevancia, el posicionamiento estratégico y la diferenciación con la competencia.\n\nAcciones digitales: Acciones de gran impacto, como email marketing, banner y contenidos en web y newsletter, buscando la acción directa sobre la audiencia y la efectividad e inmediatez en el resultado. Además, estas acciones se potenciarán a través de nuestras redes sociales.\n\nAcciones Experiencias: Centradas en el patrocinio de un desayuno y en la participación en un evento de referencia sectorial, buscando la relación directa y personal con el cliente para la obtención de leads.',
                observations: '',
                show_discounts: 0,
                show_inserts: 1,
                show_invoices: 1,
                show_pvp: 1,
                sales_possibilities: '6',
                discount: 0,
                advertiser: ''
            },
            is_change_get_info: 0,
            is_change_get_info_input: 0,
            is_updating: 0,
            date_now: '',
            is_updating_order: 0,
            is_article_billing: false
        };
    },
    computed: {
        ...mapState(["errors", "orders"]),
    },
    methods: {
        ...mapMutations(["clearError", "changeViewStatusOrders", "changeProposalObj", "changeValueIsChangeArticle", "generateBill", "generateBillOrder",  "clearObjectsOrders", "deleteArticleOrder", "deleteConsultant"]),
        ...mapActions(["getCompanies", "updateOrder", "deleteOrder", "copyOrder", "payInvoice"]),
        openFormArticle(){
            $('#modal_form_article_proposals').modal('show');
        },
        openAddConsultant(){
            $('#modal_add_consultant').modal('show');
        },
        getNameCompany(id, type){
            let me = this;
            me.array_companies.forEach(function callback(value, index, array) {
                if(type == 1){
                    if(value.id == id){
                        me.name_company = value.name;
                        me.nif_company = value.nif;
                        me.address_company = value.address;
                        me.id_company = value.id;
                    }
                }else{
                    if(value.id_company == id){
                        me.name_company = value.name;
                        me.nif_company = value.nif;
                        me.address_company = value.address;
                        me.id_company = value.id;
                    }
                }
            });
            me.proposal_submission_settings.objetives = 'Somos consultores y expertos en comunicación. Nuestra marca y nuestros servicios son líderes en el sector, y tienen el máximo reconocimiento, prestigio e influencia. Sabemos qué quiere nuestra audiencia, lo que nos permite ofrecer a '+ me.name_company +' una propuesta de valor única, diferencial y de éxito.\n\nHemos estudiado el potencial y la proyección de '+ me.name_company +' con el fin de crear una propuesta de comunicación eficaz que permita mejorar sus resultados y objetivos.\n\nLas acciones de comunicación para '+ me.name_company +' que incluimos en esta propuesta crean influencia y potencian la visibilidad y la relevancia de sus chapteros, impactando positivamente en nuestra audiencia e incitando a la acción.';

        },
        changeValueBox(type, status){
            this.is_show_buttons_bill = false;
            if(type == 1){
                var difference = this.orders.proposal_obj.total_global_normal - this.offer;
                this.discount = this.$utils.roundAndFix(difference / (this.orders.proposal_obj.total_global_normal) * 100);

            }else{
                var difference = ((100 - this.discount) / 100) * this.orders.proposal_obj.total_global_normal;
                this.offer = parseFloat(this.$utils.roundAndFix(difference));

            }

            if(status == 0){
                //Recorremos el array valur_form1 y miramos cuantos inputs hay. Una vez contado los inputs, repartimos el valor de la oferta entre estos a partes iguales
                var total_inputs = this.rewalkForm1(1, 0);

                var new_value = this.offer / total_inputs;
                //Modificamos los valores de los inputs
                this.rewalkForm1(2, this.discount);
            }

            var params = {
                status: 0,
                form: this.value_form1,
                type: 3
            }
            this.not_zero_discount = false;
            if(status != undefined){
                this.changeProposalObj(params);
            }
           
        },
        rewalkForm1(type, new_value){
            let me = this;
            var value = 0;

            me.value_form1.map(function(product, key_product) {
                product.article.map(function(article_obj, key_article) {
                    article_obj.dates.map(function(date, key_dates) {
                        if(me.value_form_discount[key_dates] != undefined){
                            me.value_form_discount[key_dates].pvp = 0;
                            me.value_form_discount[key_dates].date = '';
                        }
                    });
                });
            });

            me.value_form1.map(function(product, key_product) {
                product.article.map(function(article_obj, key_article) {
                    //Reseteamos el total
                    me.value_form1[key_product].article[key_article].total_aux = 0;
                    article_obj.dates.map(function(date, key_dates) {
                        date.date_pvp.map(function(date_pvp, key_date_pvp) {
                            date_pvp.pvp.map(function(pvp_obj, key_pvp) {
                                if(type == 1){
                                    value += 1;
                                }else{
                                    if(me.discount != 0){
                                        pvp_obj = me.value_form1[key_product].article[key_article].dates[key_dates].article.pvp * (1 - (me.discount  /100));
                                        me.value_form1[key_product].article[key_article].dates[key_dates].date_pvp[key_date_pvp].pvp[key_pvp] = me.$utils.roundAndFix(pvp_obj);
                                        me.value_form1[key_product].article[key_article].total_aux += pvp_obj;
                                        
                                        if(key_product == 0){
                                            if(me.value_form_discount[key_dates] != undefined){
                                                if(me.value_form_discount[key_dates].date == ''){
                                                    me.value_form_discount[key_dates].pvp = pvp_obj;
                                                    me.value_form_discount[key_dates].date = me.$utils.changeFormatDate(date_pvp.date);
                                                }else if(me.value_form_discount[key_dates].date == me.$utils.changeFormatDate(date_pvp.date)){
                                                    me.value_form_discount[key_dates].pvp += pvp_obj;
                                                }
                                            }else{
                                                var new_obj = {
                                                    pvp: pvp_obj,
                                                    date:  me.$utils.changeFormatDate(date_pvp.date)
                                                }
                                                me.value_form_discount.push(new_obj);
                                            }
                                        }else{
                                            let is_search = false;
                                            me.value_form_discount.map(function(form_discount, key_form_discount) {
                                                if(form_discount.date == me.$utils.changeFormatDate(date_pvp.date)){
                                                    form_discount.pvp += pvp_obj;
                                                    is_search = true;
                                                }
                                            });
                                            if(!is_search){
                                                var new_obj_value_form_discount = {
                                                    pvp: pvp_obj,
                                                    date:  me.$utils.changeFormatDate(date_pvp.date)
                                                }
                                                me.value_form_discount.push(new_obj_value_form_discount);
                                            }
                                        }
                                        
                                        
                                        
                                    }else{
                                        me.value_form1[key_product].article[key_article].dates[key_dates].date_pvp[key_date_pvp].pvp[key_pvp] = me.$utils.roundAndFix(me.value_form1[key_product].article[key_article].dates[key_dates].article.pvp);
                                    }
                                }
                            });
                        });
                    });
                    //Formateamos el resultado del total con descuento
                    me.value_form1[key_product].article[key_article].total_aux = me.$utils.roundAndFix(me.value_form1[key_product].article[key_article].total_aux);
                });
            });
            if(type == 1){
                return value;
            }
        },
        changeValuesOffer(e) {
            let me = this;
            var total = 0;
            me.value_form1.map(function(chapter, key_chapter) {
                chapter.article.map(function(article_obj, key_article) {
                    article_obj.dates.map(function(date, key_dates) {
                        date.date_pvp.map(function(date_pvp, key_date_pvp) {
                            date_pvp.pvp.map(function(pvp_obj, key_pvp) {
                                total += Number(pvp_obj);
                            });
                        });
                    });
                });
            });
            me.offer = this.$utils.roundAndFix(total);
            this.changeValueBox(1);
            var params = {
                status: 1,
                form: me.value_form1,
                type: 3
            }
            this.is_change_get_info_input = 1;
           
            this.changeProposalObj(params);
            me.is_show_buttons_bill = false;
        },
        createBills(){
            this.is_show_buttons_bill = true;
            var params = {
                form: this.value_form1,
                num_custom_invoices: this.orders.num_custom_invoices,
                type: 2
            }
            this.generateBillOrder(params);
        },
        loadFormObj(){
            let me = this;
            me.value_form1 = [];
                
            //Rellenar los modelos de los inputs de la tabla
            me.orders.proposal_obj.chapters.map(function(chapter, key_chapter) {
                chapter.articles.map(function(article, key_article) {
                    me.orders.proposal_obj.array_dates.map(function(date_obj, key_arr_dates) {
                        article.dates_prices.map(function(date, key_dates) {
                            if(date_obj.date == date.date){
                                date.arr_pvp_date.map(function(pvp_date, key_pvp_date) {
                                    pvp_date.arr_pvp.map(function(pvp, key_pvp) {
                                        if(me.value_form1[key_chapter] == undefined){
                                            me.value_form1.push({
                                                article: [{
                                                    dates:[{
                                                        article: article.article_obj,
                                                        date_pvp: [{
                                                            date: pvp_date.date,
                                                            pvp: [],
                                                            pvp_default: [],
                                                            arr_status_validate: []
                                                        }]
                                                    }]
                                                }]
                                            });
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].arr_status_validate.push(pvp_date.arr_status_validate[key_pvp]);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp_default.push(article.article_obj.pvp);

                                        }else if(me.value_form1[key_chapter].article[key_article] == undefined){
                                            me.value_form1[key_chapter].article.push({
                                                dates:[{
                                                    article: article.article_obj,
                                                    date_pvp: [{
                                                        date: pvp_date.date,
                                                        pvp: [],
                                                        pvp_default: [],
                                                        arr_status_validate: []
                                                    }]
                                                }]
                                            });
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].arr_status_validate.push(pvp_date.arr_status_validate[key_pvp]);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp_default.push(article.article_obj.pvp);

                                        }else if(me.value_form1[key_chapter].article[key_article].dates[key_dates] == undefined){
                                            me.value_form1[key_chapter].article[key_article].dates.push({
                                                article: article.article_obj,
                                                date_pvp: [{
                                                    date: pvp_date.date,
                                                    pvp: [],
                                                    pvp_default: [],
                                                    arr_status_validate: []
                                                }]
                                            });
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].arr_status_validate.push(pvp_date.arr_status_validate[key_pvp]);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp_default.push(article.article_obj.pvp);

                                        }else if(me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date] == undefined){
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp.push({
                                                date: pvp_date.date,
                                                pvp: [],
                                                pvp_default: [],
                                                arr_status_validate: []
                                            });

                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].arr_status_validate.push(pvp_date.arr_status_validate[key_pvp]);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp_default.push(article.article_obj.pvp);

                                        }else{
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].arr_status_validate.push(pvp_date.arr_status_validate[key_pvp]);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp_default.push(article.article_obj.pvp);
                                        }
                                    });
                                });
                            }
                        });
                    });
                });
            });

            //Ponemos a cero los valores auxiliares
            me.value_form1.map(function(product, key_product) {
                product.article.map(function(article_obj, key_article) {
                    article_obj.dates.map(function(date, key_dates) {
                        if(me.value_form_discount[key_dates] != undefined){
                            me.value_form_discount[key_dates].pvp = 0;
                            me.value_form_discount[key_dates].date = '';
                        }
                    });
                });
            });

            if(this.is_change_get_info == 1){
                me.value_form1.map(function(product, key_product) {
                    product.article.map(function(article_obj, key_article) {
                        //Reseteamos el total
                        me.value_form1[key_product].article[key_article].total_aux = 0;
                        article_obj.dates.map(function(date, key_dates) {
                            date.date_pvp.map(function(date_pvp, key_date_pvp) {
                                date_pvp.pvp.map(function(pvp_obj, key_pvp) {
                                    if(me.discount != 0){
                                        if(me.is_change_get_info_input == 1){
                                            pvp_obj = Number(pvp_obj);
                                            me.value_form1[key_product].article[key_article].total_aux += pvp_obj;
                                            
                                            if(key_product == 0){
                                                if(me.value_form_discount[key_dates] != undefined){
                                                    if(me.value_form_discount[key_dates].date == ''){
                                                        me.value_form_discount[key_dates].pvp = pvp_obj;
                                                        me.value_form_discount[key_dates].date = me.$utils.changeFormatDate(date_pvp.date);
                                                    }else if(me.value_form_discount[key_dates].date == me.$utils.changeFormatDate(date_pvp.date)){
                                                        me.value_form_discount[key_dates].pvp += pvp_obj;
                                                    }
                                                }else{
                                                    var new_obj = {
                                                        pvp: pvp_obj,
                                                        date:  me.$utils.changeFormatDate(date_pvp.date)
                                                    }
                                                    me.value_form_discount.push(new_obj);
                                                }
                                            }else{
                                                let is_search = false;
                                                me.value_form_discount.map(function(form_discount, key_form_discount) {
                                                    if(form_discount.date == me.$utils.changeFormatDate(date_pvp.date)){
                                                        form_discount.pvp += pvp_obj;
                                                        is_search = true;
                                                    }
                                                });
                                                if(!is_search){
                                                    var new_obj_value_form_discount = {
                                                        pvp: pvp_obj,
                                                        date:  me.$utils.changeFormatDate(date_pvp.date)
                                                    }
                                                    me.value_form_discount.push(new_obj_value_form_discount);
                                                }
                                            }
                                        }else {
                                            pvp_obj = me.value_form1[key_product].article[key_article].dates[key_dates].article.pvp * (1 - (me.discount  /100));
                                            me.value_form1[key_product].article[key_article].total_aux += pvp_obj;
                                            
                                            if(key_product == 0){
                                                if(me.value_form_discount[key_dates] != undefined){
                                                    if(me.value_form_discount[key_dates].date == ''){
                                                        me.value_form_discount[key_dates].pvp = pvp_obj;
                                                        me.value_form_discount[key_dates].date = me.$utils.changeFormatDate(date_pvp.date);
                                                    }else if(me.value_form_discount[key_dates].date == me.$utils.changeFormatDate(date_pvp.date)){
                                                        me.value_form_discount[key_dates].pvp += pvp_obj;
                                                    }
                                                }else{
                                                    var new_obj = {
                                                        pvp: pvp_obj,
                                                        date:  me.$utils.changeFormatDate(date_pvp.date)
                                                    }
                                                    me.value_form_discount.push(new_obj);
                                                }
                                            }else{
                                                let is_search = false;
                                                me.value_form_discount.map(function(form_discount, key_form_discount) {
                                                    if(form_discount.date == me.$utils.changeFormatDate(date_pvp.date)){
                                                        form_discount.pvp += pvp_obj;
                                                        is_search = true;
                                                    }
                                                });
                                                if(!is_search){
                                                    var new_obj_value_form_discount = {
                                                        pvp: pvp_obj,
                                                        date:  me.$utils.changeFormatDate(date_pvp.date)
                                                    }
                                                    me.value_form_discount.push(new_obj_value_form_discount);
                                                }
                                            }
                                        }   
                                    }
                                    
                                });
                            });
                        });
                        //Formateamos el resultado del total con descuento
                        me.value_form1[key_product].article[key_article].total_aux = me.$utils.roundAndFix(me.value_form1[key_product].article[key_article].total_aux);
                    });
                });
            }
        },
        changeOptions(index){
            for(var i=index; i<this.orders.bill_obj.array_bills.length; i++){
                this.orders.bill_obj.array_bills[i].select_way_to_pay = this.orders.bill_obj.array_bills[index].select_way_to_pay;
                this.orders.bill_obj.array_bills[i].select_expiration = this.orders.bill_obj.array_bills[index].select_expiration;
                this.orders.bill_obj.array_bills[i].observations = this.orders.bill_obj.array_bills[index].observations;
                this.orders.bill_obj.array_bills[i].order_number = this.orders.bill_obj.array_bills[index].order_number;
                this.orders.bill_obj.array_bills[i].internal_observations = this.orders.bill_obj.array_bills[index].internal_observations;
            }
        },
        finishProposal(){
            let me = this;
            var is_empty = false;
            me.orders.bill_obj.array_bills.map(function(bill, key) {
                if(bill.select_expiration == '' || bill.select_way_to_pay == ''){
                    is_empty = true;
                }       

                if(typeof bill.date.getMonth === 'function'){
                    if(bill.date.toISOString().includes('Z')){
                        bill.date = me.$utils.customFormDate(bill.date);
                    }
                }
            });
            if(!is_empty){
                me.proposal_submission_settings.commercial_name = me.name_company;
                me.proposal_submission_settings.date_proyect = me.$utils.getNow();
                me.orders.status_view = 3;
            }else{
                swal("", "Rellena todos los datos", "warning");
            }
        },
        isDate(value) {
            var dateFormat;
            if (toString.call(value) === '[object Date]') {
                return true;
            }
            if (typeof value.replace === 'function') {
                value.replace(/^\s+|\s+$/gm, '');
            }
            dateFormat = /(^\d{1,4}[\.|\\/|-]\d{1,2}[\.|\\/|-]\d{1,4})(\s*(?:0?[1-9]:[0-5]|1(?=[012])\d:[0-5])\d\s*[ap]m)?$/;
            return dateFormat.test(value);
        },
        //Saber si estan rellenos observaciones, num pedido y observaciones internas
        countRows(obj){
            var rows = 2;
            if(obj.observations != ''){
                rows++;
            }
            if(obj.order_number != ''){
                rows++;
            }
            if(obj.internal_observations != ''){
                rows++;
            }
            return rows;
        },
        //Limpiar el data del component
        clearData(){
            let me = this;
            me.clearObjectsOrders();
            me.select_company = '';
            me.select_company_other_values = '';
            me.name_company = '';
            me.nif_company = '';
            me.address_company = '';
            me.id_company = 0;
            me.advertiser = '';
            me.offer = 0;
            me.total = 0;
            me.discount = '0.00';
            me.fullname = '';
            me.select_type_proposal = '1';
            me.is_show_buttons_bill = false;
            me.proposal_submission_settings.commercial_name = '';
            me.proposal_submission_settings.language = '1';
            me.proposal_submission_settings.type_proyect = '1';
            me.proposal_submission_settings.name_proyect = '';
            me.proposal_submission_settings.date_proyect = '';
            me.proposal_submission_settings.objetives = '';
            me.proposal_submission_settings.proposal = 'Hemos creado una propuesta con diferentes acciones de demostrada eficacia. Es una comunicación 360 grados, de fuerte impacto, de gran repercusión y de calidad, que convencerá a nuestra audiencia de la necesidad de utilizar los chapteros y servicios de su empresa.\n\nEsta propuesta incluye la inmediatez e impacto directo de las nuevas tecnologías de movilidad, la efectividad del branded content, la utilización selectiva de nuestras bases de datos y el posicionamiento estratégico y de marca de los formatos publicitarios.';
            me.proposal_submission_settings.actions = 'Acciones Print: Acciones de marketing de contenido para conseguir credibilidad de marca, acciones de publicidad corporativa bien posicionadas para reforzar la relevancia, el posicionamiento estratégico y la diferenciación con la competencia.\n\nAcciones digitales: Acciones de gran impacto, como email marketing, banner y contenidos en web y newsletter, buscando la acción directa sobre la audiencia y la efectividad e inmediatez en el resultado. Además, estas acciones se potenciarán a través de nuestras redes sociales.\n\nAcciones Experiencias: Centradas en el patrocinio de un desayuno y en la participación en un evento de referencia sectorial, buscando la relación directa y personal con el cliente para la obtención de leads.';
            me.proposal_submission_settings.observations = '';
            me.proposal_submission_settings.show_discounts = 0;
            me.proposal_submission_settings.show_inserts = 1;
            me.proposal_submission_settings.show_invoices = 1;
            me.proposal_submission_settings.show_pvp = 1;
            me.proposal_submission_settings.sales_possibilities = 6;
            me.proposal_submission_settings.discount = 0;
            me.proposal_submission_settings.advertiser = '';
            me.is_updating_order = 0;
        },
        //Modificar orden
        updateOrderFront(){
            $('#modal_reason_update_order').modal('show');
            //this.is_updating_order = 1;
        },
        returnView(){
            this.changeViewStatusOrders(1);
            this.clearObjectsOrders();
        },
        //Cargar datos de la propuesta
        loadViewInfoProposal(){
            this.is_change_get_info = this.orders.is_change_get_info;
            this.orders.is_change_get_info = 0;
            this.id_company = this.orders.id_company;
            this.select_company = this.id_company;
            this.array_companies = this.orders.company_aux;
            this.getNameCompany(this.select_company, 2);
            this.is_show_buttons_bill = true;
            this.proposal_submission_settings.commercial_name = this.orders.proposal_bd_obj.commercial_name;
            this.proposal_submission_settings.language = this.orders.proposal_bd_obj.language;
            this.proposal_submission_settings.type_proyect = this.orders.proposal_bd_obj.type_proyect;
            this.proposal_submission_settings.name_proyect = this.orders.proposal_bd_obj.name_proyect;
            this.proposal_submission_settings.date_proyect = this.orders.proposal_bd_obj.date_proyect;
            this.proposal_submission_settings.objetives = this.orders.proposal_bd_obj.objetives;
            this.proposal_submission_settings.proposal = this.orders.proposal_bd_obj.proposal;
            this.proposal_submission_settings.actions = this.orders.proposal_bd_obj.actions;
            this.proposal_submission_settings.observations = this.orders.proposal_bd_obj.observations;
            this.proposal_submission_settings.show_discounts = this.orders.proposal_bd_obj.show_discounts;
            this.proposal_submission_settings.show_inserts = 0;
            this.proposal_submission_settings.show_invoices = this.orders.proposal_bd_obj.show_invoices;
            this.proposal_submission_settings.show_pvp = this.orders.proposal_bd_obj.show_pvp;
            this.proposal_submission_settings.sales_possibilities = this.orders.proposal_bd_obj.sales_possibilities;
            this.proposal_submission_settings.discount = this.orders.proposal_bd_obj.discount;
            this.proposal_submission_settings.advertiser = this.orders.proposal_bd_obj.advertiser;
            this.proposal_submission_settings.type_proposal = this.orders.proposal_bd_obj.type_proposal;
            this.select_type_proposal = this.orders.proposal_bd_obj.type_proposal;
            this.advertiser = this.orders.proposal_bd_obj.advertiser;
            this.discount = this.proposal_submission_settings.discount;
            this.offer = Math.round(Number(this.orders.bill_obj.total_bill) * 100) / 100; //this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(this.proposals.bill_obj.total_bill));

            //Comprobamos si alguno de los artículos está facturado para pintar o no el botón de eliminar
            this.orders.proposal_obj.chapters.map(function(chapter, key_chapter) {
                chapter.articles.map(function(article, key_article) {
                    article.dates_prices.map(function(date_price, key_date_price) {
                        date_price.arr_pvp_date.map(function(pvp_date, key_pvp_date) {
                            pvp_date.arr_status_validate.map(function(status_validate, key_status_validate) {
                                if(status_validate == 1){
                                    article.is_article_billing = 1;
                                }
                            });
                        });
                    });
                });
            });

            this.loadFormObj(); 
        },
        //Eliminar propuesta
        deleteOrderAction(){
            let me = this;
            let id_order = this.orders.proposal_obj.id_order;
            swal({
                title: "¿Eliminar orden?",
                text: "¿Está seguro? Esta orden se eliminará",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Eliminar",
                cancelButtonText: "Cancelar",
                closeOnCancel: true,
                closeOnConfirm: false
            }, function(isConfirm) {
                if (isConfirm) {
                    me.deleteOrder(id_order);
                }
            });
        },
        //Actualizar propuesta
        updateOrdeBtn(){
            let me = this;
            var is_empty = false;
            me.orders.bill_obj.array_bills.map(function(bill, key) {
                if(bill.select_expiration == '' || bill.select_way_to_pay == ''){
                    is_empty = true;
                }       
            });
            if(!is_empty){
                var params = {
                    'id_order': this.orders.proposal_obj.id_order,
                    'bill_obj': this.orders.bill_obj,
                    'discount': this.discount,
                    'reason_update': this.orders.reason_update,
                    'array_consultants': this.orders.proposal_obj.array_consultants,
                    'advertiser': this.advertiser
                }
                this.updateOrder(params);

            }else{
                swal("", "Rellena todos los datos", "warning");
            }
        },
        copyOrderBtn(){
            this.copyOrder(this.orders.proposal_obj.id_order);
        },
        //Eliminar articulo de la tabla
        deleteArticle(id){
            let me = this;
            me.orders.proposal_obj.chapters.map(function(chapter, key_chapter) {
                chapter.articles.map(function(article, key_article) {
                    if(article.article_obj.id == id){
                        me.orders.proposal_obj.chapters[key_chapter].articles.splice(key_article, 1);
                    }
                });
            });

            //Consultamos si existe algún artículo
            var reload = false;
            var article_exist = false;
            me.orders.proposal_obj.chapters.map(function(chapter, key_chapter) {
                chapter.articles.map(function(article, key_article) {
                    article_exist = true;
                });
                if(!article_exist){
                    me.orders.proposal_obj.chapters.splice(key_chapter, 1);
                    reload = true;
                }
            });
            if(reload){
                me.loadFormObj();
            }

            me.createBills();
            
        },
        deleteConsultanForm(id){
            var params = {
                id: id,
                type: 2
            }
            this.deleteConsultant(params);
        },
        showConsultanModal(id){
            let me = this;
            $('#id_consultant').val(id);
            me.orders.proposal_obj.array_consultants.forEach( function(value, index, array) {
                if(value.id_consultant == id){
                    $('#select_consultant').val(id);
                    me.$refs.modal_add_consultant.id_consultant = id;
                    me.$refs.modal_add_consultant.select_consultant = id;
                    me.$refs.modal_add_consultant.percentage = value.percentage;
                    me.$refs.modal_add_consultant.title_modal = 'Actualizar consultor';
                    me.$refs.modal_add_consultant.is_update = 1;
                    $('#select_consultant').prop('disabled', true);
                }
            });
            $('#modal_add_consultant').modal('show');
        },
        //Abonar factura
        payInvoiceForm(id){
            this.payInvoice(id);
        },
        //Eliminar un articulo de una fecha
        deleteOneArticle(articles, index_article, index_dates, index_pvp_date, index_pvp){
            let me = this;
            me.orders.proposal_obj.chapters.map(function(chapter, key_chapter) {
                if(articles.chapter_obj.id == chapter.chapter_obj.id){
                    chapter.articles.map(function(article, key_article) {
                        if(article.article_obj.id == articles.article_obj.id){
                            article.dates_prices.map(function(date_price, key_date_price) {
                                if(key_date_price == (index_dates - 1)){
                                    date_price.arr_pvp_date.map(function(pvp_date, key_pvp_date) {
                                        if(key_pvp_date == (index_pvp_date - 1)){
                                            pvp_date.arr_pvp.map(function(pvp, key_pvp) {
                                                if(key_pvp == (index_pvp - 1)){
                                                    me.orders.proposal_obj.array_dates.map(function(date, key_pvp) {
                                                        if(article.dates_prices[key_date_price].date == date.date){
                                                            date.total -= pvp;
                                                        }
                                                    });

                                                    article.dates_prices[key_date_price].arr_pvp_date[key_pvp_date].arr_pvp.splice(key_pvp, 1);
                                                    article.dates_prices[key_date_price].arr_pvp_date[key_pvp_date].arr_status_validate.splice(key_pvp, 1);

                                                    if(article.dates_prices[key_date_price].arr_pvp_date[key_pvp_date].arr_pvp.length == 0){
                                                        me.orders.proposal_obj.chapters[key_chapter].articles[key_article].dates_prices[key_date_price].arr_pvp_date.splice(key_pvp_date, 1);
                                                    }

                                                    if(article.dates_prices[key_date_price].arr_pvp_date.length == 0){
                                                        me.orders.proposal_obj.chapters[key_chapter].articles[key_article].dates_prices.splice(key_date_price, 1);
                                                        me.orders.proposal_obj.chapters[key_chapter].articles[key_article].dates.splice(key_date_price, 1);
                                                        //me.orders.proposal_obj.array_dates.splice(key_date_price, 1);
                                                    }

                                                    if(article.dates_prices.length == 0){
                                                        me.orders.proposal_obj.chapters[key_chapter].articles.splice(key_article, 1);
                                                    }

                                                    article.amount -= 1;
                                                    article.total -= pvp;
                                                    me.orders.proposal_obj.total_amount_global -= 1;
                                                    me.orders.proposal_obj.total_global_normal -= pvp;
                                                    me.orders.proposal_obj.total_global -= pvp;
                                                }
                                            });
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });

            //Consultamos si tenemos que eliminar alguna columna de fechas
            me.orders.proposal_obj.array_dates.map(function(date, key_date) {
                var exist = false;
                me.orders.proposal_obj.chapters.map(function(chapter, key_chapter) {
                    chapter.articles.map(function(article, key_article) {
                        article.dates_prices.map(function(date_price, key_date_price) {
                            if(date_price.date == date.date){
                                exist = true;
                            }
                        });
                    });
                });
                if(!exist){
                    me.orders.proposal_obj.array_dates.splice(key_date, 1);
                }
            });

            me.loadFormObj();
            me.createBills();
        }
    },
    mounted() {
        this.date_now = this.$utils.getNow();
        if(this.errors.type_error == "get_info_proposal"){
            this.loadViewInfoProposal();  
        }

        this.clearError();
    },
    watch: {
        '$store.state.errors.code': function() {
            if(this.errors.type_error == 'update_order'){
                if(this.errors.code != ''){
                    if(this.errors.code == 1000){
                        $("#list_orders").KTDatatable("reload");
                        this.orders.status_view = 1;
                        this.clearData();
                        if(this.errors.msg != ''){
                            var url = this.errors.msg;
                            this.errors.msg = '';
                            window.open(url);
                        }
                        swal("", "Orden actualizada correctamente", "success");
                    }else{
                        swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                    }
                }
            }else if(this.errors.type_error == 'delete_order'){
                if(this.errors.code != ''){
                    if(this.errors.code == 1000){
                        $("#list_orders").KTDatatable("reload");
                        this.orders.status_view = 1;
                        this.clearData();
                        if(this.errors.msg != ''){
                            var url = this.errors.msg;
                            this.errors.msg = '';
                            window.open(url);
                        }
                        swal("", "Orden eliminada correctamente", "success");
                    }else if(this.errors.code == 1002){
                        swal("", "No es posible eliminar la orden ya que una de las facturas ha sido emitida", "warning");

                    }else{
                        swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                    }
                }
            }else if(this.errors.type_error == 'copy_order'){
                if(this.errors.code != ''){
                    if(this.errors.code == 1000){
                        $("#list_orders").KTDatatable("reload");
                        this.orders.status_view = 1;
                        this.clearData();
                        if(this.errors.msg != ''){
                            var url = this.errors.msg;
                            this.errors.msg = '';
                            window.open(url);
                        }
                        swal("", "Orden copiada correctamente", "success");
                    }else if(this.errors.code == 1002){
                        swal("", "No es posible eliminar la orden ya que una de las facturas ha sido emitida", "warning");

                    }else{
                        swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                    }
                }
            }else if(this.errors.type_error == 'delete_article_order'){
                if(this.errors.code != ''){
                    if(this.errors.code == 1000){
                        this.loadFormObj();
                        var params = {
                            form: this.value_form1,
                            num_custom_invoices: this.orders.num_custom_invoices,
                            type: 2
                        }
                        this.generateBillOrder(params);
                    }
                }
            }else if(this.errors.type_error == 'pay_invoice'){
                if(this.errors.code != ''){
                    if(this.errors.code == 1000){
                        swal("", "Factura abonada correctamente", "success");
                    }
                }
            }
            this.clearError();
        },
        '$store.state.orders.proposal_obj.is_change': function() {
            if(this.orders.proposal_obj.is_change == 1){
                let me = this;
                me.is_show_buttons_bill = false;
                if(me.orders.proposal_obj.is_change){
                    me.changeValueIsChangeArticle();
                    me.offer = me.$utils.roundAndFix(me.orders.proposal_obj.total_global);
                    me.total = me.$utils.roundAndFix(me.orders.proposal_obj.total_global);
                    if(this.not_zero_discount){
                        me.discount = 0;
                    }
                    this.not_zero_discount = true;
                    me.is_change_get_info = 1;
                }else{
                    me.is_change_get_info = 0;
                }

                me.loadFormObj();
                this.createBills();
                this.orders.proposal_obj.is_change = 0;
            }
        },
        '$store.state.orders.array_companies': function() {
            if(this.orders.is_change_get_info == 1){
                this.is_change_get_info = this.orders.is_change_get_info;
                this.orders.is_change_get_info = 0;
                this.id_company = this.orders.id_company;
                this.select_company = this.id_company;
                this.getNameCompany(this.select_company, 1);
                this.is_show_buttons_bill = true;
                this.proposal_submission_settings.commercial_name = this.orders.proposal_bd_obj.commercial_name;
                this.proposal_submission_settings.language = this.orders.proposal_bd_obj.language;
                this.proposal_submission_settings.type_proyect = this.orders.proposal_bd_obj.type_proyect;
                this.proposal_submission_settings.name_proyect = this.orders.proposal_bd_obj.name_proyect;
                this.proposal_submission_settings.date_proyect = this.orders.proposal_bd_obj.date_proyect;
                this.proposal_submission_settings.objetives = this.orders.proposal_bd_obj.objetives;
                this.proposal_submission_settings.proposal = this.orders.proposal_bd_obj.proposal;
                this.proposal_submission_settings.actions = this.orders.proposal_bd_obj.actions;
                this.proposal_submission_settings.observations = this.orders.proposal_bd_obj.observations;
                this.proposal_submission_settings.show_discounts = this.orders.proposal_bd_obj.show_discounts;
                this.proposal_submission_settings.show_inserts = 0;
                this.proposal_submission_settings.show_invoices = this.orders.proposal_bd_obj.show_invoices;
                this.proposal_submission_settings.show_pvp = this.orders.proposal_bd_obj.show_pvp;
                this.proposal_submission_settings.sales_possibilities = this.orders.proposal_bd_obj.sales_possibilities;
                this.proposal_submission_settings.discount = this.orders.proposal_bd_obj.discount;
                this.discount =  this.proposal_submission_settings.discount;
                this.proposal_submission_settings.advertiser = this.orders.proposal_bd_obj.advertiser;
                this.advertiser =  this.proposal_submission_settings.advertiser;
                this.loadFormObj();        
                this.offer = this.orders.bill_obj.total_bill;
            }
        },  
        '$store.state.orders.reason_update': function() {
            this.is_updating_order = 1;
        },  
    },
};
</script>