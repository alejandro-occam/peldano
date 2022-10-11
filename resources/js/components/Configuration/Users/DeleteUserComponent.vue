<template>
<div class="modal fade" id="modal_delete_user" tabindex="-1" role="dialog" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Modal Header -->
        <div class="modal-content">
            <form @submit.prevent="">
                <div class="modal-header">
                    <h2 class="mx-auto color-blue" id="title_modal">Eliminar usuario</h2>
                    <button type="button" class="close position-absolute" style="right: 22px;" data-dismiss="modal" @click="this.closeModal()">
                        &times;
                    </button>
                </div>
                <div class="modal-body text-align-center">
                    <span class="mx-auto font-size-h4" >Estás a un paso de borrar la siguiente cuenta:</span >
                    <div class="mt-5">
                        <span class="mx-auto font-size-h4 text-dark font-weight-bold" ></span>
                    </div>
                    <div class="mt-10 d-grid">
                        <img class="mx-auto" width=100 src="/media/custom-imgs/icono_perfil_dashboard.svg" />
                        <span class="font-weight-bolder mt-4">{{ this.config.users.user_obj.name }} {{ this.config.users.user_obj.surname }}</span>
                        <span class="font-weight-bolder color-dark-gray">{{ this.config.users.user_obj.email }}</span>
                    </div>
                    <div class="mt-10 mb-5">
                        <span class="mx-auto font-size-h4 color-blue" >¿Confirmas su eliminación?</span >
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-close ml-auto px-10 color-blue font-weight-bold" data-dismiss="modal" @click="this.closeModal()">
                        Cancelar
                    </button>
                    <button type="submit" class="btn bg-azul color-white px-10 font-weight-bold" @click="this.deleteUser(config.users.user_obj.id)">
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</template>

<script>

import { mapState, mapActions } from "vuex";

export default {
    name: "DeleteUserComponent",
    data() {
        return {
            publicPath: window.location.origin,
        };
    },
    computed: {
        ...mapState(["config", "errors"]),
    },
    methods: {
        ...mapActions(["deleteUser"]),
        closeModal(){
            $("#modal_delete_user").modal("hide");
        }
    },
    mounted() {}
};
</script>
