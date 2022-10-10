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
                <span class="my-auto w-25">Nombre</span>
                <div class="w-50">
                    <input v-model="name" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger " v-if="name_error">El nombre no es válido</small>
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
                    <small class="text-danger" v-if="email_error">El correo no es válido</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Usuario</span>
                <div class="w-50">
                    <input v-model="user" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger" v-if="user_error">El usuario no es válido</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Contraseña</span>
                <div class="w-50">
                    <input v-model="password" type="password" class="form-control borders-box" placeholder="" :disabled="disabled_password == 1"/>
                    <small class="text-danger" v-if="password_error">La contaseña no es válida</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Cargo</span>
                <div class="w-50">
                    <select class="form-control w-100 bg-gray text-dark select-custom" :name="'select_position'" :id="'select_position'" v-model="select_position" data-style="select-lightgreen" >
                        <option value="" selected>
                            Seleccionar cargo
                        </option>
                        <option :value="position.id" v-for="position in config.users.array_positions" :key="position.id" v-text="position.name" ></option>
                    </select>
                    <small class="text-danger" v-if="select_position_error">El cargo no es válido</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Extensión</span>
                <div class="w-50">
                    <input v-model="extension" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger" v-if="extension_error">La extensión no es válida</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Móvil</span>
                <div class="w-50">
                    <input v-model="mobile" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger" v-if="mobile_error">El móvil no es válido</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" v-if="this.discharge_date != ''">
                <span class="my-auto w-25">Fecha de alta</span>
                <div class="w-50">
                    <input v-model="discharge_date" type="text" class="form-control borders-box" placeholder="" :disabled="disabled_password == 1"/>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Rol</span>
                <div class="w-50">
                    <select class="form-control w-100 bg-gray text-dark select-custom" :name="'select_rol'" :id="'select_rol'" v-model="select_rol" data-style="select-lightgreen" >
                        <option value="" selected>
                            Seleccionar Rol
                        </option>
                        <option :value="role.id" v-for="role in config.users.array_roles" :key="role.id" v-text="role.name" ></option>
                    </select>
                    <small class="text-danger" v-if="select_rol_error">El rol no es válido</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Comisión</span>
                <div class="w-50">
                    <input v-model="commission" type="text" class="form-control borders-box" placeholder="" />
                    <small class="text-danger" v-if="commission_error">La comisión no es válida</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto w-25">Activo</span>
                <div class="w-50 d-flex">
                    <div class="d-flex" v-if="this.status == 1">
                        <button class="bg-purple btn mr-4 font-weight-bold color-white d-flex py-4">
                            <div class="white-circle mr-auto">
                                <div class="purple-circle-white"></div>
                            </div>
                            <span class="px-10">Sí</span>
                        </button>
                        <button  class="purple-border btn mr-4 font-weight-bold d-flex py-4" @click="this.changeStatus(0)">
                            <div class="purple-circle mr-auto">
                                <div class="white-circle-purple"></div>
                            </div>
                            <span class="px-10">No</span>
                        </button>
                    </div>
                        
                    <div class="d-flex" v-else>
                        <button class="purple-border btn mr-4 font-weight-bold d-flex py-4" @click="this.changeStatus(1)">
                            <div class="purple-circle mr-auto">
                                <div class="white-circle-purple"></div>
                            </div>
                            <span class="px-10">Sí</span>
                        </button>
                        <button  class="bg-purple btn mr-4 font-weight-bold color-white d-flex py-4">
                            <div class="white-circle mr-auto">
                                <div class="purple-circle-white"></div>
                            </div>
                            <span class="px-10">No</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="d-flex input-group mt-10" v-if="is_update == 0">
                <span class="my-auto w-25"></span>
                <div class="w-50">
                    <button type="button" class="bg-azul btn font-weight-bolder py-4 px-6 w-100 color-white" @click="this.validateForm(1)">Añadir</button>
                </div>
            </div>
            <div class="d-flex input-group mt-10" v-else>
                <span class="my-auto w-25"></span>
                <div class="w-50">
                    <div class="my-2">
                        <button type="button" class="bg-azul btn font-weight-bolder py-4 px-6 w-100 color-white" @click="this.validateForm(2)">Guardar cambios</button>
                    </div>
                    <div class="my-3">
                        <button type="button" class="borders-box btn font-weight-bolder py-4 px-6 w-100 color-blue" @click="this.changeShowView(1)">Cancelar</button>
                    </div>
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
            ...mapActions(["getInfoFormAddUser", "addUser", "updateUser"]),
            //Cambiar el estado de activo
            changeStatus(status){
                this.status = status;
            },
            //Validar datos
            validateForm(type){
                this.valid = true;
                this.name_error = false;
                this.surname_error = false;
                this.email_error = false;
                this.user_error = false;
                this.password_error = false;
                this.select_position_error = false;
                this.extension_error = false;
                this.mobile_error = false;
                this.select_rol_error = false;
                this.commission_error = false;

                if(this.name == "" || this.name == null){
                    this.name_error = true;
                    this.valid = false;
                }
                if(this.surname == "" || this.surname == null){
                    this.surname_error = true;
                    this.valid = false;
                }
                if(this.email == "" || this.email == null || !this.validateEmail(this.email)){
                    this.email_error = true;
                    this.valid = false;
                }
                if(this.user == "" || this.user == null){
                    this.user_error = true;
                    this.valid = false;
                }
                if(type == 1){
                    if(this.password == "" || this.password == null){
                        this.password_error = true;
                        this.valid = false;
                    }
                }
                if(this.select_position == "" || this.select_position == null || this.select_position == 0){
                    this.select_position_error = true;
                    this.valid = false;
                }
                if(this.extension == "" || this.extension == null){
                    this.extension_error = true;
                    this.valid = false;
                }
                if(this.mobile == "" || this.mobile == null){
                    this.mobile_error = true;
                    this.valid = false;
                }
                if(this.select_rol == "" || this.select_rol == null || this.select_rol == 0){
                    this.select_rol_error = true;
                    this.valid = false;
                }
                if(this.commission == "" || this.commission == null){
                    this.commission_error = true;
                    this.valid = false;
                }

                if(type == 2){
                    if(this.config.users.user_obj.id == "" || this.config.users.user_obj.id == null || this.config.users.user_obj.id == 0){
                        this.valid = false;
                    }
                }

                if(this.valid){
                    if(type == 1){
                        var params ={
                            'name': this.name,
                            'surname': this.surname,
                            'user': this.user,
                            'email': this.email,
                            'password': this.password,
                            'id_position': this.select_position,
                            'extension': this.extension,
                            'mobile': this.mobile,
                            'id_rol': this.select_rol,
                            'commission': this.commission,
                            'status': this.status
                        }

                        this.addUser(params);
                    }

                    if(type == 2){
                        var params ={
                            'id_user': this.config.users.user_obj.id,
                            'name': this.name,
                            'surname': this.surname,
                            'user': this.user,
                            'email': this.email,
                            'id_position': this.select_position,
                            'extension': this.extension,
                            'mobile': this.mobile,
                            'id_rol': this.select_rol,
                            'commission': this.commission,
                            'status': this.status
                        }

                        this.updateUser(params);
                    }
                }else{
                    swal("", "Rellena todos los datos", "warning");
                }
            },
            //Validador de correo
            validateEmail(mail) {
                if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
                    return true;
                }
                return false;
            },
        },
        computed: {
            ...mapState(["config", "errors"]),
        },
        mounted() {
            this.getInfoFormAddUser();
        },
        watch: {
            '$store.state.errors.code': function() {
                if(this.errors.code != ''){
                    if(this.errors.code == 1000){
                        if(this.type == 1){
                            swal("", "Usuario creado correctamente", "success");
                        }else{
                            swal("", "Usuario modificado correctamente", "success");
                        }
                        this.changeShowView(1);
                    }else if(this.errors.code == 1001 || this.errors.code == 1002){
                        swal("", "Rellena todos los datos", "warning");
                    }else if(this.errors.code == 1003){
                        swal("", "El correo ya está registrado", "warning");
                    }else if(this.errors.code == 1004 || this.errors.code == 1005){
                        swal("", "El usuario ya está registrado", "warning");
                    }else{
                        swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                    }
                    this.clearError();
                }
            },
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