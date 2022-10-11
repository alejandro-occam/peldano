<!--MENU-->
<div class="aside-menu-wrapper flex-column-fluid bg-white" id="kt_aside_menu_wrapper">
    <!--begin::Menu Container-->
    <div id="kt_aside_menu" class="aside-menu my-4 scroll ps ps--active-y bg-white" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500" style="height: 350px; overflow: hidden;">
        <!--begin::Menu Nav-->
        <ul class="menu-nav">
            <li lass="nav-item mb-3 {{ Request::is('/admin/configuration') ? 'menu-item-active' : '' }}" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window">
                <router-link to="/admin/configuration" class="menu-link">
                    <div style="padding: 9px 25px;">
                        <span class="svg-icon svg-icon-xl color-gray menu-text" >
                            <img class="btn-folder mr-2" src="{{ Request::is('/admin/configuration') ? '/media/custom-imgs/icono_config_activo.svg' : '/media/custom-imgs/icono_config_desactivo.svg' }}" width="35" height="35"/>
                            <b>Configuración</b>
                        </span>
                    </div>
                </router-link>
            </li>
        </ul>
        <!--end::Menu Nav-->
    </div>
    <!--end::Menu Container-->
</div>
<!--end::Footer-->