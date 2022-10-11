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
            <div class=" btn-icon-mobile w-auto  d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                <span class="color-white font-weight-bolder font-size-base d-md-inline mr-5">{{Auth::user()->getNameUser(Auth::user()->id)}}</span>
                <img width=50 class="mr-8" src="/media/custom-imgs/icono_ficha_usuario.svg" />
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" ><img width=25 src="/media/custom-imgs/icono_cerrar_sesion.svg" /></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
        <!--end::User-->
    </div>
</div>