<template>
    <div class="input-group search-input px-0" :class="columns">
        <div class="input-group-prepend">
            <span class="input-group-text bg-blue-light-white border-radius-left border-0 h-100 px-2" id="search-addon">
                <img width="16" height="16" src="/media/custom-imgs/icono_lupa.svg" />
            </span>
        </div>
        <input
            :id="'search_' + model"
            type="text"
            class="form-control bg-blue-light-white color-green border-0 h-100 search-input pl-1"
            :placeholder="placeholder"
            aria-describedby="search-addon"
            v-model="search_articles"
            v-on:keyup="emitEventChanged"
        />
    </div>
</template>

<script>

import { mapMutations } from "vuex";

export default {
    name: "SeachComponent",
    props: ["columns", "model", "placeholder", "model2"],
    data() {
        return {
            publicPath: window.location.origin,
            error: null,
            search_articles: ''
        };
    },
    methods: {
        ...mapMutations(["saveSearchArticles"]),
        emitEventChanged () {
            this.$emit('CustomEventInputChanged', this.search_articles);
        }
    },
    mounted(){
        /*let me = this;
        $("#search_articles").on("input", function() {
            console.log('hola2');
            me.saveSearchArticles(me.search_articles);
        });*/
    },
    watch: {
        search_articles: {
            handler: async function(val) {
                this.saveSearchArticles(val);
            }
        },
    }
};
</script>
