
import ContentFieldAll from '../../../vaahvue/reusable/content-fields/All'

import copy from "copy-to-clipboard";

let namespace = 'contents';

export default {
    props:['groups'],
    computed: {
        root() {return this.$store.getters['root/state']},
        ajax_url() {return this.$store.getters[namespace+'/state'].ajax_url},
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
            if(!field.content || typeof field.content === 'string'){
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

            let temp_group = JSON.parse(JSON.stringify(group));


            $.each(temp_group.fields, function( index, field ) {

                if(field.type.slug !== "seo-meta-tags"){
                    field.content = "";
                    field.vh_cms_form_group_index = arr_groups.length;
                    field.vh_cms_form_field_id  = null;
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
        removeGroup: function (arr_groups,group,index)
        {

            // arr_groups.splice(index, 1);


            if(group.fields[0].vh_cms_form_field_id){
                this.$Progress.start();
                let url = this.ajax_url+'/actions/remove-group';
                let params = {
                    inputs: {
                        index: index,
                        group_id: group.fields[0].vh_cms_form_group_id,
                        content_id: this.$route.params.id
                    },
                };
                this.$vaah.ajax(url, params, this.removeGroupAfter);
            }

        },
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
        removeGroupAfter: function (data,res) {
            this.$emit('eReloadList');
            this.$Progress.finish();

        },
        //---------------------------------------------------------------------
    }
}
