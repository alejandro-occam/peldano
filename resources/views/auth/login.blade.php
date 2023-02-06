<!DOCTYPE html>
<html lang="en">
    <!--begin::Head-->
    <head><base href="../../../">
        <meta charset="utf-8" />
        <title>Login</title>
        <meta name="description" content="Login page example" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!--begin::Fonts-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
        <!--end::Fonts-->
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
    </head>
    <!--end::Head-->
    <!--begin::Body-->
    <body id="kt_body" class="header-mobile-fixed subheader-enabled aside-enabled aside-fixed aside-secondary-enabled page-loading" style="overflow-y: auto;">
        <!--begin::Main-->
        <div class="d-flex flex-column flex-root wrapper-custom">
            <div class="d-flex justify-content-center align-items-center w-100 h-45" style="position: absolute;">
                <img class="w-100 h-100" style="object-fit: cover;" src="../media/custom-imgs/login.jpg" />
            </div>
            <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid content" id="kt_login">
                <div class="col-md-10 flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
                    <img width=225 class="mx-auto mt-10" src="../media/custom-imgs/logo_peldanno_login.svg" />
                    <h1 class="mx-auto title-login mt-20">Comunicamos. Conectamos. Impulsamos.</h1>
                    <!--begin::Content body-->
                    <div class="d-flex flex-column-fluid flex-center">
                        <!--begin::Signin-->
                        <div class="login-form login-signin col-md-6 card p-10 login-content" id="login-signin">
                            <!--begin::Form-->
                            <form method="POST" action="{{ route('login') }}" class="form" id="kt_login_signin_form">
                                @csrf
                                <!--begin::Title-->
                                <div class="pb-10 pt-lg-0 pt-5 text-center">
                                    <h3 class="text-dark font-size-h4 font-size-h1-lg">Iniciar sesión</span></h3>
                                </div>
                                <!--begin::Title-->
                                <!--begin::Form group-->
                                @csrf
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Usuario</label>
                                    <input class="form-control h-auto py-4 px-4 @error('email') is-invalid @enderror borders-box" type="text" name="email" value="{{ old('email') }}" autocomplete="off" />
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <!--end::Form group-->
                                <!--begin::Form group-->
                                <div class="form-group">
                                    <div class="d-flex justify-content-between mt-n5">
                                        <label class="font-size-h6 font-weight-bolder text-dark pt-5 mr-10">Contraseña</label>
                                    </div>
                                    <input class="form-control h-auto py-4 px-4 @error('password') is-invalid @enderror borders-box" type="password" name="password" autocomplete="off" />
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group my-13">
                                    <div class="checkbox col-md-12 pl-0">
                                        <div>
                                            <label>
                                                <input type="checkbox" name="remember"> <span class="ml-2" style="font-weight:400;">Recordarme</span>
                                            </label>
                                        </div>
                                        <a href="javascript:;" class="font-size-h8 font-weight-bolder enlaces ml-auto my-auto" id="kt_login_forgot">Olvidé mi contraseña</a>
                                    </div>
                                    @error('checkbox')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <!--end::Form group-->
                                <!--begin::Action-->
                                <div class="d-flex justify-content-around">
                                    <!--<a href="javascript:;" id="kt_login_signup" class="btn btn-login-light font-weight-bolder py-4 px-6">Crear cuenta</a>-->
                                    <button type="submit" id="kt_login_signin_submit" class="btn-login btn btn-primary font-weight-bolder py-4 px-6 w-100">Entrar</button>
                                </div>
                                <!--end::Action-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Signin-->
                        <!--begin::Forgot-->
                        <div class="login-form login-forgot card p-14 login-content" id="login-forgot">
                            <form class="form" method="POST" action="{{ route('send_email_reset_password') }}" novalidate="novalidate" id="kt_login_forgot_form">
                                {!! csrf_field() !!}
                                <div class="pb-5 pt-lg-0 pt-5">
                                    <h3 class="text-dark font-size-h4 font-size-h1-lg text-align-center">¿Olvidaste tu contraseña?</h3>
                                    <p class="font-size-h6 color-subtitle text-align-center">Ingrese su correo electrónico para restablecer su contraseña</p>
                                </div>
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Tu Email</label>
                                    <input class="form-control h-auto py-4 px-4 rounded-lg borders-box" type="email" placeholder="Email" name="email" value="{{ old('email') }}" id="email" autocomplete="off" />
                                </div>
                                <div class="form-group d-flex flex-wrap mb-0">
                                    <button type="submit" id="kt_login_forgot_submit" class="btn btn-login btn-primary font-weight-bolder px-8 py-4 my-3 w-100 ">Enviar</button>
                                    <button type="button" id="kt_login_forgot_cancel" class="btn btn-cancel font-weight-bolder px-8 py-4 my-3 w-100">Cancelar</button>
                                </div>
                            </form>
                        </div>
                        <!--end::Forgot-->
                    </div>
                    <!--end::Content body-->
                    <!--Footer-->
                    <div class="custom-footer">
                        <span class="color-subtitle"> {{ Date('Y') }}© {{ env('APP_NAME') }}</span>
                    </div>
                    @if(session()->get('msg_remember_error'))
                    <input id="msg_remember_error" name="msg_remember_error" type="hidden" value="true">
                    @else
                    <input id="msg_remember_error" name="msg_remember_error" type="hidden" value="false">
                    @endif
                    @if(session()->get('msg_remember_success'))
                    <input id="msg_remember_success" name="msg_remember_success" type="hidden" value="true">
                    @else
                    <input id="msg_remember_success" name="msg_remember_success" type="hidden" value="false">
                    @endif
                </div>
                <!--end::Content-->
            </div>
            <!--end::Login-->
        </div>
        <!--end::Main-->
        <script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
        <!--begin::Global Config(global config for global JS scripts)-->
        <script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#1BC5BD", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#6993FF", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#1BC5BD", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#E1E9FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
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