<script setup>
import {reactive, ref} from 'vue';
import { useRootStore } from '../stores/root'

import Menubar from 'primevue/menubar';
const root = useRootStore();
const inputs = {
}
const data = reactive(inputs);
const height = ref(window.innerHeight)

const menu = ref();



</script>
<template>

    <div v-if="height && root.assets">
        <Menubar :model="root.assets.aside_menu"
                 class="p-2"
                 :pt="{
                    action: 'p-2'
                 }"/>
    </div>

</template>


