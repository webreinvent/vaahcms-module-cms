<template>


    <b-navbar class="cms-top-menu">

        <template #start v-if="assets" style="margin-left: 20px;" >

            <b-navbar-dropdown v-for="(menu, menu_index) in assets.aside_menu"
                               :key="menu_index"
                               :label="menu.label">
                <b-navbar-item v-if="menu.child" v-for="(item, item_index) in menu.child"
                               :key="item_index"
                               :label="item.label"
                               :icon="item.icon"
                               :href="item.link">
                    {{item.label}}
                </b-navbar-item>
            </b-navbar-dropdown>
        </template>


    </b-navbar>



</template>

<style>
.cms-top-menu > .navbar-brand{
    display: none;
}

.cms-top-menu > .navbar-menu{
    margin-left: 15px;
}

</style>

<script>
export default {
    computed:{
        root() {return this.$store.getters['root/state']},
        assets() {return this.$store.getters['root/state'].assets},
    },
    components:{

    },
    data()
    {
        let obj = {
        };

        return obj;
    },
    watch: {



    },
    mounted() {
        //---------------------------------------------------------------------

    },
    methods: {
        //---------------------------------------------------------------------

        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
    }
}
</script>
