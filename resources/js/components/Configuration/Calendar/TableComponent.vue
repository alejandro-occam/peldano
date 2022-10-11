<template>
    <div class="row">
        <div class="col-12 d-flex flex-wrap justify-content-between">
            <div class="d-flex align-items-center justify-content-center w-15">
                <select class="form-control w-100 bg-gray text-dark select-custom select-filter" :name="'select_calendar_filter'" :id="'select_calendar_filter'" v-model="select_calendar_filter" data-style="select-lightgreen" >
                    <option value="" selected>
                        Elige un calendario
                    </option>
                    <option :value="role.id" v-for="role in config.users.array_roles" :key="role.id" v-text="role.name" ></option>
                </select>
            </div>
            <AddButtonComponent
                @click.native="changeShowView(2)"
                :columns="'col-1 ml-auto mr-7'"
                :text="'Exportar'"
                :id="'btn_export'"
                :src="'/media/custom-imgs/icono_btn_exportar.svg'"
                :width="16"
                :height="16"
            />
            <AddButtonComponent
                @click.native="openFormModal()"
                :columns="'col-1'"
                :text="'Añadir número'"
                :id="'btn_add_number'"
                :src="'/media/custom-imgs/icono_btn_annadir_numero.svg'"
                :width="25"
                :height="25"
            />
        </div>
        <div class="col-12">
            <div
                class="datatable datatable-bordered datatable-head-custom"
                id="list_users"
                style="width: 100%"
            ></div>
        </div>
    </div>
</template>

<script>
    import { mapMutations, mapActions, mapState } from "vuex";


    import SearchComponent from "../../Partials/SearchComponent.vue";
    import AddButtonComponent from "../../Partials/AddButtonComponent.vue";

    export default {
        name: "TableComponent",
        components: {
            SearchComponent,
            AddButtonComponent,
        },
        data() {
            return {
                publicPath: window.location.origin,
                select_calendar_filter: ''
            };
        },
        methods: {
            ...mapActions([""]),
            ...mapMutations([""]),
            openFormModal(){
                $('#modal_form_number_calendar').modal('show');
            }
        },
        computed: {
                ...mapState(["errors", "config"]),
        },
        mounted() {
        },
        watch: {
            '$store.state.errors.code': function() {
                if(this.errors.code != ''){
                    if(this.errors.code == 1000){
                        $("#list_users").KTDatatable("reload");
                        $("#modal_delete_user").modal("hide");
                        swal("", "Usuario eliminado correctamente", "success");
                    }else if(this.errors.code == 1001){
                        swal("", "El usuario no existe", "warning");
                    }else{
                        swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                    }
                    this.clearError();
                }
            },
        }
    };
    </script>