<template>
    <div>
        <div class="col-12 d-flex flex-wrap justify-content-between">
            <template v-if="(proposals.status_view == 2 && proposals.proposal_bd_obj != null && !this.create_order && !this.is_updating && !this.is_copy)">
                <AddButtonComponent
                    :columns="'ml-auto mr-7'"
                    :text="'Volver'"
                    :id="'btn_return'"
                    :src="'/media/custom-imgs/flecha_btn_volver.svg'"
                    :width="16"
                    :height="16"
                    @click.native="returnView()"
                />
                <AddButtonComponent
                    :columns="'mr-7'"
                    :text="'Crear orden'"
                    :id="'btn_create_order'"
                    :src="'/media/custom-imgs/icono_btn_crear_orden.svg'"
                    :width="16"
                    :height="16"
                    @click.native="createOrderView()"
                />
                <AddButtonComponent
                    :columns="'mr-7'"
                    :text="'Copiar'"
                    :id="'btn_copy_order'"
                    :src="'/media/custom-imgs/icono_btn_crear_orden.svg'"
                    :width="16"
                    :height="16"
                    @click.native="copyOrderView()"
                />
                <AddButtonComponent
                    :columns="'mr-7'"
                    :text="'Modificar'"
                    :id="'btn_update_proposal'"
                    :src="'/media/custom-imgs/icono_btn_editar.svg'"
                    :width="16"
                    :height="16"
                    @click.native="updateProposalFront()"
                />
                <DeleteButtonComponent
                    :columns="''"
                    :text="'Eliminar'"
                    :id="'btn_add_user'"
                    :src="'/media/custom-imgs/icono_btn_borrar.svg'"
                    :width="16"
                    :height="16"
                    @click.native="deleteProposalAction()"
                />
                
            </template>
            <template v-else>
                <AddButtonComponent
                    :columns="'ml-auto'"
                    :text="'Volver'"
                    :id="'btn_return'"
                    :src="'/media/custom-imgs/flecha_btn_volver.svg'"
                    :width="16"
                    :height="16"
                    @click.native="returnView()"
                />
            </template>
            
        </div>
        <div class="col-12 pl-0 mt-15">
            <h3 class="color-blue" v-if="!this.finish_proposal && !this.create_order">Datos del cliente</h3>
            <div class="my-5 col-12 row" v-if="!this.finish_proposal && !this.generate_proposal && !this.create_order">
                <div class="input-group px-0 d-flex" v-if="this.select_company == '' && this.select_company_other_values == ''">
                    <div class="w-25">
                        <span class="w-25">Empresa o nombre y apellidos</span>
                        <div class="mt-2 select-filter">
                            <select class="form-control select2 select-filter" id="select_company" v-model="select_company">
                                <option :data-name="company.name" :value="company.id" v-for="company in proposals.array_companies" :key="company.id" v-text="company.name + ' - ' + company.fullname" ></option>
                            </select>
                        </div>
                    </div>
                    <div class="w-25 ml-10">
                        <span class="w-25">Otros (localidad, e-mail, cif/nif, tlf, cp)</span>
                        <div class="mt-2 select-filter">
                            <select class="form-control select2 select-filter" id="select_company_other_values" v-model="select_company_other_values">
                                <option :data-name="company.name" :value="company.id" v-for="company in proposals.array_companies" :key="company.id" v-text="company.email + ' - ' + company.nif" ></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="input-group px-0 d-flex mt-5" v-else>
                    <div class="bg-span-gray py-2 w-25 br-5">
                        <span class="font-weight-bolder color-white ml-5 f-15 text-dark">{{ this.name_company }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-15" v-if="(this.select_company != '' || this.select_company_other_values != '') && !this.finish_proposal && !this.generate_proposal && !this.create_order">
                <button type="button" class="btn bg-azul color-white px-5 font-weight-bolder" @click="this.openFormArticle()">
                    <img class="mr-2" width="24" height="24" src="/media/custom-imgs/icono_btn_annadir_articulo_blanco.svg" />
                    Añadir artículo
                </button>
            </div>
            <div class="mb-5 mt-15 col-12 row" v-if="this.proposals.proposal_obj.chapters[0].chapter_obj != null && !this.generate_proposal">
                <div>
                    <img class="mr-2" width="150" height="150" src="/media/custom-imgs/icono_ficha_ordenes.svg" />
                </div>
                <div>
                    <div class="ml-10">
                        <div v-if="proposals.status_view == 2 && proposals.proposal_bd_obj != null && !this.is_copy"><h2 class="text-dark">Propuesta {{ proposals.proposal_bd_obj.id_proposal_custom_aux }}</h2></div>
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
                                        <span v-if="!this.create_order && !this.is_change_get_info">{{ this.$utils.getNow() }}</span>
                                        <span v-else>{{ this.proposal_submission_settings.date_proyect }}</span>
                                    </div>
                                </div>
                                <div class="d-block mx-20">
                                    <div class="f-16 color-dark-gray font-weight-bolder">
                                        <span>CONSULTOR</span>
                                    </div>
                                    <div class="f-15 text-dark">
                                        <span v-if="proposals.user_obj != null">{{ proposals.user_obj.name + ' ' + proposals.user_obj.surname }}</span>
                                    </div>
                                </div>
                                <div class="d-block mx-20">
                                    <div class="f-16 color-dark-gray font-weight-bolder">
                                        <span>DEPARTAMENTO</span>
                                    </div>
                                    <div class="f-15 text-dark">
                                        <span v-if="proposals.proposal_obj.chapters[0].articles[0].department_obj != null">{{ proposals.proposal_obj.chapters[0].articles[0].department_obj.name }}</span>
                                    </div>
                                </div>
                                <div class="d-block ml-20">
                                    <div class="f-16 color-dark-gray font-weight-bolder">
                                        <span>ANUNCIANTE</span>
                                    </div>
                                    <div class="f-15 text-dark">
                                        {{ name_company }}
                                    </div>
                                </div>
                            </div>
                            <div style="float:left;">
                                <div class="d-flex border-blue-article mt-8">
                                    <div class="d-block mr-5 px-10 py-8">
                                        <div class="f-16 color-dark-gray text-align-center">
                                            <span>OFERTA</span>
                                        </div>
                                        <span class="p-input-icon-right w-100" v-if="!this.finish_proposal && !this.create_order">
                                            <input v-model="offer" type="text" class="form-control discount bg-blue-light-white font-weight-bolder f-15 color-dark-gray not-border mt-3" style="width:150px;" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" v-on:change="changeValueBox(1, 0)"/>
                                        </span>
                                        <div class="f-15 color-dark-gray font-weight-bolder px-8 py-2 mt-3" v-else>
                                            <span v-if="!this.create_order">{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(this.offer)) }}€</span>
                                            <span v-else>{{ this.offer }}€</span>
                                        </div>
                                    </div>
                                    <div class="subheader-separator subheader-separator-ver my-auto py-14 bg-gray-light"></div>
                                    <div class="d-block mx-5 px-10 py-8">
                                        <div class="f-16 color-dark-gray text-align-center">
                                            <span>DESCUENTO</span>
                                        </div>
                                        <span class="p-input-icon-right w-100" v-if="!this.finish_proposal && !this.create_order">
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
                                            <span >{{ this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(this.proposals.proposal_obj.total_global_normal)) }}€</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-15 pl-0" v-if="proposals.proposal_obj.chapters[0].chapter_obj != null && !this.finish_proposal && !this.generate_proposal">
                <table width="100%" cellpadding="2" cellspacing="1">
                    <thead class="custom-columns-datatable">
						<tr>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>SERVICIOS</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>PVP</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>N</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" v-for="index in Number(proposals.proposal_obj.array_dates.length)" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>{{ proposals.proposal_obj.array_dates[index - 1].date }}</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>TOTAL</span></th>
                        </tr>
					</thead>
                    <tbody>
                        
                        <div class="d-contents" v-for="index in Number(proposals.proposal_obj.chapters.length)">
                            <tr class="row-product">
                                <td class="py-2" :colspan="proposals.proposal_obj.array_dates.length + 4">
                                    <span class="ml-5">{{ proposals.proposal_obj.chapters[index - 1].chapter_obj.name }}</span>
                                </td>
                            </tr>
                            <tr class="row-article" v-for="index_article in Number(proposals.proposal_obj.chapters[index - 1].articles.length)">
                                <td valign="middle" class="td-border-right"><span class="ml-5">{{ proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].article_obj.name }}</span></td>
                                <td valign="middle" class="td-border-right text-align-center"><span class="">{{ $utils.numberWithDotAndComma($utils.roundAndFix(proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].article_obj.pvp)) }}€</span></td>
                                <td valign="middle" class="td-border-right text-align-center"><span class="">{{ proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].amount }}</span></td>
                                <td v-for="index_arr_date in Number(proposals.proposal_obj.array_dates.length)" valign="middle" class="td-border-right">
                                    <template v-for="index_dates in Number(proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices.length)">
                                        <template v-if="proposals.proposal_obj.array_dates[index_arr_date - 1].date == proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].date">
                                            <div class="d-grid px-5">
                                               <template v-for="index_pvp_date in Number(proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date.length)">
                                                    <input v-if="this.value_form1.length > 0 && !this.create_order" 
                                                    v-model="this.value_form1[index - 1].article[index_article - 1].dates[index_dates - 1].date_pvp[index_pvp_date - 1].pvp[index_pvp - 1]" 
                                                    v-for="index_pvp in Number(proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date[index_pvp_date - 1].arr_pvp.length)" 
                                                    @input="changeValuesOffer($event)"
                                                    type="text" class="form-control discount bg-blue-light-white text-align-center not-border my-2" placeholder="" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0"/>
                                                    <span v-for="index_pvp in Number(proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date[index_pvp_date - 1].arr_pvp.length)"  v-if="this.value_form1.length > 0 && this.create_order" class="mx-auto py-3">{{ this.value_form1[index - 1].article[index_article - 1].dates[index_dates - 1].date_pvp[index_pvp_date - 1].pvp[index_pvp - 1] }}€</span>
                                                </template>
                                            </div>
                                        </template>
                                    </template>
                                </td>
                                <td v-if="this.discount != 0" valign="middle" class="td-border-right text-align-center">
                                    <span class="">{{ this.value_form1[index - 1].article[index_article - 1].total_aux }}€</span>
                                </td>
                                <td v-else valign="middle" class="td-border-right text-align-center"><span class="">{{ $utils.numberWithDotAndComma($utils.roundAndFix(proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].total)) }}€</span></td>
                            </tr>
                        </div>
                        <tr class="tr-total-datatable">
                            <td class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                            <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(proposals.proposal_obj.total_individual_pvp)) }}€</span></td>
                            <td class="text-align-center"><span class="font-weight-bolder">{{ proposals.proposal_obj.total_amount_global }}</span></td>
                            <template v-if="this.discount != 0">
                                <td class="text-align-center" v-for="index in Number(proposals.proposal_obj.array_dates.length)">
                                    <span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(this.value_form_discount[index - 1].pvp)) }}€</span>
                                </td>
                                <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(this.offer)) }}€</span></td>
                            </template>
                            <template v-else>
                                <td class="text-align-center" v-for="index in Number(proposals.proposal_obj.array_dates.length)">
                                    <span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(proposals.proposal_obj.array_dates[index - 1].total)) }}€</span>
                                </td>
                                <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(proposals.proposal_obj.total_global)) }}€</span></td>
                            </template>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
            <div class="col-12 pl-0 mt-10" v-if="proposals.proposal_obj.chapters[0].chapter_obj != null && !this.finish_proposal && !this.generate_proposal && !this.create_order">
                <span class="text-dark font-weight-bold mb-2">Tipo de propuesta</span>
                <select class="form-control bg-gray text-dark select-custom select-filter mt-3 col-2" :name="'select_type_proposal'" :id="'select_type_proposal'" v-model="select_type_proposal" data-style="select-lightgreen">
                    <option value="1" selected>Normal</option>
                    <option value="2">Intercambio con facturas</option>
                    <option value="3">Necesidades Peldaño</option>
                    <option value="4">Autopromoción</option>
                </select>
                <div class="mt-10" v-if="!is_show_buttons_bill">
                    <button type="submit" class="btn bg-azul color-white px-5 font-weight-bolder mr-4" @click.native="createBills()">Crear factura simple</button>
                    <button type="submit" class="btn bg-azul color-white px-5 font-weight-bolder ml-4" @click.native="openCustomInvoice()">Crear factura personalizada</button>
                </div>
            </div>
            <div class="col-12 pl-0 mt-10" v-if="proposals.proposal_obj.chapters[0].chapter_obj != null && this.is_show_buttons_bill && !this.finish_proposal && !this.generate_proposal">
                <table width="100%" cellpadding="2" cellspacing="1">
                    <thead class="custom-columns-datatable">
						<tr>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>FACTURAS</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FECHA</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FORMA DE PAGO</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>VENCIMIENTO</span></th>
                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>IMPORTE</span></th>
                            <th tabindex="0" v-if="!this.create_order" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>ACCIÓN</span></th>
                        </tr>
					</thead>
                    <tbody>  
                        <template v-for="(item, index) in Number(proposals.bill_obj.array_bills.length)" :key="index">
                            <tr class="row-product text-align-center">
                                <td class="td-border-right" rowspan="5">{{ index + 1 }}</td>
                            </tr>
                            <tr class="row-product">
                                <td class="text-align-center td-border-right" v-if="proposals.num_custom_invoices == 0">{{ proposals.bill_obj.array_bills[index].date }}</td>
                                <td class="text-align-center td-border-right" v-else>
                                    <Calendar class="w-100 borders-box text-dark-gray px-5"  autocomplete="off" v-model="proposals.bill_obj.array_bills[index].date" dateFormat="dd-mm-yy"  />
                                </td>
                                <td class="text-align-center py-4 px-5 td-border-right" width="20%">
                                    <template v-if="!this.create_order">
                                        <select class="form-control text-dark select-custom select-filter bg-white" :name="'select_way_to_pay'" :id="'select_way_to_pay'" v-model="proposals.bill_obj.array_bills[index].select_way_to_pay" data-style="select-lightgreen">
                                            <option v-for="(item, index) in Number(this.select_way_to_pay_options.length)" :key="index" :value="this.select_way_to_pay_options[index].value">{{ this.select_way_to_pay_options[index].text }}</option>
                                        </select>
                                    </template>
                                    <template v-else>
                                        <select v-if="this.date_now < this.proposal_submission_settings.date_proyect" class="form-control text-dark select-custom select-filter bg-white" :name="'select_way_to_pay'" :id="'select_way_to_pay'" v-model="proposals.bill_obj.array_bills[index].select_way_to_pay" data-style="select-lightgreen">
                                            <option v-for="(item, index) in Number(this.select_way_to_pay_options.length)" :key="index" :value="this.select_way_to_pay_options[index].value">{{ this.select_way_to_pay_options[index].text }}</option>
                                        </select>
                                        <span v-else>{{ this.select_way_to_pay_options[proposals.bill_obj.array_bills[index].select_way_to_pay].text }}</span>
                                    </template>
                                </td>
                                <td class="text-align-center py-4 px-5 td-border-right">
                                    <template v-if="!this.create_order">
                                        <select class="form-control text-dark select-custom select-filter bg-white" :name="'select_expiration'" :id="'select_expiration'" v-model="proposals.bill_obj.array_bills[index].select_expiration" data-style="select-lightgreen">
                                            <option v-for="(item, index) in Number(this.select_expiration_options.length)" :key="index" :value="this.select_expiration_options[index].value">{{ this.select_expiration_options[index].text }}</option>
                                        </select>
                                    </template>
                                    <template v-else>
                                        <select v-if="this.date_now < this.proposal_submission_settings.date_proyect" class="form-control text-dark select-custom select-filter bg-white" :name="'select_expiration'" :id="'select_expiration'" v-model="proposals.bill_obj.array_bills[index].select_expiration" data-style="select-lightgreen">
                                            <option v-for="(item, index) in Number(this.select_expiration_options.length)" :key="index" :value="this.select_expiration_options[index].value">{{ this.select_expiration_options[index].text }}</option>
                                        </select>
                                        <span v-else>{{ this.select_expiration_options[proposals.bill_obj.array_bills[index].select_expiration].text }}</span>
                                    </template>
                                </td>
                                <td class="text-align-center td-border-right">
                                    {{ $utils.roundAndFix(proposals.bill_obj.array_bills[index].amount) }}
                                </td>
                                <td v-if="!this.create_order" class="td-border-right text-align-center">
                                    <button type="button" class="btn"><img width="40" height="40" src="/media/custom-imgs/icono_tabla_aplicar_todos.svg" @click.native="changeOptions(index)" /></button>
                                </td>
                            </tr>   
                            <tr class="row-article">
                                <td v-if="!this.create_order" class="p-5" colspan="6">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Observaciones</span>
                                        <input type="text" class="form-control bg-gray my-auto select-filter text-dark-gray col-10" v-model="proposals.bill_obj.array_bills[index].observations" placeholder="Observaciones" />
                                    </div>
                                </td>
                                <td v-else class="p-5" colspan="5">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Observaciones</span>
                                        <input v-if="this.date_now < this.proposal_submission_settings.date_proyect" type="text" class="form-control bg-gray my-auto select-filter text-dark-gray col-10" v-model="proposals.bill_obj.array_bills[index].observations" placeholder="Observaciones" />
                                        <span class="my-auto col-10" v-else>{{ proposals.bill_obj.array_bills[index].observations }}</span>
                                    </div>
                                </td>
                            </tr>      
                            <tr class="row-article">
                                <td v-if="!this.create_order" class="p-5" colspan="6">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Núm. pedido</span>
                                        <input type="text" class="form-control bg-gray my-auto select-filter text-dark-gray col-10" v-model="proposals.bill_obj.array_bills[index].order_number" placeholder="Número de pedido" />
                                    </div>
                                </td>
                                <td v-else class="p-5" colspan="5">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Núm. pedido</span>
                                        <input v-if="this.date_now < this.proposal_submission_settings.date_proyect" type="text" class="form-control bg-gray my-auto select-filter text-dark-gray col-10" v-model="proposals.bill_obj.array_bills[index].order_number" placeholder="Número de pedido" />
                                        <span class="my-auto col-10" v-else>{{ proposals.bill_obj.array_bills[index].order_number }}</span>
                                    </div>
                                </td>
                            </tr>    
                            <tr class="row-article">
                                <td v-if="!this.create_order"  class="p-5" colspan="6">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Observaciones Internas</span>
                                        <input type="text" class="form-control bg-gray my-auto select-filter text-dark-gray col-10" v-model="proposals.bill_obj.array_bills[index].internal_observations" placeholder="Observaciones Internas" />
                                    </div>
                                </td>
                                <td v-else class="p-5" colspan="5">
                                    <div class="d-flex">
                                        <span class="my-auto col-2">Observaciones Internas</span>
                                        <input v-if="this.date_now < this.proposal_submission_settings.date_proyect" type="text" class="form-control bg-gray my-auto select-filter text-dark-gray col-10" v-model="proposals.bill_obj.array_bills[index].internal_observations" placeholder="Observaciones Internas" />
                                        <span class="my-auto col-10" v-else>{{ proposals.bill_obj.array_bills[index].internal_observations }}</span>
                                    </div>
                                </td>
                            </tr>   
                        </template>       
                        <tr class="tr-total-datatable">
                            <td colspan="4" class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                            <td class="text-align-right"><span class="font-weight-bolder mr-7">{{ $utils.roundAndFix(proposals.bill_obj.total_bill) }}€</span></td>
                            <td v-if="!this.create_order"  class="text-align-center bg-white"><span class="font-weight-bolder"><button type="button" class="btn"><img  width="40" height="40" src="/media/custom-imgs/icono_tabla_eliminar.svg" @click.native="this.is_show_buttons_bill=false"/></button></span></td>
                        </tr>    
                    </tbody>
                </table>
                <div class="mt-10" v-if="!this.create_order">
                    <button @click.native="this.finishProposal()" type="button" class="btn bg-azul color-white px-30 font-weight-bolder">Finalizar propuesta</button>
                </div>
                <div class="mt-10" v-else>
                    <button @click.native="this.createOrderBtn()" type="button" class="btn bg-azul color-white px-30 font-weight-bolder">Crear orden</button>
                </div>
            </div>
            <div class="col-12 pl-0 mt-10" v-if="proposals.proposal_obj.chapters[0].chapter_obj != null && this.is_show_buttons_bill && this.finish_proposal && !this.generate_proposal">
                <h3 class="color-blue">Configuración de la presentación de la propuesta</h3>
                <div class="col-6 pl-0 mt-6">
                    <div class="d-flex input-group mb-5 mt-10" >
                        <span class="my-auto w-25">Nombre comercial</span>
                        <div class="w-62">
                            <input v-model="proposal_submission_settings.commercial_name" type="text" class="form-control borders-box text-dark-gray" placeholder="" />
                        </div>
                    </div>
                </div>
                <div class="col-6 pl-0 mt-6">
                    <div class="d-flex input-group my-5" >
                        <span class="my-auto w-25">Idioma</span>
                        <div class="w-62">
                            <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_language'" :id="'select_language'" v-model="proposal_submission_settings.language" data-style="select-lightgreen">
                                <option value="1" selected>Español</option>
                                <option value="2">Inglés</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6 pl-0 mt-6">
                    <div class="d-flex input-group my-5" >
                        <span class="my-auto w-25">Tipo de proyecto</span>
                        <div class="w-62">
                            <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_type_proyect'" :id="'select_type_proyect'" v-model="proposal_submission_settings.type_proyect" data-style="select-lightgreen">
                                <option value="1" selected>Propuesta</option>
                                <option value="2">Propuesta de Plan de comunicación</option>
                                <option value="3">Propuesta de Plan de comunicación digital</option>
                                <option value="4">Propuesta de email marketing</option>
                                <option value="5">Propuesta de participación en evento</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6 pl-0 mt-6">
                    <div class="d-flex input-group my-5" >
                        <span class="my-auto w-25">Nombre de proyecto</span>
                        <div class="w-62">
                            <input v-model="proposal_submission_settings.name_proyect" type="text" class="form-control borders-box text-dark-gray" placeholder="" />
                        </div>
                    </div>
                </div>
                <div class="col-6 pl-0 mt-6">
                    <div class="d-flex input-group my-5" >
                        <span class="my-auto w-25">Fecha de proyecto</span>
                        <div class="w-62">
                            <Calendar v-model="proposal_submission_settings.date_proyect" class="w-100 borders-box text-dark-gray mt-1"  autocomplete="off"  dateFormat="dd-mm-yy"  />
                        </div>
                    </div>
                </div>
                <div class="col-6 pl-0 mt-6">
                    <div class="d-flex input-group my-5" >
                        <span class="mb-auto w-25">Objetivos</span>
                        <div class="w-50">
                            <Textarea v-model="proposal_submission_settings.objetives" :autoResize="true" class="borders-box text-dark-gray" rows="5" cols="56" />
                        </div>
                    </div>
                </div>
                <div class="col-6 pl-0 mt-6">
                    <div class="d-flex input-group my-5" >
                        <span class="mb-auto w-25">Propuesta</span>
                        <div class="w-50">
                            <Textarea v-model="proposal_submission_settings.proposal" :autoResize="true" class="borders-box text-dark-gray" rows="5" cols="56" />
                        </div>
                    </div>
                </div>
                <div class="col-6 pl-0 mt-6">
                    <div class="d-flex input-group my-5" >
                        <span class="mb-auto w-25">Acciones</span>
                        <div class="w-50">
                            <Textarea v-model="proposal_submission_settings.actions" :autoResize="true" class="borders-box text-dark-gray" rows="5" cols="56" />
                        </div>
                    </div>
                </div>
                <div class="col-6 pl-0 mt-6">
                    <div class="d-flex input-group my-5" >
                        <span class="mb-auto w-25">Observaciones</span>
                        <div class="w-50">
                            <Textarea v-model="proposal_submission_settings.observations" :autoResize="true" class="borders-box text-dark-gray" rows="5" cols="56" />
                            <div style="display: -webkit-box;" class="mt-6">
                                <div v-if="this.proposal_submission_settings.show_discounts == 0">
                                    <button class="purple-border btn mr-4 font-weight-bold d-flex py-4" @click="this.changeStatusShowDiscounts(1)">
                                        <div class="purple-circle mr-auto">
                                            <div class="white-circle-purple"></div>
                                        </div>
                                        <span class="px-10">Mostrar descuentos</span>
                                    </button>
                                </div>
                                <div v-else>
                                    <button class="bg-purple btn mr-4 font-weight-bold color-white d-flex py-4" @click="this.changeStatusShowDiscounts(0)">
                                        <div class="white-circle mr-auto">
                                            <div class="purple-circle-white"></div>
                                        </div>
                                        <span class="px-10">Mostrar descuentos</span>
                                    </button>
                                </div>
                                <div v-if="this.proposal_submission_settings.show_inserts == 0">
                                    <button class="purple-border btn mr-4 font-weight-bold d-flex py-4" @click="this.changeStatusShowInserts(1)">
                                        <div class="purple-circle mr-auto">
                                            <div class="white-circle-purple"></div>
                                        </div>
                                        <span class="px-10">Mostrar inserciones como X</span>
                                    </button>
                                </div>
                                <div v-else>
                                    <button  class="bg-purple btn mr-4 font-weight-bold color-white d-flex py-4" @click="this.changeStatusShowInserts(0)">
                                        <div class="white-circle mr-auto">
                                            <div class="purple-circle-white"></div>
                                        </div>
                                        <span class="px-10">Mostrar inserciones como X</span>
                                    </button>
                                </div>
                                <div v-if="this.proposal_submission_settings.show_invoices == 0">
                                    <button class="purple-border btn mr-4 font-weight-bold d-flex py-4" @click="this.changeStatusShowInvoices(1)">
                                        <div class="purple-circle mr-auto">
                                            <div class="white-circle-purple"></div>
                                        </div>
                                        <span class="px-10">Mostrar facturas</span>
                                    </button>
                                </div>
                                <div v-else>
                                    <button  class="bg-purple btn mr-4 font-weight-bold color-white d-flex py-4" @click="this.changeStatusShowInvoices(0)">
                                        <div class="white-circle mr-auto">
                                            <div class="purple-circle-white"></div>
                                        </div>
                                        <span class="px-10">Mostrar facturas</span>
                                    </button>
                                </div>
                                <div v-if="this.proposal_submission_settings.show_pvp == 0">
                                    <button class="purple-border btn mr-4 font-weight-bold d-flex py-4" @click="this.changeStatusShowPvp(1)">
                                        <div class="purple-circle mr-auto">
                                            <div class="white-circle-purple"></div>
                                        </div>
                                        <span class="px-10">Mostrar PVP</span>
                                    </button>
                                </div>
                                <div v-else>
                                    <button  class="bg-purple btn mr-4 font-weight-bold color-white d-flex py-4" @click="this.changeStatusShowPvp(0)">
                                        <div class="white-circle mr-auto">
                                            <div class="purple-circle-white"></div>
                                        </div>
                                        <span class="px-10">Mostrar PVP</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 pl-0 mt-6">
                    <div class="d-flex input-group my-5" >
                        <span class="my-auto w-25">Posibilidades de venta</span>
                        <div class="w-62">
                            <select class="form-control w-100 bg-gray text-dark-gray select-custom" :name="'select_sales_possibilities'" :id="'select_sales_possibilities'" v-model="proposal_submission_settings.sales_possibilities" data-style="select-lightgreen">
                                <option value="1">100% - Venta ganada</option>
                                <option value="2">90% - Aprobado a falta de firma</option>
                                <option value="3">60% - Pinta bien</option>
                                <option value="4">30% - Dudoso</option>
                                <option value="5">10% - Muy complicado</option>
                                <option value="6">0% - Venta perdida</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-10">
                    <button @click.native="this.generateProposal()" type="button" class="btn bg-azul color-white px-30 font-weight-bolder">Generar propuesta</button>
                </div>
            </div>
            <div class="col-12 pl-0 mt-10" v-if="proposals.proposal_obj.chapters[0].chapter_obj != null && this.is_show_buttons_bill && this.finish_proposal && this.generate_proposal">
                <h3 v-if="this.is_change_get_info == 0" class="color-blue"></h3>
                <div class="mt-7">
                    <template v-if="this.is_change_get_info == 0">
                        <div class="d-grid my-4">
                            <span class="f-14 color-blue font-weight-bold">NOMBRE COMERCIAL</span>
                            <span class="mt-1 text-block">{{ this.proposal_submission_settings.commercial_name }}</span>
                        </div>
                        <div class="d-grid my-4" v-if="this.proposal_submission_settings.name_proyect != ''">
                            <span class="f-14 color-blue font-weight-bold">TÍTULO DEL PROYECTO</span>
                            <span class="mt-1 text-block">{{ this.proposal_submission_settings.name_proyect }}</span>
                        </div>
                        <div class="d-grid my-4">
                            <span class="f-14 color-blue font-weight-bold">TIPO DE PROYECTO</span>
                            <span class="mt-1 text-block">{{ this.proposal_submission_settings.type_proyect }}</span>
                        </div>
                        <div class="d-grid my-4">
                            <span class="f-14 color-blue font-weight-bold">FECHA</span>
                            <span class="mt-1 text-block">{{ this.proposal_submission_settings.date_proyect }}</span>
                        </div>
                        <div class="d-grid my-4">
                            <span class="f-14 color-blue font-weight-bold">OBJETIVOS</span>
                            <span class="mt-1 text-block">{{ this.proposal_submission_settings.objetives }}</span>
                        </div>
                        <div class="d-grid my-4">
                            <span class="f-14 color-blue font-weight-bold">PROPUESTA</span>
                            <span class="mt-1 text-block">{{ this.proposal_submission_settings.proposal }}</span>
                        </div>
                        <div class="d-grid my-4">
                            <span class="f-14 color-blue font-weight-bold">ACCIONES</span>
                            <span class="mt-1 text-block">{{ this.proposal_submission_settings.actions }}</span>
                        </div>
                    </template>
                    <div class="d-grid mt-15 mb-4">
                        <table width="100%" cellpadding="2" cellspacing="1">
                            <tbody>
                                <tr class="row-product-offer-proposal">
                                    <template v-if="this.proposal_submission_settings.show_discounts == 1">
                                        <td colspan="5" class="f-15 py-2"><span class="ml-5 gray-product-offer-proposal font-weight-bold"><b class="text-dark">Cliente: </b>{{ this.name_company }}</span></td>
                                    </template>
                                    <template v-else>
                                        <td colspan="4" class="f-15 py-2"><span class="ml-5 gray-product-offer-proposal font-weight-bold"><b class="text-dark">Cliente: </b>{{ this.name_company }}</span></td>
                                    </template>
                                    <td colspan="2" class="py-2 td-border-left text-align-center"><span v-if="proposals.proposal_bd_obj != null" class="gray-product-offer-proposal font-weight-bolder">PROPUESTA Nº: </span><span v-if="proposals.proposal_bd_obj != null" class="text-dark"> {{ proposals.proposal_bd_obj.id_proposal_custom_aux }}</span></td>
                                </tr>
                                <tr class="row-product-offer-proposal">
                                    <td class="py-2"><div class="f-13 ml-5 font-weight-bolder gray-product-offer-proposal">FECHA:</div><div v-if="!this.create_order && !this.is_change_get_info" class="ml-5 f-13 text-dark">{{ this.$utils.getNow() }}</div><div v-else class="ml-5 f-13 text-dark">{{ this.proposal_submission_settings.date_proyect }}</div></td>
                                    <td class="py-2 td-border-left"><div class="f-13 ml-5 font-weight-bolder gray-product-offer-proposal">CONSULTOR:</div><div class="ml-5 f-13 text-dark">{{ proposals.user_obj.name + ' ' + proposals.user_obj.surname }}</div></td>
                                    <td class="py-2 td-border-left"><div class="f-13 ml-5 font-weight-bolder gray-product-offer-proposal">DEPARTAMENTO:</div><div class="ml-5 f-13 text-dark">{{ proposals.proposal_obj.chapters[0].articles[0].department_obj.name }}</div></td>
                                    <td class="py-2 td-border-left"><div class="f-13 ml-5 font-weight-bolder gray-product-offer-proposal">ANUNCIANTE:</div><div class="ml-5 f-13 text-dark">{{ this.name_company }}</div></td>
                                    <td class="py-2 td-border-left" v-if="this.proposal_submission_settings.show_discounts == 1"><div class="f-13 ml-5 font-weight-bolder gray-product-offer-proposal">DESCUENTO:</div><div class="ml-5 f-13 text-dark">{{ this.proposal_submission_settings.discount }}%</div></td>
                                    <td class="py-2 td-border-left bg-blue-light-white"><div class="f-13 ml-5 font-weight-bolder color-blue">OFERTA:</div><div class="ml-5 f-13 text-dark">{{ $utils.numberWithDotAndComma($utils.roundAndFix(this.offer)) }}€</div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-grid mb-4 mt-15">
                        <span class="f-14 color-blue font-weight-bold">PROPUESTA</span>
                        <table width="100%" cellpadding="2" cellspacing="1">
                            <thead class="custom-columns-datatable">
                                <tr>
                                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>SERVICIOS</span></th>
                                    <th v-if="this.proposal_submission_settings.show_pvp == 1" tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>PVP</span></th>
                                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>N</span></th>
                                    <th tabindex="0" class="pb-3 text-align-center" v-for="index in Number(proposals.proposal_obj.array_dates.length)" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>{{ proposals.proposal_obj.array_dates[index - 1].date }}</span></th>
                                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>TOTAL</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <div class="d-contents" v-for="index in Number(proposals.proposal_obj.chapters.length)">
                                    <tr class="row-product">
                                        <td class="py-2" :colspan="proposals.proposal_obj.array_dates.length + 4">
                                            <span class="ml-5">{{ proposals.proposal_obj.chapters[index - 1].chapter_obj.name }}</span>
                                        </td>
                                    </tr>
                                    <tr class="row-article" v-for="index_article in Number(proposals.proposal_obj.chapters[index - 1].articles.length)">
                                        <td valign="middle" class="td-border-right py-5"><span class="ml-5">{{ proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].article_obj.name }}</span></td>
                                        <td v-if="this.proposal_submission_settings.show_pvp == 1" valign="middle" class="td-border-right text-align-center py-5"><span class="">{{ $utils.numberWithDotAndComma($utils.roundAndFix(proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].article_obj.pvp)) }}€</span></td>
                                        <td valign="middle" class="td-border-right text-align-center py-5"><span class="">{{ proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].amount }}</span></td>
                                        <td v-for="index_arr_date in Number(proposals.proposal_obj.array_dates.length)" valign="middle" class="td-border-right py-5">
                                            <template v-for="index_dates in Number(proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices.length)">
                                                <template v-if="proposals.proposal_obj.array_dates[index_arr_date - 1].date == proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].date">
                                                    <div class="d-grid px-5">
                                                        <template v-for="index_pvp_date in Number(proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date.length)">
                                                            <img v-if="this.proposal_submission_settings.show_inserts == 1" v-for="index_pvp in Number(proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date[index_pvp_date - 1].arr_pvp.length)" class="mx-auto my-2" width="8" src="/media/custom-imgs/circle.png" />
                                                            <template v-else v-for="index_pvp in Number(proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].dates_prices[index_dates - 1].arr_pvp_date[index_pvp_date - 1].arr_pvp.length)"><span class="mx-auto">{{ this.value_form1[index - 1].article[index_article - 1].dates[index_dates - 1].date_pvp[index_pvp_date - 1].pvp[index_pvp - 1] }}€</span></template>
                                                        </template>
                                                    </div>
                                                </template>
                                            </template>
                                        </td>
                                        <td valign="middle" class="td-border-right text-align-center py-5">
                                            <template v-if="this.proposal_submission_settings.show_pvp == 1">
                                                <template v-if="this.discount != 0">
                                                    <span class="">{{ this.value_form1[index - 1].article[index_article - 1].total_aux }}€</span>
                                                </template>
                                                <template v-else>
                                                    <span class="">{{ $utils.numberWithDotAndComma($utils.roundAndFix(proposals.proposal_obj.chapters[index - 1].articles[index_article - 1].total)) }}€</span>
                                                </template>
                                            </template>
                                            <template v-else >
                                                <span class="">-</span>
                                            </template>
                                        </td>
                                    </tr>
                                </div>
                                <tr class="tr-total-datatable">
                                    <td class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                                    <td class="text-align-center" v-if="this.proposal_submission_settings.show_pvp == 1"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(proposals.proposal_obj.total_individual_pvp)) }}€</span></td>
                                    <td class="text-align-center"><span class="font-weight-bolder">{{ proposals.proposal_obj.total_amount_global }}</span></td>
                                    <template v-if="this.discount != 0">
                                        <td class="text-align-center" v-for="index in Number(proposals.proposal_obj.array_dates.length)">
                                            <span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(this.value_form_discount[index - 1].pvp)) }}€</span>
                                        </td>
                                        <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(this.offer)) }}€</span></td>
                                    </template>
                                    <template v-else>
                                        <td class="text-align-center" v-for="index in Number(proposals.proposal_obj.array_dates.length)"><span class="font-weight-bolder" v-if="this.proposal_submission_settings.show_pvp == 1">{{ $utils.numberWithDotAndComma($utils.roundAndFix(proposals.proposal_obj.array_dates[index - 1].total)) }}€</span></td>
                                        <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.numberWithDotAndComma($utils.roundAndFix(proposals.proposal_obj.total_global)) }}€</span></td>
                                    </template>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <template v-if="this.proposal_submission_settings.show_invoices == 1">
                        <div class="d-grid mb-4 mt-15">
                            <span class="f-14 color-blue font-weight-bold">PLAN DE PAGO</span>
                        </div>
                        <table width="100%" cellpadding="2" cellspacing="1">
                            <thead class="custom-columns-datatable">
                                <tr>
                                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="4" colspan="1" style="width: 50px;"><span>FACTURAS</span></th>
                                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FECHA</span></th>
                                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>FORMA DE PAGO</span></th>
                                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;"><span>VENCIMIENTO</span></th>
                                    <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;"><span>IMPORTE</span></th>
                                </tr>
                            </thead>
                            <tbody>  
                                <template v-for="(item, index) in Number(proposals.bill_obj.array_bills.length)" :key="index">
                                    <tr class="row-product text-align-center bg-white">
                                        <td class="td-border-right" :rowspan="countRows(proposals.bill_obj.array_bills[index])">{{ index + 1 }}</td>
                                    </tr>
                                    <tr class="row-product bg-white">
                                        <td class="text-align-center td-border-right">{{ proposals.bill_obj.array_bills[index].date }}</td>
                                        <td class="text-align-center py-4 px-5 td-border-right" width="20%">
                                            {{ this.select_way_to_pay_options[proposals.bill_obj.array_bills[index].select_way_to_pay].text }}
                                        </td>
                                        <td class="text-align-center py-4 px-5 td-border-right">
                                            {{ this.select_expiration_options[proposals.bill_obj.array_bills[index].select_expiration].text }}
                                        </td>
                                        <td class="text-align-center">
                                            {{ $utils.roundAndFix(proposals.bill_obj.array_bills[index].amount) }}
                                        </td>
                                    </tr>   
                                    <tr class="row-article" v-if="proposals.bill_obj.array_bills[index].observations != ''">
                                        <td class="p-5" colspan="5">
                                            <div class="d-flex">
                                                <span class="my-auto col-12">Observaciones: {{ proposals.bill_obj.array_bills[index].observations }}</span>
                                            </div>
                                        </td>
                                    </tr>      
                                    <tr class="row-article" v-if="proposals.bill_obj.array_bills[index].order_number != ''">
                                        <td class="p-5" colspan="5">
                                            <div class="d-flex">
                                                <span class="my-auto col-12">Núm. pedido: {{ proposals.bill_obj.array_bills[index].order_number }}</span>
                                            </div>
                                        </td>
                                    </tr>    
                                    <tr class="row-article" v-if="proposals.bill_obj.array_bills[index].internal_observations != ''">
                                        <td class="p-5" colspan="5">
                                            <div class="d-flex">
                                                <span class="my-auto col-12">Observaciones Internas: {{ proposals.bill_obj.array_bills[index].internal_observations }}</span>
                                            </div>
                                        </td>
                                    </tr>   
                                </template>       
                                <tr class="tr-total-datatable">
                                    <td colspan="4" class="py-6"><span class="ml-5 font-weight-bolder">TOTAL</span></td>
                                    <td class="text-align-center"><span class="font-weight-bolder">{{ $utils.roundAndFix(proposals.bill_obj.total_bill) }}€</span></td>
                                </tr>    
                            </tbody>
                        </table>
                    </template>
                    <div class="mt-10" v-if="(this.is_updating == 1 && this.proposals.proposal_bd_obj != null) || (this.generate_proposal && this.proposals.proposal_bd_obj == null)">
                        <button v-on:click="this.generatePdf()" type="button" class="btn bg-azul color-white px-30 font-weight-bolder">Guardar y generar PDF</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <FormAddArticleComponent :type="1"></FormAddArticleComponent>
    <ModalCustomInvoiceComponent></ModalCustomInvoiceComponent>
