<script src="../../../../../../../../vaahcms-ready-test/vaahcms/Modules/Cms/Resources/assets/vue/app-menus.js"></script>
<template>
    <div>
        <ul class="tree" v-if="menu_item">
            <li>
                {{menu_item.name}}
                <tree
                        v-if="menu_item.childrens"
                        v-for="item in menu_item.childrens"
                        :menu_item="item"
                        :key="index"
                >
                </tree>
            </li>
        </ul>
    </div>
</template>
<script>
    export default {
        props: [ 'menu_item' ],
        name: 'tree'
    }
</script>