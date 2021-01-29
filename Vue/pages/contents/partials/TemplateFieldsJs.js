
import ContentFieldAll from '../../../vaahvue/reusable/content-fields/All'
import copy from "copy-to-clipboard";

let namespace = 'contents';

export default {
    props:['groups'],
    computed: {
        root() {return this.$store.getters['root/state']},
    },
    components:{
        ContentFieldAll,
    },

    data()
    {
        let obj = {
            namespace: namespace,
            labelPosition: 'on-border',
        };

        return obj;
    },
    created() {
    },
    mounted(){

    },

    watch: {

    },
    methods: {
        //---------------------------------------------------------------------
        update: function(name, value)
        {
            let update = {
                state_name: name,
                state_value: value,
                namespace: this.namespace,
            };
            this.$vaah.updateState(update);
        },
        //---------------------------------------------------------------------
        copyCode: function (group, field)
        {
            let code = "";
            if(group.slug != 'default')
            {
                code = "{!! get_field($data, '"+field.slug+"', '"+group.slug+"', 'template') !!}";
            } else
            {
                code = "{!! get_field($data, '"+field.slug+"', 'default', 'template') !!}";
            }

            copy(code);

            this.$buefy.toast.open({
                message: 'Copied!',
                type: 'is-success'
            });
        },
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
    }
}