</template>

<script>

import { mapMutations, mapState, mapActions } from "vuex";

import AddButtonComponent from "../Partials/AddButtonComponent.vue";
import DeleteButtonComponent from "../Partials/DeleteButtonComponent.vue"
import FormAddArticleComponent from "./FormAddArticleComponent.vue";
import ModalCustomInvoiceComponent from "./ModalCustomInvoiceComponent.vue";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Textarea from 'primevue/textarea';
import Calendar from 'primevue/calendar';
import { throwStatement } from "@babel/types";

export default {
    name: "FormComponent",
    components: {
        AddButtonComponent,
        FormAddArticleComponent,
        DataTable,
        Column,
        Textarea,
        Calendar,
        ModalCustomInvoiceComponent,
        DeleteButtonComponent
    },
    data() {
        return {
            publicPath: window.location.origin,
            select_company: '',
            select_company_other_values: '',
            name_company: '',
            id_company: 0,
            offer: 0,
            total: 0,
            discount: '0.00',
            fullname: '',
            select_type_proposal: '1',
            value_form1: [], //Formulario presupuesto,
            value_form_discount: [],
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
            finish_proposal: false,
            generate_proposal: false,
            proposal_submission_settings: {
                commercial_name: '',
                language: '1',
                type_proyect: '1',
                name_proyect: '',
                date_proyect: '',
                objetives: '',
                proposal: 'Hemos creado una propuesta con diferentes acciones de demostrada eficacia. Es una comunicación 360 grados, de fuerte impacto, de gran repercusión y de calidad, que convencerá a nuestra audiencia de la necesidad de utilizar los productos y servicios de su empresa.\n\nEsta propuesta incluye la inmediatez e impacto directo de las nuevas tecnologías de movilidad, la efectividad del branded content, la utilización selectiva de nuestras bases de datos y el posicionamiento estratégico y de marca de los formatos publicitarios.',
                actions: 'Acciones Print: Acciones de marketing de contenido para conseguir credibilidad de marca, acciones de publicidad corporativa bien posicionadas para reforzar la relevancia, el posicionamiento estratégico y la diferenciación con la competencia.\n\nAcciones digitales: Acciones de gran impacto, como email marketing, banner y contenidos en web y newsletter, buscando la acción directa sobre la audiencia y la efectividad e inmediatez en el resultado. Además, estas acciones se potenciarán a través de nuestras redes sociales.\n\nAcciones Experiencias: Centradas en el patrocinio de un desayuno y en la participación en un evento de referencia sectorial, buscando la relación directa y personal con el cliente para la obtención de leads.',
                observations: '',
                show_discounts: 0,
                show_inserts: 1,
                show_invoices: 1,
                show_pvp: 1,
                sales_possibilities: '6',
                discount: 0
            },
            is_change_get_info: 0,
            is_updating: false,
            create_order: false,
            date_now: '',
            is_copy: false
        };
    },
    computed: {
        ...mapState(["errors", "proposals"]),
    },
    methods: {
        ...mapMutations(["clearError", "changeViewStatusProposals", "changeProposalObj", "changeValueIsChangeArticle", "generateBill", "clearObjectsProposal"]),
        ...mapActions(["getCompanies", "saveProposal", "updateProposal", "deleteProposal", "createOrder"]),
        openFormArticle(){
            $('#modal_form_article_proposals').modal('show');
        },
        openCustomInvoice(){
            $('#modal_custom_invoice').modal('show');
        },
        getNameCompany(id){
            let me = this;
            me.proposals.array_companies.forEach(function callback(value, index, array) {
                if(value.id == id){
                    me.name_company = value.name;
                    me.id_company = value.id;
                }
            });
            me.proposal_submission_settings.objetives = 'Somos consultores y expertos en comunicación. Nuestra marca y nuestros servicios son líderes en el sector, y tienen el máximo reconocimiento, prestigio e influencia. Sabemos qué quiere nuestra audiencia, lo que nos permite ofrecer a '+ me.name_company +' una propuesta de valor única, diferencial y de éxito.\n\nHemos estudiado el potencial y la proyección de '+ me.name_company +' con el fin de crear una propuesta de comunicación eficaz que permita mejorar sus resultados y objetivos.\n\nLas acciones de comunicación para '+ me.name_company +' que incluimos en esta propuesta crean influencia y potencian la visibilidad y la relevancia de sus productos, impactando positivamente en nuestra audiencia e incitando a la acción.';

        },
        changeValueBox(type, status){
            this.is_show_buttons_bill = false;
            if(type == 1){
                var difference = this.proposals.proposal_obj.total_global_normal - this.offer;
                this.discount = this.$utils.roundAndFix(difference / (this.proposals.proposal_obj.total_global_normal) * 100);

            }else{
                var difference = ((100 - this.discount) / 100) * this.proposals.proposal_obj.total_global_normal;
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
                form: this.value_form1
            }
            
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
            me.value_form1.map(function(product, key_product) {
                product.article.map(function(article_obj, key_article) {
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
                form: me.value_form1
            }
            this.changeProposalObj(params);
            me.is_show_buttons_bill = false;
        },
        createBills(){
            this.is_show_buttons_bill = true;
            var params = {
                form: this.value_form1,
                num_custom_invoices: this.proposals.num_custom_invoices,
                type: 1
            }
            this.generateBill(params);
        },
        loadFormObj(){
            let me = this;
            console.log(me.proposals.proposal_obj.is_change);
            me.value_form1 = [];
                
            //Rellenar los modelos de los inputs de la tabla
            me.proposals.proposal_obj.chapters.map(function(chapter, key_chapter) {
                chapter.articles.map(function(article, key_article) {
                    me.proposals.proposal_obj.array_dates.map(function(date_obj, key_arr_dates) {
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
                                                            pvp_default: []
                                                        }]
                                                    }]
                                                }]
                                            });
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp_default.push(article.article_obj.pvp);

                                        }else if(me.value_form1[key_chapter].article[key_article] == undefined){
                                            me.value_form1[key_chapter].article.push({
                                                dates:[{
                                                    article: article.article_obj,
                                                    date_pvp: [{
                                                        date: pvp_date.date,
                                                        pvp: [],
                                                        pvp_default: []
                                                    }]
                                                }]
                                            });
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp_default.push(article.article_obj.pvp);

                                        }else if(me.value_form1[key_chapter].article[key_article].dates[key_dates] == undefined){
                                            me.value_form1[key_chapter].article[key_article].dates.push({
                                                article: article.article_obj,
                                                date_pvp: [{
                                                    date: pvp_date.date,
                                                    pvp: [],
                                                    pvp_default: []
                                                }]
                                            });
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp_default.push(article.article_obj.pvp);

                                        }else if(me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date] == undefined){
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp.push({
                                                date: pvp_date.date,
                                                pvp: []
                                            });

                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp_default.push(article.article_obj.pvp);

                                        }else{
                                            me.value_form1[key_chapter].article[key_article].dates[key_dates].date_pvp[key_pvp_date].pvp.push(pvp);
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
            for(var i=index; i<this.proposals.bill_obj.array_bills.length; i++){
                this.proposals.bill_obj.array_bills[i].select_way_to_pay = this.proposals.bill_obj.array_bills[index].select_way_to_pay;
                this.proposals.bill_obj.array_bills[i].select_expiration = this.proposals.bill_obj.array_bills[index].select_expiration;
                this.proposals.bill_obj.array_bills[i].observations = this.proposals.bill_obj.array_bills[index].observations;
                this.proposals.bill_obj.array_bills[i].order_number = this.proposals.bill_obj.array_bills[index].order_number;
                this.proposals.bill_obj.array_bills[i].internal_observations = this.proposals.bill_obj.array_bills[index].internal_observations;
            }
        },
        finishProposal(){
            let me = this;
            var is_empty = false;
            me.proposals.bill_obj.array_bills.map(function(bill, key) {
                if(bill.select_expiration == '' || bill.select_way_to_pay == ''){
                    is_empty = true;
                }       

                if(typeof bill.date.getMonth === 'function'){
                    if(bill.date.toISOString().includes('000Z')){
                        bill.date = me.$utils.customFormDate(bill.date);
                    }
                }
            });
            if(!is_empty){
                me.proposal_submission_settings.commercial_name = me.name_company;
                me.proposal_submission_settings.date_proyect = me.$utils.getNow();
                me.proposal_submission_settings.discount = me.discount;
                me.proposals.status_view = 2;
                me.finish_proposal = true;
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
        changeStatusShowDiscounts(status){
            this.proposal_submission_settings.show_discounts = status;
        },
        changeStatusShowInserts(status){
            this.proposal_submission_settings.show_inserts = status;
        },
        changeStatusShowInvoices(status){
            this.proposal_submission_settings.show_invoices = status;
        },
        changeStatusShowPvp(status){
            this.proposal_submission_settings.show_pvp = status;
        },
        generateProposal(){
            if(typeof this.proposal_submission_settings.date_proyect.getMonth === 'function'){
                if(this.proposal_submission_settings.date_proyect.toISOString().includes('000Z')){
                    this.proposal_submission_settings.date_proyect = this.$utils.customFormDate(this.proposal_submission_settings.date_proyect);
                }
            }
            this.generate_proposal = true;
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
        generatePdf(){
            var params = {
                proposal_submission_settings: this.proposal_submission_settings,
                id_company: this.id_company,
                bill_obj: this.proposals.bill_obj,
                id_department: this.proposals.proposal_obj.chapters[0].articles[0].department_obj.id,
                proposal_obj: this.proposals.proposal_obj,
                value_form1: this.value_form1,
                select_way_to_pay_options: this.select_way_to_pay_options,
                select_expiration_options: this.select_expiration_options
            }
            if(this.proposals.status_view == 2 && this.proposals.proposal_bd_obj != null){
                params.id_proposal = this.proposals.proposal_bd_obj.id;
                this.updateProposal(params);
            }else{
                this.saveProposal(params);
            }
        },
        //Limpiar el data del component
        clearData(){
            let me = this;
            me.clearObjectsProposal();
            me.select_company = '';
            me.select_company_other_values = '';
            me.name_company = '';
            me.id_company = 0;
            me.offer = 0;
            me.total = 0;
            me.discount = '0.00';
            me.fullname = '';
            me.select_type_proposal = '1';
            me.is_show_buttons_bill = false;
            me.finish_proposal = false;
            me.generate_proposal = false;
            me.proposal_submission_settings.commercial_name = '';
            me.proposal_submission_settings.language = '1';
            me.proposal_submission_settings.type_proyect = '1';
            me.proposal_submission_settings.name_proyect = '';
            me.proposal_submission_settings.date_proyect = '';
            me.proposal_submission_settings.objetives = '';
            me.proposal_submission_settings.proposal = 'Hemos creado una propuesta con diferentes acciones de demostrada eficacia. Es una comunicación 360 grados, de fuerte impacto, de gran repercusión y de calidad, que convencerá a nuestra audiencia de la necesidad de utilizar los productos y servicios de su empresa.\n\nEsta propuesta incluye la inmediatez e impacto directo de las nuevas tecnologías de movilidad, la efectividad del branded content, la utilización selectiva de nuestras bases de datos y el posicionamiento estratégico y de marca de los formatos publicitarios.';
            me.proposal_submission_settings.actions = 'Acciones Print: Acciones de marketing de contenido para conseguir credibilidad de marca, acciones de publicidad corporativa bien posicionadas para reforzar la relevancia, el posicionamiento estratégico y la diferenciación con la competencia.\n\nAcciones digitales: Acciones de gran impacto, como email marketing, banner y contenidos en web y newsletter, buscando la acción directa sobre la audiencia y la efectividad e inmediatez en el resultado. Además, estas acciones se potenciarán a través de nuestras redes sociales.\n\nAcciones Experiencias: Centradas en el patrocinio de un desayuno y en la participación en un evento de referencia sectorial, buscando la relación directa y personal con el cliente para la obtención de leads.';
            me.proposal_submission_settings.observations = '';
            me.proposal_submission_settings.show_discounts = 0;
            me.proposal_submission_settings.show_inserts = 1;
            me.proposal_submission_settings.show_invoices = 1;
            me.proposal_submission_settings.show_pvp = 1;
            me.proposal_submission_settings.sales_possibilities = 6;
            me.proposal_submission_settings.discount = 0;
        },
        //Copiar propuesta
        copyOrderView(){
            this.generate_proposal = false;
            this.finish_proposal = false;
            this.is_copy = true;
            this.is_updating = false;
            this.create_order = false;
            this.proposals.bill_obj.array_bills = [];
            this.proposals.bill_obj.total_bill = 0;
            this.proposals.proposal_bd_obj = null;
            this.is_show_buttons_bill = false;
        },
        //Modificar propuesta
        updateProposalFront(){
            this.generate_proposal = false;
            this.finish_proposal = false;
            this.is_updating = true;
            this.create_order = false;
            this.is_copy = false;
        },
        //Eliminar propuesta
        deleteProposalAction(){
            let me = this;
            let id_proposal = this.proposals.proposal_bd_obj.id;
            swal({
                title: "¿Eliminar propuesta?",
                text: "¿Está seguro? Esta propuesta se eliminará",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Eliminar",
                cancelButtonText: "Cancelar",
                closeOnCancel: true,
                closeOnConfirm: false
            }, function(isConfirm) {
                if (isConfirm) {
                    me.deleteProposal(id_proposal);
                }
            });
        },
        //Cambio de vista para crear orden
        createOrderView(){
            this.generate_proposal = false;
            this.finish_proposal = false;
            this.is_updating = false;
            this.create_order = true;
            this.is_copy = false;
        },
        //Crear orden
        createOrderBtn(){
            var params = {
                'id_proposal': this.proposals.proposal_bd_obj.id
            }
            this.createOrder(params);
        },
        //Botón volver
        returnView(){
            this.changeViewStatusProposals(1);
            this.clearObjectsProposal();
        }
    },
    mounted() {
        if(this.proposals.proposal_obj.array_dates != undefined && this.offer == 0 && this.proposals.proposal_obj.array_dates.length > 0 && !this.proposals.is_change_get_info){
            this.changeViewStatusProposals(1);
            this.clearObjectsProposal();

        }else{

            this.date_now = this.$utils.getNow();
            this.clearError();
            let me = this;
            $('#select_company').select2({
                placeholder: "Selecciona una empresa",
                maximumInputLength: 20
            });
            $('#select_company_other_values').select2({
                placeholder: "Selecciona una empresa"
            });
            this.getCompanies(1);
            $('#select_company').on("change",function(){
                me.select_company = $('#select_company').val();
                me.getNameCompany(me.select_company);
            });
            $('#select_company_other_values').on("change",function(){
                me.select_company_other_values = $('#select_company_other_values').val();
                me.getNameCompany(me.select_company_other_values);
            });
        }
    },
    watch: {
        '$store.state.errors.code': function() {
            if(this.errors.type_error == 'save_proposal'){
                if(this.errors.code != ''){
                    if(this.errors.code == 1000){
                        $("#list_proposals").KTDatatable("reload");
                        this.proposals.status_view = 1;
                        this.clearData();
                        if(this.errors.msg != ''){
                            var url = this.errors.msg;
                            this.errors.msg = '';
                            window.open(url);
                        }
                        swal("", "Propuesta añadida correctamente", "success");
                    }else{
                        swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                    }
                }

            }else if(this.errors.type_error == 'update_proposal'){
                if(this.errors.code != ''){
                    if(this.errors.code == 1000){
                        $("#list_proposals").KTDatatable("reload");
                        this.proposals.status_view = 1;
                        this.clearData();
                        if(this.errors.msg != ''){
                            var url = this.errors.msg;
                            this.errors.msg = '';
                            window.open(url);
                        }
                        swal("", "Propuesta actualizada correctamente", "success");
                    }else{
                        swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                    }
                }
            }else if(this.errors.type_error == 'delete_proposal'){
                if(this.errors.code != ''){
                    if(this.errors.code == 1000){
                        $("#list_proposals").KTDatatable("reload");
                        this.proposals.status_view = 1;
                        this.clearData();
                        if(this.errors.msg != ''){
                            var url = this.errors.msg;
                            this.errors.msg = '';
                            window.open(url);
                        }
                        swal("", "Propuesta eliminada correctamente", "success");
                    }else{
                        swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                    }
                }

            }if(this.errors.type_error == 'create_order'){
                if(this.errors.code != ''){
                    if(this.errors.code == 1000){
                        $("#list_proposals").KTDatatable("reload");
                        this.proposals.status_view = 1;
                        this.clearData();
                        swal("", "Orden creada correctamente", "success");
                    }else if(this.errors.code == 1004){
                        swal("", "La empresa asociada a esta propuesta no tiene todos los datos completos", "warning");

                    }else{
                        swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                    }
                }

            }
            this.clearError();
        },
        '$store.state.proposals.proposal_obj.is_change': function() {
            let me = this;
            me.is_show_buttons_bill = false;
            if(me.proposals.proposal_obj.is_change){
                me.changeValueIsChangeArticle();
                me.offer = me.$utils.roundAndFix(me.proposals.proposal_obj.total_global);
                me.total = me.$utils.roundAndFix(me.proposals.proposal_obj.total_global);
            }
            me.loadFormObj();
        },
        '$store.state.proposals.num_custom_invoices': function() {
            let me = this;
            me.createBills();
        },
        '$store.state.proposals.array_companies': function() {
            if(this.proposals.is_change_get_info == 1){
                this.finish_proposal = true;
                this.generate_proposal = true;
                this.is_change_get_info = this.proposals.is_change_get_info;
                this.proposals.is_change_get_info = 0;
                this.id_company = this.proposals.id_company;
                this.select_company = this.id_company;
                this.getNameCompany(this.select_company);
                this.is_show_buttons_bill = true;
                this.proposal_submission_settings.commercial_name = this.proposals.proposal_bd_obj.commercial_name;
                this.proposal_submission_settings.language = this.proposals.proposal_bd_obj.language;
                this.proposal_submission_settings.type_proyect = this.proposals.proposal_bd_obj.type_proyect;
                this.proposal_submission_settings.name_proyect = this.proposals.proposal_bd_obj.name_proyect;
                this.proposal_submission_settings.date_proyect = this.proposals.proposal_bd_obj.date_proyect;
                this.proposal_submission_settings.objetives = this.proposals.proposal_bd_obj.objetives;
                this.proposal_submission_settings.proposal = this.proposals.proposal_bd_obj.proposal;
                this.proposal_submission_settings.actions = this.proposals.proposal_bd_obj.actions;
                this.proposal_submission_settings.observations = this.proposals.proposal_bd_obj.observations;
                this.proposal_submission_settings.show_discounts = this.proposals.proposal_bd_obj.show_discounts;
                this.proposal_submission_settings.show_inserts = 0;
                this.proposal_submission_settings.show_invoices = this.proposals.proposal_bd_obj.show_invoices;
                this.proposal_submission_settings.show_pvp = this.proposals.proposal_bd_obj.show_pvp;
                this.proposal_submission_settings.sales_possibilities = this.proposals.proposal_bd_obj.sales_possibilities;
                this.proposal_submission_settings.discount = this.proposals.proposal_bd_obj.discount;
                this.discount = this.proposal_submission_settings.discount;
                this.offer = this.proposals.bill_obj.total_bill; //this.$utils.numberWithDotAndComma(this.$utils.roundAndFix(this.proposals.bill_obj.total_bill));
                this.loadFormObj();        
            }
        },  
    },
    updated() {
        if(this.select_company == ''){
            let me = this;
            $("#select_company").select2("destroy");

            $("#select_company").select2();
            $("#select_company").select2("val", "");
            $('#select_company').select2({
                placeholder: "Selecciona una empresa",
                maximumInputLength: 20
            });
            $('#select_company').on("change",function(){
                me.select_company = $('#select_company').val();
                me.getNameCompany(me.select_company);
            });
        }

        if(this.select_company_other_values == ''){
            let me = this;
            $("#select_company_other_values").select2("destroy");

            $("#select_company_other_values").select2();
            $("#select_company_other_values").select2("val", "");
            $('#select_company_other_values').select2({
                placeholder: "Selecciona una empresa"
            });
            $('#select_company_other_values').on("change",function(){
                me.select_company_other_values = $('#select_company_other_values').val();
                me.getNameCompany(me.select_company_other_values);
            });
        }
    }
};
</script>