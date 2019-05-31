<template>
    <div>
    <ul class="tree" v-if="menu_items" v-for="item in menu_items">
        <li>
            {{item.name}}
            {{item}}
            <br/> <hr/>
            childerns<br/>
            {{item.childrens}}

            <br/> <hr/>
        <tree
                v-if="item.childrens"
                v-for="item in item.childrens"
                :menu_items="item.childrens"
        >
        </tree>
        </li>
    </ul>
    </div>
</template>
<script>
    export default {
        props: [ 'menu_items' ],
        name: 'tree'
    }
</script>