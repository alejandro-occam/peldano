<template>
    <div class="mb-20">
        <div class="justify-content-center">
            <div class="card card-custom shadow-none border-0">
                <div class="card-body body-tab-step">
                    <TableComponent></TableComponent>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapMutations, mapState, mapActions } from "vuex";
import AddButtonComponent from "../../../Partials/AddButtonComponent.vue";
import TableComponent from "./TableComponent.vue";


export default {
    name: "Option5ContentComponen",
    components: {
        AddButtonComponent,
        TableComponent
    },
    data() {
        return {
            publicPath: window.location.origin,
        };
    },
    computed: {
            ...mapState(['reports']),
    },
    mounted() {
        this.clearReportsObjetc();
        this.loadTable();
    },
    methods: {
        ...mapActions(["reportUnpaidInvoices"]),
        ...mapMutations(["clearError", "changeViewStatusReports", "clearReportsObjetc"]),
        loadTable(){
            var params = {
                select_consultant: this.select_consultant,
                select_payment: this.select_payment,
                expiration_from: this.expiration_from,
                expiration_to: this.expiration_to,
                num_order: this.num_order,
                contact_id: this.contact_id,
                client_id_sage: this.client_id_sage,
                select_satus_bill: this.select_satus_bill,
            }
            this.reportUnpaidInvoices(params);
        }
    },
    
};
</script>
