import GlobalComponents from '../../../vaahvue/helpers/GlobalComponents';
import ContentFieldAll from '../../../vaahvue/reusable/content-fields/All'
import TreeView from '../../../vaahvue/reusable/TreeView'
import TreeSelect from '../../../vaahvue/reusable/TreeSelect'

import copy from "copy-to-clipboard";

let namespace = 'contents';

export default {
    props:['groups'],
    computed: {
        root() {return this.$store.getters['root/state']},
        assets() {return this.$store.getters[namespace+'/state'].assets},
        ajax_url() {return this.$store.getters[namespace+'/state'].ajax_url},
    },
    components:{
        ...GlobalComponents,
        TreeView,
        TreeSelect,
        ContentFieldAll,
    },

    data()
    {
        let obj = {
            namespace: namespace,
            is_relation_popup_visible: false,
            taxo_type: {
                parent_id:null,
                name:null
            },
            popup_list: null,
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
        copyCode: function (group, field,group_index = 0,field_index = null)
        {

            let code = "";

            if(field_index == null){
                if(group_index === 0){
                    code = "{!! get_field($data, '"+field.slug+"', '"+group.slug+"') !!}";

                    if(field.type.slug == 'image')
                    {
                        code = "<img src='{{get_field($data, '"+field.slug+"', '"+group.slug+"')'/>";
                    }
                }else{
                    code = "{!! get_field($data, '"+field.slug+"', '"+group.slug+"','content' , "+group_index+") !!}";

                    if(field.type.slug == 'image')
                    {
                        code = "<img src='{{get_field($data, '"+field.slug+"', '"+group.slug+"','content' , "+group_index+")'/>";
                    }
                }

            }else{
                code = "{!! get_field($data, '"+field.slug+"', '"+group.slug+"','content' , "+group_index+", "+field_index+") !!}";

                if(field.type.slug == 'image')
                {
                    code = "<img src='{{get_field($data, '"+field.slug+"', '"+group.slug+"','content' , "+group_index+", "+field_index+")'/>";
                }
            }

            copy(code);

            this.$buefy.toast.open({
                message: 'Copied!',
                type: 'is-success'
            });
        },
        //---------------------------------------------------------------------
        copyGroupCode: function (group,group_index = null)
        {

            let code = "";

            if(group_index == null){
                code = "{!! get_group($data ,'"+group.slug+"' ) !!}";

            }else{
                code = "{!! get_group($data ,'"+group.slug+"' ,'content' ,"+group_index+" ) !!}";

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
                    null
                ]
            }else{
                field.content.push(null);
            }

        },
        //---------------------------------------------------------------------
        addGroup: function (arr_groups,group)
        {

            let temp_group = JSON.parse(JSON.stringify(group));


            $.each(temp_group.fields, function( index, field ) {

                if(field.type.slug !== "seo-meta-tags"){
                    field.content = null;
                    if(field.is_repeatable) field.content = [''];
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

            arr_groups.splice(index, 1);

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
            this.$Progress.finish();
        },
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
        showRelationPopup: function (field) {

            this.is_relation_popup_visible = true;

            this.popup_list = null;

            console.log(field.meta.taxonomy_type);

            let url = this.ajax_url+'/getTaxonomiesInTree';
            let params = {
                q: field.meta.taxonomy_type
            };

            console.log('--->', params);

            this.axios.post(url, params).then((response) => {
                this.popup_list = response.data;


                console.log('--->', response);
            });


        },
        //---------------------------------------------------------------------
    }
}
