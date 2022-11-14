<template>
    <button type="button" class="d-flex align-items-center justify-content-center btn color-red px-5 bg-light-red" :class="columns" :id="id">
        <img class="mr-2" :width="width" :height="height" :src="src" />
        <span>{{ text }}</span>
    </button>
</template>

<script>
export default {
    name: "SeachComponent",
    props: ["columns", "text", "id", "src", "width", "height"],
    data() {
        return {
            publicPath: window.location.origin,
            error: null,
        };
    },
};
</script>

<style lang="scss" scoped>
@import 'resources/sass/app';

.btn-gray {
    color: $primary;
    background-color: $gray;
    font-weight: 500;
}
</style>
