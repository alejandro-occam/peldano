<!DOCTYPE html>
<html lang="en">
    <!--begin::Head-->
    <head>
        <title>Propuesta: {!! $proposal->id_proposal_custom !!}</title>
        <style>
            .color-blue, .color-blue:hover{
                color: #2e49ff;
            }
            .mt-7{
                margin-top: 1.75rem !important;
            }
            .font-weight-bold{
                font-weight: bold !important;
            }
            .font-weight-bolder{
                font-weight: bolder !important;
            }
            .d-grid{
                display: grid !important;
            }
            .mb-4, .my-4 {
                margin-bottom: 1rem !important;
            }
            .mt-4, .my-4 {
                margin-top: 1rem !important;
            }
            .text-block {
                white-space: break-spaces !important;
            }
            .mt-1, .my-1 {
                margin-top: 0.25rem !important;
            }
            .mt-15, .my-15 {
                margin-top: 3.75rem !important;
            }
            .mt-10, .my-15 {
                margin-top: 2.50rem !important;
            }
            .f-10 {
                font-size: 10px;
            }
            .f-11 {
                font-size: 11px;
            }
            .f-12 {
                font-size: 12px;
            }
            .f-13 {
                font-size: 13px;
            }
            .f-14 {
                font-size: 14px;
            }
            .f-15 {
                font-size: 15px;
            }
            .page-break {
                page-break-after: always;
            }
            .custom-columns-datatable {
                background-color: transparent !important;
                color: #b5b5b5 !important;
                font-weight: bold !important;
                font-size: 14px !important;
                padding-bottom: 10px;
            }
            .text-dark {
                color: #181C32 !important;
            }
            .gray-chapter-offer-proposal {
                color: #707185 !important;
            }
            .row-chapter-offer-proposal {
                border-top-width: 1px !important;
                border-top-style: solid !important;
                border-bottom-width: 1px !important;
                border-bottom-style: solid !important;
                border-color: #ebeff2 !important;
            }
            .td-border-left {
                border-left-width: 1px !important;
                border-left-style: solid !important;
                border-left-color: #ebeff2 !important;
            }
            .text-align-center {
                text-align: center !important;
            }
            .row-chapter-offer-proposal {
                border-top-width: 1px !important;
                border-top-style: solid !important;
                border-bottom-width: 1px !important;
                border-bottom-style: solid !important;
                border-color: #ebeff2 !important;
            }
            .td-border-left {
                border-left-width: 1px !important;
                border-left-style: solid !important;
                border-left-color: #ebeff2 !important;
            }
            .bg-blue-light-white, .bg-blue-light-white:focus {
                background-color: #F6FBFF;
            }
            .pb-1, .py-1 {
                padding-bottom: 0.25rem !important;
            }
            .pb-2, .py-2 {
                padding-bottom: 0.5rem !important;
            }
            .pb-3, .py-3 {
                padding-bottom: 0.75rem !important;
            }
            .pt-1, .py-1 {
                padding-top: 0.25rem !important;
            }
            .pt-2, .py-2 {
                padding-top: 0.5rem !important;
            }
            table {
                border-collapse: collapse;
                width: 100%;
            }
            .ml-5, .mx-5 {
                margin-left: 1.25rem !important;
            }
            .mb-4, .my-4 {
                margin-bottom: 1rem !important;
            }
            .custom-columns-datatable {
                background-color: transparent !important;
                color: #b5b5b5 !important;
                font-weight: bold !important;
                font-size: 14px !important;
                padding-bottom: 10px;
            }
            .row-chapter {
                background-color: #F6FBFF;
                border-top-width: 1px;
                border-top-style: solid;
                border-bottom-width: 1px;
                border-bottom-style: solid;
                border-color: #ebeff2;
            }
            .row-article {
                border-top-width: 1px;
                border-top-style: solid;
                border-bottom-width: 1px;
                border-bottom-style: solid;
                border-color: #ebeff2;
            }
                        .tr-total-datatable {
                background-color: #dbf1ff;
                color: #0050db;
                padding-top: 10px;
                padding-bottom: 10px;
                border-bottom: 1px;
                border-bottom-style: solid;
                border-bottom-color: #ebeff2;
            }

            .td-border-right {
                border-right-width: 1px;
                border-right-style: solid;
                border-right-color: #ebeff2;
            }
            .pb-3, .py-3 {
                padding-bottom: 0.75rem !important;
            }
            .pt-3, .py-3 {
                padding-top: 0.75rem !important;
            }
            .pb-4, .py-4 {
                padding-bottom: 1rem !important;
            }
            .pt-4, .py-4 {
                padding-top: 1rem !important;
            }
            .pb-5, .py-5 {
                padding-bottom: 1.25rem !important;
            }
            .pt-5, .py-5 {
                padding-top: 1.25rem !important;
            }
            .pb-6, .py-6 {
                padding-bottom: 1.50rem !important;
            }
            .pt-6, .py-6 {
                padding-top: 1.50rem !important;
            }
            .pl-5, .px-5 {
                padding-left: 1.25rem !important;
            }
            .pr-5, .px-5 {
                padding-right: 1.25rem !important;
            }
            .bg-white{
                background-color: #fff !important;
            }
            .p-5{
                padding: 1rem !important;
            }
            .col-2 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 16.6666666667%;
                flex: 0 0 16.6666666667%;
                max-width: 16.6666666667%;
            }
            .header,
            .footer {
                width: 100%;
                position: fixed;
            }
            .header {
                top: 0px;
            }
            .footer {
                bottom: 0px;
            }
            .pagenum:before {
                content: counter(page);
            }
            .float-right{
                float: right
            }
            .my-auto{
                margin-top: auto;
                margin-bottom: auto;
            }
            .p-0{
                padding-top: 0px;
                padding-bottom: 0px;
                padding-left: 0px;
                padding-right: 0px;
            }
            .pagenum:before {
                content: counter(page);
            }
            .pagenum:before {
                content: counter(pageTotal);
            }
        </style>
    </head>
    <!--end::Head-->
    <!--begin::Body-->
    <body id="kt_body" class="header-mobile-fixed subheader-enabled aside-enabled aside-fixed aside-secondary-enabled page-loading">
        <!--begin::Main-->
        <div class="container">
            <div class="mb-20">
                <div class="justify-content-center">
                    <div class="card card-custom shadow-none border-0">
                        <div class="card-body body-tab-step">
                            <div data-select2-id="7">
                                <div class="col-12 pl-0 mt-15">
                                    <div id="test" class="col-12 pl-0 mt-10">
                                        <div class="col-12 pl-0 mt-10 text-align-center">
                                            <img src="https://peldano.occamagenciadigital.com/media/custom-imgs/logo_azul.png" width="300px" >
                                        </div>
                                        <div>
                                            <h2 class="color-blue">Propuesta</h2>
                                            <span class="f-15">{!! $proposal->commercial_name !!}</span><br>
                                            <span class="f-15">{!! $proposal->date_proyect !!}</span>
                                        </div>
                                        <div class="footer d-flex">
                                            <span class="f-13 color-blue">Comunicamos. Conectamos. Impulsamos</span>
                                            <img class="float-right my-auto" src="https://peldano.occamagenciadigital.com/media/custom-imgs/logo_azul.png" width="30px" >
                                        </div>
                                        <div class="page-break"></div>
                                        <div class="header d-flex">
                                            <span class="f-13 color-blue">Propuesta</span>
                                            <img class="float-right my-auto" src="https://peldano.occamagenciadigital.com/media/custom-imgs/logo_azul.png" width="30px" >
                                        </div>
                                        <h1 class="color-blue">{!! $proposal->commercial_name !!}</h1>
                                        <h3 class="color-blue" style="font-weight: normal !important;">{!! $proposal->date_proyect !!}</h3>
                                        <div class="mt-7">
                                            <div class="d-grid my-4">
                                                <span class="f-14 color-blue font-weight-bolder">OBJETIVOS</span> <br>
                                            </div>
                                            <div class="d-grid">
                                                <span class="mt-1 text-block">{!! $proposal->objetives !!}</span>
                                            </div>
                                            <div class="d-grid my-4">
                                                <span class="f-14 color-blue font-weight-bolder">PROPUESTA</span>
                                            </div>
                                            <div class="d-grid">
                                                <span class="mt-1 text-block">{!! $proposal->proposal !!}</span>
                                            </div>
                                            <div class="d-grid my-4">
                                                <span class="f-14 color-blue font-weight-bolder">ACCIONES</span>
                                            </div>
                                            <div class="d-grid">
                                                <span class="mt-1 text-block">{!! $proposal->actions !!}</span>
                                            </div>
                                            <div class="footer d-flex">
                                                <span class="f-13 pagenum color-blue float-right">/3</span>
                                            </div>
                                            <div class="page-break"></div>
                                            <div class="header d-flex">
                                                <span class="f-13 color-blue">Propuesta</span>
                                                <img class="float-right my-auto" src="https://peldano.occamagenciadigital.com/media/custom-imgs/logo_azul.png" width="30px" >
                                            </div>
                                            <div class="d-grid mt-10 mb-4">
                                                <table width="100%" cellpadding="2" cellspacing="1" id="table_1">
                                                    <tbody>
                                                        <tr class="row-chapter-offer-proposal">
                                                            <td colspan="4" class="f-15 py-1">
                                                                <span class=" gray-chapter-offer-proposal f-10"><b class="text-dark ml-5">Cliente: </b>{!! $proposal->commercial_name !!}</span>
                                                            </td>
                                                            <td colspan="2" class="py-1 td-border-left text-align-center">
                                                                <span class="gray-chapter-offer-proposal font-weight-bolder f-10">PROPUESTA Nº: </span>
                                                                <span class="text-dark f-10">{!! $proposal->id_proposal_custom !!}</span>
                                                            </td>
                                                        </tr>
                                                        <tr class="row-chapter-offer-proposal">
                                                            <td class="py-2">
                                                                <div class="f-10 ml-5 font-weight-bolder gray-chapter-offer-proposal">FECHA:</div>
                                                                <div class="ml-5 f-10 text-dark">{!! $proposal->date_proyect !!}</div>
                                                            </td>
                                                            <td class="py-2 td-border-left">
                                                                <div class="f-10 ml-5 font-weight-bolder gray-chapter-offer-proposal">CONSULTOR:</div>
                                                                <div class="ml-5 f-10 text-dark">{!! $fullname !!}</div>
                                                            </td>
                                                            <td class="py-2 td-border-left">
                                                                <div class="f-10 ml-5 font-weight-bolder gray-chapter-offer-proposal">DEPARTAMENTO:</div>
                                                                <div class="ml-5 f-10 text-dark">{!! $department_name !!}</div>
                                                            </td>
                                                            <td class="py-2 td-border-left">
                                                                <div class="f-10 ml-5 font-weight-bolder gray-chapter-offer-proposal">ANUNCIANTE:</div>
                                                                <div class="ml-5 f-10 text-dark">{!! $advertiser !!}</div>
                                                            </td><!--v-if-->
                                                            <td colspan="2" class="py-2 td-border-left bg-blue-light-white">
                                                                <div class="f-10 ml-5 font-weight-bolder color-blue">OFERTA:</div>
                                                                <div class="ml-5 f-10 text-dark">{{ $bill_obj->total_bill }}€</div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="d-grid mb-4 mt-10">
                                                <span class="f-12 color-blue font-weight-bold">PROPUESTA</span>
                                                <table class="mt-4" width="100%" cellpadding="2" cellspacing="1">
                                                    <thead class="custom-columns-datatable">
                                                        <tr>
                                                            <th tabindex="0" class="pb-1 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;">
                                                                <span class="f-10">SERVICIOS</span>
                                                            </th>
                                                            <th tabindex="0" class="pb-1 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;">
                                                                <span class="f-10">PVP</span>
                                                            </th>
                                                            <th tabindex="0" class="pb-1 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;">
                                                                <span class="f-10">N</span>
                                                            </th>
                                                            @foreach($proposal_obj->array_dates as $date_obj)
                                                            <th tabindex="0" class="pb-1 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;">
                                                                <span class="f-10">{{ $date_obj->date }}</span>
                                                            </th>
                                                            @endforeach
                                                            <th tabindex="0" class="pb-1 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;">
                                                                <span class="f-10">TOTAL</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($proposal_obj->chapters as $key_chapter => $chapter)
                                                            <tr class="row-chapter">
                                                                <td class="py-1" colspan="{{ count($proposal_obj->array_dates) + 4 }}">
                                                                    <span class="ml-5 f-10">{{ $chapter->chapter_obj->name }}</span>
                                                                </td>
                                                            </tr>
                                                            @foreach($chapter->articles as $key_article => $article)
                                                                <tr class="row-article">
                                                                    <td valign="middle" class="td-border-right py-1">
                                                                        <span class="ml-5 f-10">{{ $article->article_obj->name }}</span>
                                                                    </td>
                                                                    <td valign="middle" class="td-border-right text-align-center py-1">
                                                                        <span class="f-10">{{ $article->article_obj->pvp }}€</span>
                                                                    </td>
                                                                    <td valign="middle" class="td-border-right text-align-center py-1">
                                                                        <span class="f-10">{{ $article->amount }}</span>
                                                                    </td>
                                                                    @foreach($proposal_obj->array_dates as $key_array_dates => $date)
                                                                    <td valign="middle" class="td-border-right py-1 text-align-center">
                                                                        @foreach($article->dates_prices as $key_dates_prices => $date_price)
                                                                            @if($date->date == $date_price->date)
                                                                                @foreach($date_price->arr_pvp_date as $key_arr_pvp_date => $pvp_date)
                                                                                    <div class="d-grid px-5">
                                                                                        @foreach($pvp_date->arr_pvp as $pvp)
                                                                                            <span class="mx-auto f-10">{{ $pvp }}€</span>
                                                                                        @endforeach                                                                                
                                                                                    </div>
                                                                                @endforeach
                                                                            @endif
                                                                        @endforeach
                                                                    </td>
                                                                    @endforeach  
                                                                    <td valign="middle" class="td-border-right text-align-center py-1">
                                                                        <span class="f-10">{{ $article->total }}€</span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endforeach
                                                        <tr class="tr-total-datatable">
                                                            <td class="py-1">
                                                                <span class="ml-5 font-weight-bolder f-10">TOTAL</span>
                                                            </td>
                                                            <td class="text-align-center">
                                                                <span class="font-weight-bolder f-10">{{ $proposal_obj->total_individual_pvp }}€</span>
                                                            </td>
                                                            <td class="text-align-center">
                                                                <span class="font-weight-bolder f-10">{{ $proposal_obj->total_amount_global }}</span>
                                                            </td>
                                                            @foreach($proposal_obj->array_dates as $key_array_dates => $date)
                                                                <td class="text-align-center">
                                                                    <span class="font-weight-bolder f-10">{{ $date->total }}€</span>
                                                                </td>
                                                            @endforeach
                                                            <td class="text-align-center">
                                                                <span class="font-weight-bolder f-10">{{ $proposal_obj->total_global }}€</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="d-grid mb-4 mt-10">
                                                <span class="f-12 color-blue font-weight-bold">PLAN DE PAGO</span>
                                            </div>
                                            
                                            <table width="100%" cellpadding="0" cellspacing="0" >
                                                <thead class="custom-columns-datatable">
                                                    <tr>
                                                        <th tabindex="0" class="pb-1 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 50px;">
                                                            <span class="f-10">FACTURAS</span>
                                                        </th>
                                                        <th tabindex="0" class="pb-1 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;">
                                                            <span class="f-10">FECHA</span>
                                                        </th>
                                                        <th tabindex="0" class="pb-1 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 135px;">
                                                            <span class="f-10">FORMA DE PAGO</span>
                                                        </th>
                                                        <th tabindex="0" class="pb-1 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;">
                                                            <span class="f-10">VENCIMIENTO</span>
                                                        </th>
                                                        <th tabindex="0" class="pb-1 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 145px;">
                                                            <span class="f-10">IMPORTE</span>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($bill_obj->array_bills as $key_bill => $bill)
                                                        <tr class="row-article text-align-center bg-white">
                                                            <td class="td-border-right text-align-center f-10" rowspan="{{ $bill->rows }}">{{ $key_bill + 1 }}</td>
                                                        </tr>
                                                        <tr class="row-article bg-white">
                                                            <td class="text-align-center td-border-right f-10">{{ $bill->date }}</td>
                                                            <td class="text-align-center py-1 px-5 td-border-right f-10" width="20%">
                                                                {{ json_decode($select_way_to_pay_options[$bill->select_way_to_pay])->text }}
                                                            </td>
                                                            <td class="text-align-center py-1 px-5 td-border-right f-10">
                                                                {{ json_decode($select_expiration_options[$bill->select_expiration])->text }}
                                                            </td>
                                                            <td class="text-align-center f-10">{{ $bill->amount }}</td>
                                                        </tr>
                                                        @if(!empty($bill->observations))
                                                            <tr class="row-article">
                                                                <td class="px-5 py-1" colspan="4">
                                                                    <div class="d-flex">
                                                                        <span class="my-auto col-12 f-10">Observaciones: {{ $bill->observations }}</span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        @if(!empty($bill->order_number))
                                                            <tr class="row-article">
                                                                <td class="px-5 py-1" colspan="4">
                                                                    <div class="d-flex">
                                                                        <span class="my-auto col-12 f-10">Núm. pedido: {{ $bill->order_number }}</span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        @if(!empty($bill->internal_observations))
                                                            <tr class="row-article">
                                                                <td class="px-5 py-1" colspan="4">
                                                                    <div class="d-flex">
                                                                        <span class="my-auto col-12 f-10">Observaciones Internas: {{ $bill->internal_observations }}</span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        
                                                    @endforeach
                                                    <tr class="tr-total-datatable">
                                                        <td colspan="4" class="py-1">
                                                            <span class="ml-5 font-weight-bolder f-10">TOTAL</span>
                                                        </td>
                                                        <td class="text-align-center">
                                                            <span class="font-weight-bolder f-10">{{ $bill_obj->total_bill }}€</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="d-grid mt-10 mb-4">
                                                <span class="f-11"><b>Aviso legal</b></span><br>
                                                <span class="f-10">Estos precios no incluyen IVA. La aprobación de esta propuesta implica la aceptación de las condiciones de contratación que puedes encontrar en la dirección web:</span><br>
                                                <a class="f-10" href="https://peldano.com/condiciones-de-contratacion-de-planes-de-comunicacion/">https://peldano.com/condiciones-de-contratacion-de-planes-de-comunicacion/</a>
                                            </div>
                                            <div class="d-grid text-align-center mb-4">
                                                <span class="f-14"><b>Conforme cliente (firma y sello):</b></span><br>
                                            </div>
                                            <div class="footer d-flex">
                                                <span class="f-13 pagenum color-blue float-right">/3</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <!--end::Body-->
</html>