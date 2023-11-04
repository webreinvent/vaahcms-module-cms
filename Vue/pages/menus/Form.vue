<script setup>
import {onMounted, ref, watch} from "vue";
import { useMenuStore } from '../../stores/store-menus'
import draggable from 'vuedraggable'

import VhField from './../../vaahvue/vue-three/primeflex/VhField.vue'
import NestedDraggable from './components/NestedDraggable.vue'
import {useRoute} from 'vue-router';


const store = useMenuStore();
const route = useRoute();

onMounted(async () => {

    if(route.params && route.params.id)
    {
        await store.getItem(route.params.id);
    }

});

//--------form_menu
const form_menu = ref();
const toggleFormMenu = (event) => {
    form_menu.value.toggle(event);
};
//--------/form_menu

</script>
<template>


</template>
