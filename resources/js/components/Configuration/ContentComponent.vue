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
                                <div id="div_text_profile" class="text-gray ml-2" >
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

                <div class="card card-custom card-shadowless rounded-top-0 div-content-tabs" >
                    <div class="card-body">
                        <div class="step" id="step_users" data-wizard-type="step-content" data-wizard-state="current" >
                            <div class="card card-custom shadow-none border-0">
                                <div class="card-body body-tab-step">
                                    <UsersComponent></UsersComponent>
                                </div>
                            </div>
                        </div>

                        <div class="step" id="step_profile" data-wizard-type="step-content" data-wizard-state="pending" >
                            <div class="card card-custom shadow-none border-0">
                                <div class="card-body body-tab-step">
                                </div>
                            </div>
                        </div>

                        <div class="step" id="step_banks" data-wizard-type="step-content" data-wizard-state="pending" >
                            <div class="card-header border-0">
                                <div class="w-100 d-flex justify-content-start align-items-center my-2" >
                                    <div class="input-group search-input col-md-3" >
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-gray border-radius-left border-0 h-100" id="search-addon" >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#B3B3B3"  class="bi bi-search" viewBox="0 0 16 16" >
                                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <input v-model="search_beneficiary" id="search_beneficiary" type="text" class="form-control bg-gray color-green border-0 h-100 search-input" placeholder="Buscar bancos" aria-describedby="search-addon" />
                                    </div>

                                    <div class="d-flex align-items-center col-md-2" style="gap: 5px" >
                                        <select class="form-control w-100 bg-gray text-dark select-custom" :name="'select_currency'"  :id="'select_currency'" v-model="select_currency" data-style="select-lightgreen" >
                                            <option value="0" selected>
                                                Todas las monedas
                                            </option>

                                            <option :value="currency.id"  v-for="currency in array_currencies" :key="currency.id" v-text="currency.name" ></option>
                                        </select>
                                    </div>

                                    <button type="button" class="btn btn-yellow font-weight-bold mx-4 ml-auto" @click="showBankModal()" >
                                        <img class="mr-2"  width="30" src="/assets/media/custom_img/add_bank_account.svg" />Añadir cuenta
                                    </button>
                                </div>
                            </div>

                            <div class="card card-custom shadow-none border-0">
                                <div class="card-body body-tab-step">
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


/*import UserTableComponent from "./Users/TableComponent.vue";

import InfoUserProfile from "./Profile/InfoUserProfile.vue";
import FormChangePasswordComponent from "./Profile/FormChangePasswordComponent.vue";

//Banks
import BanksTableComponent from "./Banks/BanksTableComponent.vue";
import AddBankComponent from "./Banks/AddBankComponent.vue";
import DeleteBankComponent from "./Banks/DeleteBankComponent.vue";

//Commission
import CommissionsComponent from "./Commissions/CommissionsComponent.vue";

//ARS Quote
import ArsQuoteComponent from "./ArsQuote/ArsQuoteComponent.vue";*/

export default {
    name: "ContentComponent",
    components: {
        UsersComponent
        /*UserTableComponent,
        InfoUserProfile,
        FormChangePasswordComponent,
        BanksTableComponent,
        AddBankComponent,
        DeleteBankComponent,
        CommissionsComponent,
        ArsQuoteComponent,*/
    },
    data() {
        return {
            publicPath: window.location.origin,
            validation: "",
            array_currencies: "",
            select_currency: 0,
            me_configuration: "",
            search_beneficiary: "",
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
                $("#step_profile").attr("data-wizard-state", "pending");
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
                $("#step_profile").attr("data-wizard-state", "current");
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
                $("#step_profile").attr("data-wizard-state", "pending");
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
                this.me_configuration = this;
            } else if (id_tab == "tag_step4") {
                $("#step_users").attr("data-wizard-state", "pending");
                $("#step_profile").attr("data-wizard-state", "pending");
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
                $("#step_profile").attr("data-wizard-state", "pending");
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
        getCurrencies() {
            axios
                .get("/admin/configuration/get_currencies")
                .then((response) => {
                    if (response.data.code == 1000) {
                        this.array_currencies = response.data.array_currencies;
                    } else {
                        swal(
                            "",
                            "Parece que ha habido un error, inténtalo de nuevo más tarde",
                            "warning"
                        );
                    }
                })
                .catch(function (error) {
                    swal(
                        "",
                        "Parece que ha habido un error, inténtalo de nuevo más tarde",
                        "error"
                    );
                });
        },
        showBankModal() {
            $("body").addClass("modal-show");
            $("#modal_add_bank").css("display", "block");

            $("#modal_add_bank").modal("show");
        },
    },
    mounted() {
        $("#select_currency").val(0);
    },
    updated() {
        this.$refs.table_banks.listBanks(this.select_currency);
    },
    watch: {
        select_currency() {
            setTimeout(() => {
                this.$refs.table_banks.listBanks(this.select_currency);
            }, 10);
        },
        search_beneficiary() {
            setTimeout(() => {
                this.$refs.table_banks.listBanks(this.select_currency);
            }, 10);
        },
    },
};
</script>
