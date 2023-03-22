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
    <div class="col-12"><navbar/></div>
    <div class="grid">
        <div class="col"><RouterView /></div>
    </div>

</template>
