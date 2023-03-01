<!--MENU-->
<div class="aside-menu-wrapper flex-column-fluid bg-white" id="kt_aside_menu_wrapper">
    <!--begin::Menu Container-->
    <div id="kt_aside_menu" class="aside-menu my-4 scroll ps ps--active-y bg-white" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500" style="height: 350px; overflow: hidden;">
        <!--begin::Menu Nav-->
        <ul class="menu-nav">
            <li lass="nav-item mb-3 {{ Request::is('/admin/reports*') ? 'menu-item-active' : '' }}" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window">
                <router-link to="/admin/reports" class="menu-link">
                    <div style="padding: 9px 25px;">
                        <span class="svg-icon svg-icon-xl color-gray menu-text" >
                            <img class="btn-folder mr-2 reports" src="{{ Request::is('/admin/reports*') ? '/media/custom-imgs/icono_informes_activo.svg' : '/media/custom-imgs/icono_informes_desactivo.svg' }}" width="35" height="35"/>
                            <b>Informes</b>
                        </span>
                    </div>
                </router-link>
            </li>
            <li lass="nav-item mb-3 {{ Request::is('/admin/proposals') ? 'menu-item-active' : '' }}" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window">
                <router-link to="/admin/proposals" class="menu-link">
                    <div style="padding: 9px 25px;">
                        <span class="svg-icon svg-icon-xl color-gray menu-text" >
                            <img class="btn-folder mr-2 proposal" src="{{ Request::is('/admin/proposals') ? '/media/custom-imgs/icono_propuestas_activo.svg' : '/media/custom-imgs/icono_propuestas_desactivo.svg' }}" width="35" height="35"/>
                            <b>Propuestas</b>
                        </span>
                    </div>
                </router-link>
            </li>
            <li lass="nav-item mb-3 {{ Request::is('/admin/orders') ? 'menu-item-active' : '' }}" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window">
                <router-link to="/admin/orders" class="menu-link">
                    <div style="padding: 9px 25px;">
                        <span class="svg-icon svg-icon-xl color-gray menu-text" >
                            <img class="btn-folder mr-2 config" src="{{ Request::is('/admin/orders') ? '/media/custom-imgs/icono_config_activo.svg' : '/media/custom-imgs/icono_config_desactivo.svg' }}" width="35" height="35"/>
                            <b>Órdenes</b>
                        </span>
                    </div>
                </router-link>
            </li>
            @if(Auth::user()->hasRole('admin'))
            <li lass="nav-item mb-3 {{ Request::is('/admin/configuration') ? 'menu-item-active' : '' }}" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window">
                <router-link to="/admin/configuration" class="menu-link">
                    <div style="padding: 9px 25px;">
                        <span class="svg-icon svg-icon-xl color-gray menu-text" >
                            <img class="btn-folder mr-2 config" src="{{ Request::is('/admin/configuration') ? '/media/custom-imgs/icono_config_activo.svg' : '/media/custom-imgs/icono_config_desactivo.svg' }}" width="35" height="35"/>
                            <b>Configuración</b>
                        </span>
                    </div>
                </router-link>
            </li>
            <li lass="nav-item mb-3 {{ Request::is('/admin/invoice_validation') ? 'menu-item-active' : '' }}" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window">
                <router-link to="/admin/invoice_validation" class="menu-link">
                    <div style="padding: 9px 25px;">
                        <span class="svg-icon svg-icon-xl color-gray menu-text" >
                            <img class="btn-folder mr-2 config" src="{{ Request::is('/admin/invoice_validation') ? '/media/custom-imgs/icono_config_activo.svg' : '/media/custom-imgs/icono_config_desactivo.svg' }}" width="35" height="35"/>
                            <b>Validación de factura</b>
                        </span>
                    </div>
                </router-link>
            </li>
            @endif
        </ul>
        <!--end::Menu Nav-->
    </div>
    <!--end::Menu Container-->
</div>
<!--end::Footer-->