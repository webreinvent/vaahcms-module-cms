<script setup>
import {onMounted, reactive} from "vue";

import navbar from '../components/topnav.vue';

import { useRootStore } from '../stores/root'
const rootStore = useRootStore();


onMounted(async () => {
    await rootStore.getAssets();
});


</script>


<template>
    <div class="pb-3 pt-2"><navbar/></div>
    <div class="grid">
        <div class="col"><RouterView /></div>
    </div>

</template>
