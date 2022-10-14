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
        />
    </div>
</template>

<script>
export default {
    name: "SeachComponent",
    props: ["columns", "model", "placeholder"],
    data() {
        return {
            publicPath: window.location.origin,
            error: null,
        };
    },
};
</script>
