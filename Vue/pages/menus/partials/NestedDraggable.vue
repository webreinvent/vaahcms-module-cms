<template>
    <draggable class="dropzone-nested" tag="ul" :list="items" :group="{ name: 'menu_items' }">
        <li v-for="el in items" :key="el.id">
            <div class="dropzone-field">

                <b-field class="is-marginless" >

                    <p class="control drag">
                        <span class="button is-static">:::</span>
                    </p>

                    <p class="control" v-if="el.content">
                        <span class="button dropzone-field-label is-static">{{el.content.name}}</span>
                    </p>

                    <b-input v-model="el.name" expanded placeholder="Menu Name"></b-input>

                </b-field>

            </div>

            <nested-draggable v-if="el.child" :items="el.child" />
        </li>
    </draggable>
</template>
<script>
    import draggable from "vuedraggable";
    export default {
        props: {
            items: {
                required: true,
                type: Array
            }
        },
        components: {
            draggable
        },
        name: "nested-draggable"
    };
</script>
