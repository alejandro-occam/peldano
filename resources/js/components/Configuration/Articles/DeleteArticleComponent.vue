<template>
    <div class="modal fade" id="modal_delete_article" tabindex="-1" role="dialog" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal Header -->
            <div class="modal-content">
                <form @submit.prevent="">
                    <div class="modal-header">
                        <h2 class="mx-auto color-blue" id="title_modal">Eliminar artículo</h2>
                        <button type="button" class="close position-absolute" style="right: 22px;" data-dismiss="modal" @click="this.closeModal()">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body text-align-center">
                        <span class="mx-auto font-size-h4" >Estás intentando eliminar el siguiente artículo:</span >
                        <div class="mt-5">
                            <span class="mx-auto font-size-h4 text-dark font-weight-bold" ></span>
                        </div>
                        <div class="mt-10 d-grid">
                            <img class="mx-auto" width=100 src="/media/custom-imgs/icono_ficha_articulo.svg" />
                            <span class="font-weight-bolder mt-4">{{ this.config.articles.article_obj.name }} </span>
                            <span class="font-weight-bolder color-dark-gray"></span>
                        </div>
                        <div class="mt-10 mb-5">
                            <span class="mx-auto font-size-h4 color-blue" >¿Confirmas su eliminación?</span >
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-close ml-auto px-10 color-blue font-weight-bold" data-dismiss="modal" @click="this.closeModal()">
                            Cancelar
                        </button>
                        <button type="submit" class="btn bg-azul color-white px-10 font-weight-bold" @click="this.deleteArticle(config.articles.article_obj.id)">
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
        name: "DeleteArticleComponent",
        data() {
            return {
                publicPath: window.location.origin,
            };
        },
        computed: {
            ...mapState(["config", "errors"]),
        },
        methods: {
            ...mapActions(["deleteArticle"]),
            closeModal(){
                $("#modal_delete_article").modal("hide");
            }
        },
        mounted() {}
    };
    </script>
    