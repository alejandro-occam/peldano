<!DOCTYPE html>
<html lang="en">
    <!--begin::Head-->
    <head><base href="../../../">
        <meta charset="utf-8" />
        <title>Login</title>
        <meta name="description" content="Login page example" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!--begin::Page Custom Styles(used by this page)-->
        <link href="{{ url('/css/login.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('/css/pages/login/login-1.css') }}" rel="stylesheet" type="text/css" />
        <!--end::Page Custom Styles-->
        <!--begin::Global Theme Styles(used by all pages)-->
        <link href="{{ url('/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('/plugins/custom/prismjs/prismjs.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <!--end::Global Theme Styles-->
        <!--begin::Layout Themes(used by all pages)-->
        <!--end::Layout Themes-->
        <link rel="shortcut icon" />
        <link rel="shortcut icon" href="{{asset('assets/media/logos/favicon.ico')}}"/> 
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
            .gray-product-offer-proposal {
                color: #707185 !important;
            }
            .row-product-offer-proposal {
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
            .row-product-offer-proposal {
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
            .pb-2, .py-2 {
                padding-bottom: 0.5rem !important;
            }
            .pb-3, .py-3 {
                padding-bottom: 0.75rem !important;
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
            .row-product {
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
                                                <div class="page-break"></div>
                                                <div class="d-grid mt-15 mb-4">
                                                    <table width="100%" cellpadding="2" cellspacing="1" id="table_1">
                                                        <tbody>
                                                            <tr class="row-product-offer-proposal">
                                                                <td colspan="4" class="f-15 py-2">
                                                                    <span class=" gray-product-offer-proposal"><b class="text-dark ml-5">Cliente: </b>{!! $proposal->commercial_name !!}</span>
                                                                </td>
                                                                <td colspan="2" class="py-2 td-border-left text-align-center">
                                                                    <span class="gray-product-offer-proposal font-weight-bolder">PROPUESTA Nº: </span>
                                                                    <span class="text-dark">{!! $proposal->id_proposal_custom !!}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="row-product-offer-proposal">
                                                                <td class="py-2">
                                                                    <div class="f-13 ml-5 font-weight-bolder gray-product-offer-proposal">FECHA:</div>
                                                                    <div class="ml-5 f-13 text-dark">{!! $proposal->date_proyect !!}</div>
                                                                </td>
                                                                <td class="py-2 td-border-left">
                                                                    <div class="f-13 ml-5 font-weight-bolder gray-product-offer-proposal">CONSULTOR:</div>
                                                                    <div class="ml-5 f-13 text-dark">{!! $fullname !!}</div>
                                                                </td>
                                                                <td class="py-2 td-border-left">
                                                                    <div class="f-13 ml-5 font-weight-bolder gray-product-offer-proposal">SECTOR:</div>
                                                                    <div class="ml-5 f-13 text-dark">{!! $sector_name !!}</div>
                                                                </td>
                                                                <td class="py-2 td-border-left">
                                                                    <div class="f-13 ml-5 font-weight-bolder gray-product-offer-proposal">ANUNCIANTE:</div>
                                                                    <div class="ml-5 f-13 text-dark">{!! $proposal->commercial_name !!}</div>
                                                                </td><!--v-if-->
                                                                <td colspan="2" class="py-2 td-border-left bg-blue-light-white">
                                                                    <div class="f-13 ml-5 font-weight-bolder color-blue">OFERTA:</div>
                                                                    <div class="ml-5 f-13 text-dark">20.00€</div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="d-grid mb-4 mt-15">
                                                    <span class="f-14 color-blue font-weight-bold">PROPUESTA</span>
                                                    <table class="mt-4" width="100%" cellpadding="2" cellspacing="1">
                                                        <thead class="custom-columns-datatable">
                                                            <tr>
                                                                <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;">
                                                                    <span>SERVICIOS</span>
                                                                </th>
                                                                <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;">
                                                                    <span>PVP</span>
                                                                </th>
                                                                <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;">
                                                                    <span>N</span>
                                                                </th>
                                                                <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;">
                                                                    <span>NOV22</span>
                                                                </th>
                                                                <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 165px;">
                                                                    <span>TOTAL</span>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="row-product">
                                                                <td class="py-2" colspan="5">
                                                                    <span class="ml-5">Producto 1</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="row-article">
                                                                <td valign="middle" class="td-border-right py-5">
                                                                    <span class="ml-5">Articulo 1</span>
                                                                </td>
                                                                <td valign="middle" class="td-border-right text-align-center py-5">
                                                                    <span class="">20,00€</span>
                                                                </td>
                                                                <td valign="middle" class="td-border-right text-align-center py-5">
                                                                    <span class="">1</span>
                                                                </td>
                                                                <td valign="middle" class="td-border-right py-5">
                                                                    <div class="d-grid px-5">
                                                                        <img class="mx-auto my-2" width="8" src="{{ asset('/media/custom-imgs/circle.png') }}" />
                                                                    </div>
                                                                </td>
                                                                <td valign="middle" class="td-border-right text-align-center py-5">
                                                                    <span class="">20,00€</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="tr-total-datatable">
                                                                <td class="py-6">
                                                                    <span class="ml-5 font-weight-bolder">TOTAL</span>
                                                                </td>
                                                                <td class="text-align-center">
                                                                    <span class="font-weight-bolder">20,00€</span>
                                                                </td>
                                                                <td class="text-align-center">
                                                                    <span class="font-weight-bolder">1</span>
                                                                </td>
                                                                <td class="text-align-center">
                                                                    <span class="font-weight-bolder">20,00€</span>
                                                                </td>
                                                                <td class="text-align-center">
                                                                    <span class="font-weight-bolder">20,00€</span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="d-grid mb-4 mt-15">
                                                    <span class="f-14 color-blue font-weight-bold">PLAN DE PAGO</span>
                                                </div>
                                                
                                                <table width="100%" cellpadding="2" cellspacing="1" >
                                                    <thead class="custom-columns-datatable">
                                                        <tr>
                                                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 50px;">
                                                                <span>FACTURAS</span>
                                                            </th>
                                                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;">
                                                                <span>FECHA</span>
                                                            </th>
                                                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 135px;">
                                                                <span>FORMA DE PAGO</span>
                                                            </th>
                                                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;">
                                                                <span>VENCIMIENTO</span>
                                                            </th>
                                                            <th tabindex="0" class="pb-3 text-align-center" aria-controls="example" rowspan="1" colspan="1" style="width: 145px;">
                                                                <span>IMPORTE</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="row-product text-align-center bg-white">
                                                            <td class="td-border-right" rowspan="2">1</td>
                                                        </tr>
                                                        <tr class="row-product bg-white">
                                                            <td class="text-align-center td-border-right">03-11-2022</td>
                                                            <td class="text-align-center py-4 px-5 td-border-right" width="20%">Recibo bancario</td>
                                                            <td class="text-align-center py-4 px-5 td-border-right">15 días</td>
                                                            <td class="text-align-center">20.00</td>
                                                        </tr>
                                                        <tr class="tr-total-datatable">
                                                            <td colspan="4" class="py-6">
                                                                <span class="ml-5 font-weight-bolder">TOTAL</span>
                                                            </td>
                                                            <td class="text-align-center">
                                                                <span class="font-weight-bolder">20.00€</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Main-->
        <script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
        <!--begin::Global Config(global config for global JS scripts)-->
        <!--end::Global Config-->
        <!--begin::Global Theme Bundle(used by all pages)-->
        <script src="{{ url('/plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{ url('/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
        <script src="{{ url('/js/scripts.bundle.js') }}"></script>
        <!--end::Global Theme Bundle-->
        <!--begin::Page Scripts(used by this page)-->
        <script src="{{ url('/js/login.js') }}"></script>
        <!--end::Page Scripts-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </body>
    <!--end::Body-->
</html>