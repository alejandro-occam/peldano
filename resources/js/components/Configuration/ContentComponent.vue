<template>
    <div class="mb-20">
        <div class="justify-content-center">
            <div class="wizard wizard-custom">
                <div class="wizard-nav">
                    <div class="wizard-steps">
                        <div id="tag_step1" data-wizard-type="step" data-wizard-state="current" class="wizard-step mr-3 cursor-pointer custom-wizard" @click="selectTab($event)">
                            <div class="d-flex align-items-center justify-content-center my-15" >
                                <img class="mr-2" id="img_configuration" width="35" height="30" src="/media/custom-imgs/icono_tab_activo_usuarios.svg"  />
                                <div id="div_text_users" class="text-lightgreen ml-2" >
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
                                    <UsersComponent></UsersComponent>
                                </div>
                            </div>
                        </div>
                        <div class="step" id="step_calendars" data-wizard-type="step-content" data-wizard-state="pending" >
                            <div class="card card-custom shadow-none border-0">
                                <div class="card-body body-tab-step">
                                    <CalendarComponent></CalendarComponent>
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

export default {
    name: "ContentComponent",
    components: {
        UsersComponent,
        CalendarComponent
    },
    data() {
        return {
            publicPath: window.location.origin,
        };
    },
    methods: {
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
                $("#step_banks").attr("data-wizard-state", "pending");
                $("#step_commissions").attr("data-wizard-state", "pending");
                $("#step_ars_quote").attr("data-wizard-state", "pending");

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

                document
                    .getElementById("div_text_users")
                    .classList.add("text-lightgreen");
                document
                    .getElementById("div_text_users")
                    .classList.remove("text-gray");
                document
                    .getElementById("div_text_profile")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_profile")
                    .classList.remove("text-lightgreen");
                document
                    .getElementById("div_text_banks")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_banks")
                    .classList.remove("text-lightgreen");
                document
                    .getElementById("div_text_commissions")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_commissions")
                    .classList.remove("text-lightgreen");
                document
                    .getElementById("div_text_ars_quote")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_ars_quote")
                    .classList.remove("text-lightgreen");

                this.$refs.table_users.listUsers();
            } else if (id_tab == "tag_step2") {
                $("#step_users").attr("data-wizard-state", "pending");
                $("#step_calendars").attr("data-wizard-state", "current");
                $("#step_banks").attr("data-wizard-state", "pending");
                $("#step_commissions").attr("data-wizard-state", "pending");
                $("#step_ars_quote").attr("data-wizard-state", "pending");

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

                document
                    .getElementById("div_text_users")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_users")
                    .classList.remove("text-lightgreen");
                document
                    .getElementById("div_text_profile")
                    .classList.add("text-lightgreen");
                document
                    .getElementById("div_text_profile")
                    .classList.remove("text-gray");
                document
                    .getElementById("div_text_banks")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_banks")
                    .classList.remove("text-lightgreen");
                document
                    .getElementById("div_text_commissions")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_commissions")
                    .classList.remove("text-lightgreen");
                document
                    .getElementById("div_text_ars_quote")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_ars_quote")
                    .classList.remove("text-lightgreen");
            } else if (id_tab == "tag_step3") {
                $("#step_users").attr("data-wizard-state", "pending");
                $("#step_calendars").attr("data-wizard-state", "pending");
                $("#step_banks").attr("data-wizard-state", "current");
                $("#step_commissions").attr("data-wizard-state", "pending");
                $("#step_ars_quote").attr("data-wizard-state", "pending");

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

                document
                    .getElementById("div_text_users")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_users")
                    .classList.remove("text-lightgreen");
                document
                    .getElementById("div_text_profile")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_profile")
                    .classList.remove("text-lightgreen");
                document
                    .getElementById("div_text_banks")
                    .classList.add("text-lightgreen");
                document
                    .getElementById("div_text_banks")
                    .classList.remove("text-gray");
                document
                    .getElementById("div_text_commissions")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_commissions")
                    .classList.remove("text-lightgreen");
                document
                    .getElementById("div_text_ars_quote")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_ars_quote")
                    .classList.remove("text-lightgreen");

                this.getCurrencies();
                this.$refs.table_banks.listBanks();
                this.$refs.modal_add_bank.getCurrencies();

            } else if (id_tab == "tag_step4") {
                $("#step_users").attr("data-wizard-state", "pending");
                $("#step_calendars").attr("data-wizard-state", "pending");
                $("#step_banks").attr("data-wizard-state", "pending");
                $("#step_commissions").attr("data-wizard-state", "current");
                $("#step_ars_quote").attr("data-wizard-state", "pending");

                $("#tag_step1")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/assets/media/custom_img/users_menu_deactivated.svg"
                    );
                $("#tag_step2")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/assets/media/custom_img/profile_tab_deactivated.svg"
                    );
                $("#tag_step3")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/assets/media/custom_img/bank_tab_deactivated.svg"
                    );
                $("#tag_step4")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/assets/media/custom_img/commissions_tab_active.svg"
                    );
                $("#tag_step5")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/assets/media/custom_img/quote_tab_deactivated.svg"
                    );

                document
                    .getElementById("div_text_users")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_users")
                    .classList.remove("text-lightgreen");
                document
                    .getElementById("div_text_profile")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_profile")
                    .classList.remove("text-lightgreen");
                document
                    .getElementById("div_text_banks")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_banks")
                    .classList.remove("text-lightgreen");
                document
                    .getElementById("div_text_commissions")
                    .classList.add("text-lightgreen");
                document
                    .getElementById("div_text_commissions")
                    .classList.remove("text-gray");
                document
                    .getElementById("div_text_ars_quote")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_ars_quote")
                    .classList.remove("text-lightgreen");
            } else if (id_tab == "tag_step5") {
                $("#step_users").attr("data-wizard-state", "pending");
                $("#step_calendars").attr("data-wizard-state", "pending");
                $("#step_banks").attr("data-wizard-state", "pending");
                $("#step_commissions").attr("data-wizard-state", "pending");
                $("#step_ars_quote").attr("data-wizard-state", "current");

                $("#tag_step1")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/assets/media/custom_img/users_menu_deactivated.svg"
                    );
                $("#tag_step2")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/assets/media/custom_img/profile_tab_deactivated.svg"
                    );
                $("#tag_step3")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/assets/media/custom_img/bank_tab_deactivated.svg"
                    );
                $("#tag_step4")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/assets/media/custom_img/commissions_tab_deactivated.svg"
                    );
                $("#tag_step5")
                    .children()
                    .children()
                    .attr(
                        "src",
                        "/assets/media/custom_img/quote_tab_active.svg"
                    );

                document
                    .getElementById("div_text_users")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_users")
                    .classList.remove("text-lightgreen");
                document
                    .getElementById("div_text_profile")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_profile")
                    .classList.remove("text-lightgreen");
                document
                    .getElementById("div_text_banks")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_banks")
                    .classList.remove("text-lightgreen");
                document
                    .getElementById("div_text_commissions")
                    .classList.add("text-gray");
                document
                    .getElementById("div_text_commissions")
                    .classList.remove("text-lightgreen");
                document
                    .getElementById("div_text_ars_quote")
                    .classList.add("text-lightgreen");
                document
                    .getElementById("div_text_ars_quote")
                    .classList.remove("text-gray");
            }
        },
        showBankModal() {
            $("body").addClass("modal-show");
            $("#modal_add_bank").css("display", "block");

            $("#modal_add_bank").modal("show");
        },
    },
    mounted() {
        $("#select_currency").val(0);
    }
};
</script>
