<template>
    <div>
        <h2 class="mx-auto color-blue" id="title_modal">Añadir usuario</h2>
        <div class="col-6 pl-0">
            <div class="d-flex input-group my-5" >
                <span class="my-auto mr-40">Identificador</span>
                <div class="w-50">
                    <input v-model="surname" type="text" class="form-control" placeholder="Apellidos" />
                    <small class="text-danger" v-if="surname_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto mr-40">Nombre</span>
                <div class="w-50">
                    <input v-model="surname" type="text" class="form-control" placeholder="Apellidos" />
                    <small class="text-danger" v-if="surname_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto mr-40">Apellidos</span>
                <div class="w-50">
                    <input v-model="surname" type="text" class="form-control" placeholder="Apellidos" />
                    <small class="text-danger" v-if="surname_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto mr-40">Email</span>
                <div class="w-50">
                    <input v-model="surname" type="text" class="form-control" placeholder="Apellidos" />
                    <small class="text-danger" v-if="surname_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto mr-40">Usuario</span>
                <div class="w-50">
                    <input v-model="surname" type="text" class="form-control" placeholder="Apellidos" />
                    <small class="text-danger" v-if="surname_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto mr-40">Contraseña</span>
                <div class="w-50">
                    <input v-model="surname" type="text" class="form-control" placeholder="Apellidos" />
                    <small class="text-danger" v-if="surname_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto mr-40">Cargo</span>
                <div class="w-50">
                    <input v-model="surname" type="text" class="form-control" placeholder="Apellidos" />
                    <small class="text-danger" v-if="surname_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto mr-40">Extensión</span>
                <div class="w-50">
                    <input v-model="surname" type="text" class="form-control" placeholder="Apellidos" />
                    <small class="text-danger" v-if="surname_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto mr-40">Móvil</span>
                <div class="w-50">
                    <input v-model="surname" type="text" class="form-control" placeholder="Apellidos" />
                    <small class="text-danger" v-if="surname_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto mr-40">Fecha de Alta</span>
                <div class="w-50">
                    <input v-model="surname" type="text" class="form-control" placeholder="Apellidos" />
                    <small class="text-danger" v-if="surname_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto mr-40">Extensión</span>
                <div class="w-50">
                    <input v-model="surname" type="text" class="form-control" placeholder="Apellidos" />
                    <small class="text-danger" v-if="surname_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto mr-40">Rol</span>
                <div class="w-50">
                    <input v-model="surname" type="text" class="form-control" placeholder="Apellidos" />
                    <small class="text-danger" v-if="surname_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto mr-40">Comisión</span>
                <div class="w-50">
                    <input v-model="surname" type="text" class="form-control" placeholder="Apellidos" />
                    <small class="text-danger" v-if="surname_error">Los apellidos no son válidos</small>
                </div>
            </div>
            <div class="d-flex input-group my-5" >
                <span class="my-auto mr-40">Activo</span>
                <div class="w-50">
                    <input v-model="surname" type="text" class="form-control" placeholder="Apellidos" />
                    <small class="text-danger" v-if="surname_error">Los apellidos no son válidos</small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>    
    export default {
        name: "FormUserComponent",
        components: {
        },
        data() {
            return {
                publicPath: window.location.origin,
    
            };
        },
        methods: {
    
        },
        mounted() {
        },
    };
</script>