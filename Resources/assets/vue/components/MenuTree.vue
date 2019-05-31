<template>
    <div>
        <ul class="tree" v-if="menu_item">
            <li>
                {{menu_item.name}}
                <menutree
                        v-if="menu_item.childrens"
                        v-for="item in menu_item.childrens"
                        :menu_item="item"
                        :key="index"
                >
                </menutree>
            </li>
        </ul>
    </div>
</template>
<script>
    export default {
        props: [ 'menu_item' ],
        name: 'menutree'
    }
</script>