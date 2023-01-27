<template>
    <div>
        <div class="d-flex">
            <h2 class="color-blue mr-auto" id="title_modal">Ficha de usuario</h2>
            <AddButtonComponent
                v-on:click="changeShowView(1)"
                :columns="'col-1'"
                :text="'Volver'"
                :id="'btn_add_user'"
                :src="'/media/custom-imgs/flecha_btn_volver.svg'"
                :width="16"
                :height="16"
                />
        </div>
        <div class="row mt-5">
            <div class="col-1">
                <img class="mx-auto" width=150 src="/media/custom-imgs/icono_perfil_dashboard.svg" />
            </div>
            <div class="col-11">
                <div class="ml-15">
                    <h2>{{ this.config.users.user_obj.name }} {{ this.config.users.user_obj.surname }}</h2>
                </div>
                <div class="ml-15 col-12 ml-11 d-flex mt-5 border-bottom pb-7 pl-0">
                    <div class="d-grid col-2 px-0">
                        <span class="color-dark-gray font-weight-bolder f-16">IDENTIFICADOR</span>
                        <span class="f-16">{{ config.users.user_obj.id }}</span>
                    </div>
                    <div class="d-grid col-2 px-0">
                        <span class="color-dark-gray font-weight-bolder f-16">NOMBRE</span>
                        <span class="f-16">{{ config.users.user_obj.name }}</span>
                    </div>
                    <div class="d-grid col-2 px-0">
                        <span class="color-dark-gray font-weight-bolder f-16">APELLIDOS</span>
                        <span class="f-16">{{ config.users.user_obj.surname }}</span>
                    </div>
                    <div class="d-grid col-2 px-0">
                        <span class="color-dark-gray font-weight-bolder f-16">EMAIL</span>
                        <span class="f-16">{{ config.users.user_obj.email }}</span>
                    </div>
                    <div class="d-grid col-2 px-0">
                        <span class="color-dark-gray font-weight-bolder f-16">USUARIO</span>
                        <span class="f-16">{{ config.users.user_obj.user }}</span>
                    </div>
                    <div class="d-grid col-2 px-0">
                        <span class="color-dark-gray font-weight-bolder f-16">CARGO</span>
                        <span class="f-16">{{ config.users.user_obj.name_position }}</span>
                    </div>
                </div>
                <div class="ml-15 col-12 ml-11 d-flex mt-5 pb-7 pl-0">
                    <div class="d-grid col-2 px-0">
                        <span class="color-dark-gray font-weight-bolder f-16">EXTENSIÓN</span>
                        <span class="f-16">{{ config.users.user_obj.extension }}</span>
                    </div>
                    <div class="d-grid col-2 px-0">
                        <span class="color-dark-gray font-weight-bolder f-16">MÓVIL</span>
                        <span class="f-16">{{ config.users.user_obj.mobile }}</span>
                    </div>
                    <div class="d-grid col-2 px-0">
                        <span class="color-dark-gray font-weight-bolder f-16">FECHA ALTA</span>
                        <span class="f-16">{{ config.users.user_obj.custom_date }}</span>
                    </div>
                    <div class="d-grid col-2 px-0">
                        <span class="color-dark-gray font-weight-bolder f-16">ROL</span>
                        <span class="f-16">{{ config.users.user_obj.role_name }}</span>
                    </div>
                    <div class="d-grid col-2 px-0">
                        <span class="color-dark-gray font-weight-bolder f-16">COMISIÓN</span>
                        <span class="f-16">{{ config.users.user_obj.commission }}</span>
                    </div>
                    <div class="d-grid col-2 px-0">
                        <span class="color-dark-gray font-weight-bolder f-16">ACTIVO</span>
                        <span v-if="config.users.user_obj.status == 0" class="f-16">No</span>
                        <span v-else class="f-16">Sí</span>
                    </div>
                </div>
            </div>
            <div class="col-12 border-top">
                <div class="pl-0 col-2">
                    <button type="button" class="bg-azul btn font-weight-bolder mt-15 py-4 my-2 w-100 color-white" @click="this.showForm()">Modificar</button>
                    <button type="button" class="bg-light-red color-red btn font-weight-bolder py-4 my-2 w-100 " @click="this.deleteUser()">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>    
    import { mapState, mapMutations, mapActions } from "vuex";

    import AddButtonComponent from "../../Partials/AddButtonComponent.vue";

    export default {
        name: "FormUserComponent",
        components: {
            AddButtonComponent
        },
        props: ["id_user"],
        data() {
            return {
                publicPath: window.location.origin,
                name: '',
                name_error: false,
                surname: '',
                surname_error: false,
                email: '',
                email_error: false,
                user: '',
                user_error: false,
                password: '',
                password_error: false,
                disabled_password: 0,
                select_position: '',
                select_position_error: false,
                extension: '',
                extension_error: false,
                mobile: '',
                mobile_error: false,
                select_rol: '',
                select_rol_error: false,
                commission: '',
                commission_error: false,
                discharge_date: '',
                status: 0,
                valid: true,
                discharge_date: '',
                is_update: 0,
            };
        },
        methods: {
            ...mapMutations(["changeShowView", "clearError"]),
            ...mapActions(["getInfoUser"]),
            //Cambiar el estado de activo
            changeStatus(status){
                this.status = status;
            },          
            showForm(){
                this.getInfoUser(this.config.users.user_obj.id);
                this.changeShowView(2);
            },
            deleteUser(){
                $("#modal_delete_user").modal("show"); 
            }
        },
        computed: {
            ...mapState(["config"]),
        },
        mounted() {
            //this.getInfoUser(this.config.users.user_obj.id);
        },
        watch: {
            '$store.state.config.users.user_obj': function() {
                let user = this.config.users.user_obj;
                this.name = user.name;
                this.surname = user.surname;
                this.user = user.user;
                this.email = user.email;
                this.password = 'password';
                this.disabled_password = 1; 
                this.select_position = user.id_position;
                this.extension = user.extension;
                this.mobile = user.mobile;
                this.select_rol = user.id_rol;
                this.commission = user.commission;
                this.status = user.active;
                this.discharge_date = user.discharge_date;
                this.is_update = 1;
            },
        }
    };

</script>