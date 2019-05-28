<template>
    <div class="vaah-vue-select" v-if="options">

        {{options}}


    </div>
</template>

<script>
    export default {
        options: {},
        value: null,
        classes: "",
        option_key: "id",
        option_value: "label",
    }
</script>