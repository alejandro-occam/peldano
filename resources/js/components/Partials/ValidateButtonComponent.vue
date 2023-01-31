<template>
    <button type="button" class="d-flex align-items-center justify-content-center btn btn-gray px-5" :class="columns" :id="id">
        <span>{{ text }}</span>
    </button>
</template>

<script>
export default {
    props: ["columns", "text", "id", "width", "height"],
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
