<template>
    <div class="mb-20">
        <div class="justify-content-center">
            <div class="wizard wizard-custom">
                <div class="wizard-nav">
                    <div class="wizard-steps">
                        <div id="tag_step1" data-wizard-type="step" data-wizard-state="current" class="wizard-step mr-3 cursor-pointer custom-wizard" @click="selectTab($event)">
                            <div class="d-flex align-items-center justify-content-center my-15" >
                                <img class="mr-2" id="img_configuration" width="35" height="30" src="/media/custom-imgs/icono_tab_activo_usuarios.svg"  />
                                <div id="div_text_users" class="text-gray ml-2" >
                                    <h3 class="mb-0">Usuarios y roles</h3>
                                </div>
                            </div>
                        </div>

                        <div id="tag_step2" data-wizard-type="step" data-wizard-state="pending" class="wizard-step mx-3 cursor-pointer custom-wizard" @click="selectTab($event)" >
                            <div class="d-flex align-items-center justify-content-center my-15" >
                                <img class="mr-2" width="35" height="30" src="/media/custom-imgs/icono_tab_desactivo_calendarios.svg" />
                                <div id="div_text_calendar" class="text-gray ml-2" >
                                    <h3 class="mb-0">Calendarios</h3>
                                </div>
                            </div>
                        </div>

                        <div id="tag_step3" data-wizard-type="step" data-wizard-state="pending" class="wizard-step ml-3 cursor-pointer custom-wizard" @click="selectTab($event)" >
                            <div class="d-flex align-items-center justify-content-center my-15" >
                                <img class="mr-2" width="35" height="30" src="/media/custom-imgs/icono_tab_desactivo_articulos.svg" />
                                <div id="div_text_banks" class="text-gray ml-2">
                                    <h3 class="mb-0">Artículos</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-custom card-shadowless rounded-top-0 div-content-tabs bg-transparent" >
                    <div class="card-body pl-0 pt-0">
                        <div class="step" id="step_users" data-wizard-type="step-content" data-wizard-state="current" >
                            <div class="card card-custom shadow-none border-0">
                                <div class="card-body body-tab-step">
                                    <UsersComponent ref="table_users"></UsersComponent>
                                </div>
                            </div>
                        </div>
                        <div class="step" id="step_calendars" data-wizard-type="step-content" data-wizard-state="pending" >
                            <div class="card card-custom shadow-none border-0">
                                <div class="card-body body-tab-step">
                                    <CalendarComponent ref="table_calendars"></CalendarComponent>
                                </div>
                            </div>
                        </div>
                        <div class="step" id="step_articles" data-wizard-type="step-content" data-wizard-state="pending" >
                            <div class="card card-custom shadow-none border-0">
                                <div class="card-body body-tab-step">
                                    <ArticlesComponent ref="table_articles"></ArticlesComponent>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
//Users

import UsersComponent from "./Users/ContentComponent.vue";
import CalendarComponent from "./Calendar/ContentComponent.vue";
import ArticlesComponent from "./Articles/ContentComponent.vue";

import { mapMutations, mapState } from "vuex";

