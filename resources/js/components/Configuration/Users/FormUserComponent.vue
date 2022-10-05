<template>
    <div>
        <div class="d-flex">
            <h2 class="color-blue mr-auto" id="title_modal">Añadir usuario</h2>
            <AddButtonComponent
                    @click.native="changeShowView(1)"
                    :columns="'col-1'"
                    :text="'Volver'"
                    :id="'btn_add_user'"
                    :src="'/media/custom-imgs/flecha_btn_volver.svg'"
                />
        </div>
        <div class="col-6 pl-0 mt-15">
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Identificador</span>
                <div class="w-50">
                    <input v-model="id" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger" v-if="id_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Nombre</span>
                <div class="w-50">
                    <input v-model="name" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger " v-if="name_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Apellidos</span>
                <div class="w-50">
                    <input v-model="surname" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger" v-if="surname_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Email</span>
                <div class="w-50">
                    <input v-model="email" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger" v-if="email_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Usuario</span>
                <div class="w-50">
                    <input v-model="user" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger" v-if="user_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Contraseña</span>
                <div class="w-50">
                    <input v-model="password" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger" v-if="password_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Cargo</span>
                <div class="w-50">
                    <select class="form-control w-100 bg-gray text-dark select-custom" :name="'select_position'" :id="'select_position'" v-model="select_position" data-style="select-lightgreen" >
                        <option value="" selected>
                            Seleccionar cargo
                        </option>
                        <option :value="currency.id" v-for="currency in array_currencies" :key="currency.id" v-text="currency.name" ></option>
                    </select>
                    <small class="text-danger" v-if="select_position_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Extensión</span>
                <div class="w-50">
                    <input v-model="extension" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger" v-if="extension_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Móvil</span>
                <div class="w-50">
                    <input v-model="mobile" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger" v-if="mobile_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Fecha de Alta</span>
                <div class="w-50">
                    <input v-model="discharge_date" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger" v-if="discharge_date_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Rol</span>
                <div class="w-50">
                    <select class="form-control w-100 bg-gray text-dark select-custom" :name="'select_rol'" :id="'select_rol'" v-model="select_currency_change_balance" data-style="select-lightgreen" >
                        <option value="" selected>
                            Seleccionar Rol
                        </option>
                        <option :value="currency.id" v-for="currency in array_currencies" :key="currency.id" v-text="currency.name" ></option>
                    </select>
                    <small class="text-danger" v-if="select_rol_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Comisión</span>
                <div class="w-50">
                    <select class="form-control w-100 bg-gray text-dark select-custom" :name="'select_commission'" :id="'select_commission'" v-model="select_currency_change_balance" data-style="select-lightgreen" >
                        <option value="" selected>
                            Seleccionar comisión
                        </option>
                        <option :value="currency.id" v-for="currency in array_currencies" :key="currency.id" v-text="currency.name" ></option>
                    </select>
                    <small class="text-danger" v-if="select_commission_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Activo</span>
                <div class="w-50 d-flex">
                    <button class="bg-purple btn mr-4 font-weight-bold color-white d-flex py-4">
                        <div class="white-circle mr-auto">
                            <div class="purple-circle-white"></div>
                        </div>
                        <span class="px-10">Sí</span>
                    </button>
                    <button class="purple-border btn mr-4 font-weight-bold d-flex py-4">
                        <div class="purple-circle mr-auto">
                            <div class="white-circle-purple"></div>
                        </div>
                        <span class="px-10">No</span>
                    </button>
                    <small class="text-danger" v-if="status_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group mt-10" >
                <span class="my-auto w-25"></span>
                <div class="w-50">
                    <button type="submit" id="kt_login_signin_submit" class="bg-azul btn font-weight-bolder py-4 px-6 w-100 color-white">Añadir</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>    
    import { mapMutations } from "vuex";

    import AddButtonComponent from "../../Partials/AddButtonComponent.vue";

    export default {
        name: "FormUserComponent",
        components: {
            AddButtonComponent
        },
        data() {
            return {
                publicPath: window.location.origin,
                id: '',
                id_error: false,
                name: '',
                name_error: false,
                surname: '',
                surname_error: false,
                email: '',
                email_error: false,
                id: '',
                id_error: false,
                user: '',
                user_error: false,
                password: '',
                password_error: false,
                select_position: '',
                select_position_error: false,
                extension: '',
                extension_error: false,
                mobile: '',
                mobile_error: false,
                discharge_date: '',
                discharge_date_error: false,
                select_rol: '',
                select_rol_error: false,
                select_commission: '',
                select_commission_error: false,
                status: '',
                status_error: false,
            };
        },
        methods: {
            ...mapMutations(["changeShowView"]),
        },
        mounted() {
        },
    };

</script>