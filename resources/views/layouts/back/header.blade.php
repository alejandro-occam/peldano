<div class="container-fluid d-flex align-items-stretch justify-content-between">
    <div class="my-auto">
        <img width=125 class="my-auto" src="../media/custom-imgs/logo_peldanno_login.svg" />
    </div>
    <div class="my-auto mr-auto ml-50">
        <span class="color-white font-weight-bold font-size-base d-none d-md-inline mr-1">Hoy es: <b>{{ Date('l, j') }} de {{ Date('F') }}</b></span>
    </div>
    <div class="topbar">
        <!--begin::User-->
        <div class="topbar-item">
            <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                <span class="color-blue font-weight-bold font-size-base d-none d-md-inline mr-1">Hola,</span>
                <span class="color-blue font-weight-bolder font-size-base d-none d-md-inline mr-3">{{Auth::user()->getNameUser(Auth::user()->id)}}</span>
                <span class="symbol symbol-lg-35 symbol-25 symbol-blue">
                    <span class="symbol-label font-size-h5 font-weight-bold">{{Auth::user()->getFirstLetterName(Auth::user()->id)}}</span>
                </span>
            </div>
        </div>
        <!--end::User-->
    </div>
</div>