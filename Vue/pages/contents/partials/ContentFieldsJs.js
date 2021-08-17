
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
            group_index: null,
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
                code = "{!! get_field($data, '"+field.slug+"', '"+group.slug+"') !!}";

                if(field.type.slug == 'image')
                {
                    code = "<img src='{{get_field($data, '"+field.slug+"', '"+group.slug+"')'/>";
                }

            } else
            {
                code = "{!! get_field($data, '"+field.slug+"') !!}";

                if(field.type.slug == 'image')
                {
                    code = "<img src='{{get_field($data, '"+field.slug+"')}}'/>";
                }
            }

            copy(code);

            this.$buefy.toast.open({
                message: 'Copied!',
                type: 'is-success'
            });
        },
        //---------------------------------------------------------------------
        getCodeContent: function (field)
        {
            switch(field.slug) {

                default:

            }


        },
        //---------------------------------------------------------------------
        addField: function (field)
        {
            if(typeof field.content === 'string'){
                let content = field.content;

                field.content = [
                    content,
                    ""
                ]
            }else{
                field.content.push('');
            }

        },
        //---------------------------------------------------------------------
        addGroup: function (arr_groups,group)
        {

            let temp_group = group;


            $.each(temp_group.fields, function( index, field ) {

                if(field.type.slug !== "seo-meta-tags"){
                    field.content = "";
                    field.vh_cms_form_field_id = null;
                    field.vh_cms_form_group_index = arr_groups.length;
                }
            });

            arr_groups.push(temp_group);


        },
        //---------------------------------------------------------------------
        removeField: function (field,index)
        {
            if(field.content !== 'string'){

                if(field.content.length === 2 && field.is_repeatable != 1){
                    let val = field.content[0];
                    field.content = null;
                    field.content = val;
                }else{
                    field.content.splice(index, 1);
                }

            }

        },
        //---------------------------------------------------------------------
        removeGroup: function (arr_groups,index)
        {
            arr_groups.splice(index, 1);
        }
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
    }
}
