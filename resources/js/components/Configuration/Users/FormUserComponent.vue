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
                    <select class="form-control w-100 bg-gray text-dark select-custom" :name="'select_currency_change_balance'" :id="'select_currency_change_balance'" v-model="select_currency_change_balance" data-style="select-lightgreen" >
                        <option value="" selected>
                            Seleccionar el tipo de moneda
                        </option>
                        <option :value="currency.id" v-for="currency in array_currencies" :key="currency.id" v-text="currency.name" ></option>
                    </select>
                    <small class="text-danger" v-if="position_error">Los apellidos no son válidos</small>
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
                    <input v-model="rol" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger" v-if="rol_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Comisión</span>
                <div class="w-50">
                    <input v-model="commission" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger" v-if="commission_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Activo</span>
                <div class="w-50">
                    <input v-model="status" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger" v-if="status_error">Los apellidos no son válidos</small>
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
                position: '',
                position_error: false,
                extension: '',
                extension_error: false,
                mobile: '',
                mobile_error: false,
                discharge_date: '',
                discharge_date_error: false,
                rol: '',
                rol_error: false,
                commission: '',
                commission_error: false,
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