export default {
    name: "ContentComponent",
    components: {
        UsersComponent,
        CalendarComponent,
        ArticlesComponent
    },
    data() {
        return {
            publicPath: window.location.origin,
        };
    },
    computed: {
            ...mapState(["errors"]),
    },
    methods: {
        ...mapMutations(["clearError", "changeShowView"]),
        selectTab(event) {
            $("#tag_step1").attr("data-wizard-state", "pending");
            $("#tag_step2").attr("data-wizard-state", "pending");
            $("#tag_step3").attr("data-wizard-state", "pending");
            $("#tag_step4").attr("data-wizard-state", "pending");
            $("#tag_step5").attr("data-wizard-state", "pending");
            $(event.currentTarget).attr("data-wizard-state", "current");

            this.id_tab = $(event.currentTarget).attr("id");
            this.navegationTabs(this.id_tab);
        },
        navegationTabs(id_tab) {
            if (id_tab == "tag_step1") {
                $("#step_users").attr("data-wizard-state", "current");
                $("#step_calendars").attr("data-wizard-state", "pending");
                $("#step_articles").attr("data-wizard-state", "pending");;

                $("#tag_step1")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/media/custom-imgs/icono_tab_activo_usuarios.svg"
                    );
                $("#tag_step2")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/media/custom-imgs/icono_tab_desactivo_calendarios.svg"
                    );
                $("#tag_step3")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/media/custom-imgs/icono_tab_desactivo_articulos.svg"
                    );

                this.$refs.table_users.listUsers();

            } else if (id_tab == "tag_step2") {
                $("#step_users").attr("data-wizard-state", "pending");
                $("#step_calendars").attr("data-wizard-state", "current");
                $("#step_articles").attr("data-wizard-state", "pending");
                $("#tag_step1")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/media/custom-imgs/icono_tab_desactivo_usuarios.svg"
                    );
                $("#tag_step2")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/media/custom-imgs/icono_tab_activo_calendarios.svg"
                    );
                $("#tag_step3")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/media/custom-imgs/icono_tab_desactivo_articulos.svg"
                    );

                this.$refs.table_calendars.listCalendars();

            } else if (id_tab == "tag_step3") {
                $("#step_users").attr("data-wizard-state", "pending");
                $("#step_calendars").attr("data-wizard-state", "pending");
                $("#step_articles").attr("data-wizard-state", "current");

                $("#tag_step1")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/media/custom-imgs/icono_tab_desactivo_usuarios.svg"
                    );
                $("#tag_step2")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/media/custom-imgs/icono_tab_desactivo_calendarios.svg"
                    );
                $("#tag_step3")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/media/custom-imgs/icono_tab_activo_articulos.svg"
                    );
                this.$refs.table_articles.listArticles(1);
            } 
        },
    },
    watch: {
            '$store.state.errors.code': function() {
                if(this.errors.type_error == 'delete_user'){
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
                }else if(this.errors.type_error == 'add_user'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            swal("", "Usuario creado correctamente", "success");
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
                }else if(this.errors.type_error == 'update_user'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            swal("", "Usuario modificado correctamente", "success");
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
                }else if(this.errors.type_error == 'delete_calendar'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            $("#list_calendars").KTDatatable("reload");
                            $("#modal_form_number_calendar").modal("hide");
                            swal("", "Calendario eliminado correctamente", "success");
                        }else if(this.errors.code == 1001 || this.errors.code == 1002){
                            swal("", "Rellena todos los datos", "warning");
                        }else{
                            swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                        }
                        this.clearError();
                    }

                }else if(this.errors.type_error == 'add_calendar'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            $("#list_calendars").KTDatatable("reload");
                            swal("", "Calendario creado correctamente", "success");
                            $("#modal_form_number_calendar").modal("hide");
                        }else if(this.errors.code == 1001 || this.errors.code == 1002){
                            swal("", "Rellena todos los datos", "warning");
                        }else{
                            swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                        }
                        this.clearError();
                    }
                
                }else if(this.errors.type_error == 'update_calendar'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            $("#list_calendars").KTDatatable("reload");
                            swal("", "Calendario modificado correctamente", "success");
                            $("#modal_form_number_calendar").modal("hide");
                        }else if(this.errors.code == 1001 || this.errors.code == 1002){
                            swal("", "Rellena todos los datos", "warning");
                        }else{
                            swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                        }
                        this.clearError();
                    }
                }else if(this.errors.type_error == 'add_article'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            $("#list_articles").KTDatatable("reload");
                            swal("", "Artículo creado correctamente", "success");
                            $("#modal_form_article").modal("hide");
                        }else if(this.errors.code == 1001 || this.errors.code == 1002){
                            swal("", "Rellena todos los datos", "warning");
                        }else if(this.errors.code == 1003){
                            swal("", "El producto no existe", "warning");
                        }else if(this.errors.code == 1004){
                            swal("", "Ya hay un artículo con este nombre y estas categorías", "warning");
                        }else{
                            swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                        }
                        this.clearError();
                    }
                }else if(this.errors.type_error == 'delete_article'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            $("#list_articles").KTDatatable("reload");
                            $("#modal_delete_article").modal("hide");
                            swal("", "Articulo eliminado correctamente", "success");
                        }else if(this.errors.code == 1001){
                            swal("", "Este artículo pertence a alguna propuesto u orden", "warning");
                        }else{
                            swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                        }
                        this.clearError();
                    }

                }else if(this.errors.type_error == 'update_article'){
                    if(this.errors.code != ''){
                        if(this.errors.code == 1000){
                            $("#list_articles").KTDatatable("reload");
                            swal("", "Artículo modificado correctamente", "success");
                            $("#modal_form_article").modal("hide");
                        }else if(this.errors.code == 1001 || this.errors.code == 1002){
                            swal("", "Rellena todos los datos", "warning");
                        }else{
                            swal("", "Parece que ha habido un error, inténtelo de nuevo más tarde", "error");
                        }
                        this.clearError();
                    }
                }
            }
        }
    
};
</script>
