<script setup>
import {onMounted, reactive} from "vue";

import Topnav from '../components/Topnav.vue';

import { useRootStore } from '../stores/root'
const rootStore = useRootStore();


onMounted(async () => {
    await rootStore.getAssets();
});


</script>


<template>
    <div class="pb-3 pt-2"><Topnav/></div>
    <RouterView />

</template>
