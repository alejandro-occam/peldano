<template>
    <div class="mb-20">
        <div class="justify-content-center">
            <div class="d-flex mb-10 ">
                <h1 class="color-blue my-auto">Informes</h1>
                <div class="subheader-separator subheader-separator-ver my-auto mx-6" style="background-color: #c1c1cd;" v-if="reports.status_view != 1"></div>
                <span class="my-auto font-weight-bold color-dark-gray" v-if="reports.status_view == 2">Informes de contratación por canal ( Digital / Print / Experiencias ) y producto.</span>
                <span class="my-auto font-weight-bold color-dark-gray" v-if="reports.status_view == 4">Informes de comisiones del consultor.</span>
                <span class="my-auto font-weight-bold color-dark-gray" v-if="reports.status_view == 6">Informe de facturas impagadas.</span>
                <span class="my-auto font-weight-bold color-dark-gray" v-if="reports.status_view == 8">Informe publicado.</span>
                <span class="my-auto font-weight-bold color-dark-gray" v-if="reports.status_view == 3">Informes de margen de tipo manual ( Inserciones ).</span>
                <span class="my-auto font-weight-bold color-dark-gray" v-if="reports.status_view == 5">Informe de órdenes firmadas y ventas.</span>
                <span class="my-auto font-weight-bold color-dark-gray" v-if="reports.status_view == 7">Informe facturado.</span>
            </div>
            <div class="card card-custom shadow-none border-0" v-if="reports.status_view == 1">
                <div class="card-body body-tab-step">
                    <div class="card card-custom shadow-none border-0">
                        <div class="card-body body-tab-step p-2">
                            <h3 class="color-blue my-auto">Informes Comercial</h3>
                            <div class="row my-10">
                                <div class="col-6">
                                    <div class="p-2 d-flex mb-10 border-radius-10 bg-tarjet-reports bg-tarjet-reports" v-on:click="changeViewStatusReports(2)">
                                        <span class="my-5 mx-2 f-14">Informes de contratación por canal ( Digital / Print / Experiencias ) y producto.</span>
                                        <img class="ml-auto mr-6 arrow" width="15" src=""/>
                                    </div>
                                    <div class="p-2 d-flex my-10 border-radius-10 bg-tarjet-reports" v-on:click="changeViewStatusReports(4)">
                                        <span class="my-5 mx-2 f-14">Informe comisiones consultores.</span>
                                        <img class="ml-auto mr-6 arrow" width="15" src=""/>
                                    </div>
                                    <div class="p-2 d-flex my-10 border-radius-10 bg-tarjet-reports" v-on:click="changeViewStatusReports(6)">
                                        <span class="my-5 mx-2 f-14">Informe de facturas impagadas.</span>
                                        <img class="ml-auto mr-6 arrow" width="15" src=""/>
                                    </div>
                                    <div class="p-2 d-flex mt-10 border-radius-10 bg-tarjet-reports" v-on:click="changeViewStatusReports(8)">
                                        <span class="my-5 mx-2 f-14">Informe publicado.</span>
                                        <img class="ml-auto mr-6 arrow" width="15" src=""/>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 d-flex mb-10 border-radius-10 bg-tarjet-reports" v-on:click="changeViewStatusReports(3)">
                                        <span class="my-5 mx-2 f-14">Informes de margen de tipo manual ( Inserciones ).</span>
                                        <img class="ml-auto mr-6 arrow" width="15" src=""/>
                                    </div>
                                    <div class="p-2 d-flex my-10 border-radius-10 bg-tarjet-reports" v-on:click="changeViewStatusReports(5)">
                                        <span class="my-5 mx-2 f-14">Informe de órdenes firmadas y ventas.</span>
                                        <img class="ml-auto mr-6 arrow" width="15" src=""/>
                                    </div>
                                    <div class="p-2 d-flex my-10 border-radius-10 bg-tarjet-reports" v-on:click="changeViewStatusReports(7)">
                                        <span class="my-5 mx-2 f-14">Informe facturado.</span>
                                        <img class="ml-auto mr-6 arrow" width="15" src=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <Option1 v-if="reports.status_view == 2"></Option1>
            <Option2 v-if="reports.status_view == 3"></Option2>
            <Option3 v-if="reports.status_view == 4"></Option3>
            <Option4 v-if="reports.status_view == 5"></Option4>
            <Option5 v-if="reports.status_view == 6"></Option5>
            <Option6 v-if="reports.status_view == 7"></Option6>
        </div>
    </div>
</template>

<script>
import { mapMutations, mapState } from "vuex";

import Option1 from "./Options/Options1/ContentComponent.vue";
import Option2 from "./Options/Options2/ContentComponent.vue";
import Option3 from "./Options/Options3/ContentComponent.vue";
import Option4 from "./Options/Options4/ContentComponent.vue";
import Option5 from "./Options/Options5/ContentComponent.vue";
import Option6 from "./Options/Options6/ContentComponent.vue";

export default {
    name: "ContentComponent",
    components: {
        Option1,
        Option2,
        Option3,
        Option4,
        Option5,
        Option6
    },
    data() {
        return {
            publicPath: window.location.origin,
        };
    },
    computed: {
            ...mapState(['reports']),
    },
    methods: {
        ...mapMutations(["clearError", "changeViewStatusReports"]),
    },
};
</script>
