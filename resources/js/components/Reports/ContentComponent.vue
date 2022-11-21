<template>
    <div class="mb-20">
        <div class="justify-content-center">
            <div class="d-flex mb-10 ">
                <h1 class="color-blue my-auto">Informes</h1>
                <div class="subheader-separator subheader-separator-ver my-auto mx-6" style="background-color: #c1c1cd;" v-if="reports.status_view != 1"></div>
                <span class="my-auto font-weight-bold color-dark-gray" v-if="reports.status_view == 2">Nueva propuesta</span>
                <span class="my-auto font-weight-bold color-dark-gray" v-if="reports.status_view == 3">Ofrecer propuesta</span>
            </div>
            <div class="card card-custom shadow-none border-0">
                <div class="card-body body-tab-step">
                    <div class="card card-custom shadow-none border-0">
                        <div class="card-body body-tab-step p-2">
                            <h3 class="color-blue my-auto">Informes Comercial</h3>
                            <div class="row my-10">
                                <div class="col-6">
                                    <div class="p-2 d-flex mb-10 border-radius-10 bg-tarjet-reports bg-tarjet-reports selected">
                                        <span class="my-5 mx-2 f-14">Informes de contratación por canal ( Digital / Print / Experiencias ) y producto.</span>
                                        <img class="ml-auto mr-6 arrow" width="15" src=""/>
                                    </div>
                                    <div class="p-2 d-flex my-10 border-radius-10 bg-tarjet-reports">
                                        <span class="my-5 mx-2 f-14">Informe comisiones consultores.</span>
                                        <img class="ml-auto mr-6 arrow" width="15" src=""/>
                                    </div>
                                    <div class="p-2 d-flex my-10 border-radius-10 bg-tarjet-reports">
                                        <span class="my-5 mx-2 f-14">Informe de facturas impagadas.</span>
                                        <img class="ml-auto mr-6 arrow" width="15" src=""/>
                                    </div>
                                    <div class="p-2 d-flex mt-10 border-radius-10 bg-tarjet-reports">
                                        <span class="my-5 mx-2 f-14">Informe publicado.</span>
                                        <img class="ml-auto mr-6 arrow" width="15" src=""/>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 d-flex mb-10 border-radius-10 bg-tarjet-reports">
                                        <span class="my-5 mx-2 f-14">Informes de margen de tipo manual ( Inserciones ).</span>
                                        <img class="ml-auto mr-6 arrow" width="15" src=""/>
                                    </div>
                                    <div class="p-2 d-flex my-10 border-radius-10 bg-tarjet-reports">
                                        <span class="my-5 mx-2 f-14">Informe de órdenes firmadas y ventas.</span>
                                        <img class="ml-auto mr-6 arrow" width="15" src=""/>
                                    </div>
                                    <div class="p-2 d-flex my-10 border-radius-10 bg-tarjet-reports">
                                        <span class="my-5 mx-2 f-14">Informe facturado.</span>
                                        <img class="ml-auto mr-6 arrow" width="15" src=""/>
                                    </div>
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
import { mapMutations, mapState } from "vuex";

export default {
    name: "ContentComponent",
    components: {
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
        ...mapMutations(["clearError"]),
        
    },
};
</script>
