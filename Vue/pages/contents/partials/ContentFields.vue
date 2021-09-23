<script src="./ContentFieldsJs.js"></script>
<template>
    <div class="block is-marginless">

        <b-collapse :open="true" aria-id="site_settings">

            <div class="level has-padding-top-10 has-padding-bottom-10" slot="trigger"
                 slot-scope="props"
                 aria-controls="site_settings">

                <div class="label-left">
                    <h4 class="title is-5">Content Fields</h4>
                    <h2 class="subtitle is-6">These fields can be managed
                        from "Content Types" sections.</h2>
                </div>
                <div class="label-right is-hidden-mobile">
                    <b-button v-text="props.open ? 'Collapse' : 'Expand'">
                    </b-button>
                </div>

            </div>


            <div class="block has-margin-top-10">

                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="block">

                            <div v-if="groups.length > 0"
                                 v-for="(arr_groups,g_index) in groups"
                                 class="has-border-bottom-width-1"
                                 :key="'content-fields-group-'+g_index"
                                 :aria-id="'content-fields-group-'+g_index">

                                <div  v-for="(group,index) in arr_groups">

                                    <nav class="level">
                                        <!-- Left side -->
                                        <div class="level-left">
                                            <div class="level-item">
                                                <h4 v-if="index === 0"
                                                    class="title is-5">
                                                    {{group.name}}
                                                </h4>
                                            </div>
                                        </div>
                                        <!-- Right side -->
                                        <div class="right">
                                            <div  v-if="index === 0" class="level-item">
                                                <b-button v-if="arr_groups.length > 1"
                                                          @click="copyGroupCode(group,index)"
                                                          icon-left="file">
                                                </b-button>
                                                <b-button @click="copyGroupCode(group)"
                                                          icon-left="copy">
                                                </b-button>
                                            </div>
                                        </div>
                                    </nav>



                                    <div class="card mb-3">

                                        <header v-if="index > 0" class="card-header">
                                            <div class="card-header-title">
                                                {{group.name}}
                                            </div>

                                                <b-button type="is-text"
                                                          class="card-header-icon has-margin-top-5 has-margin-right-5"
                                                          @click="copyGroupCode(group,index)"
                                                          icon-left="file">
                                                </b-button>

                                                <b-button type="is-text"
                                                          dusk="action-download"
                                                          class="card-header-icon has-margin-top-5 has-margin-right-5"
                                                          @click="removeGroup(arr_groups,group,index)"
                                                          icon-left="times">

                                                </b-button>

                                        </header>

                                        <div class="card-content p-4">

                                            <div class="block has-margin-top-10 has-padding-bottom-10"
                                                 v-if="group.fields.length>0"
                                                 v-for="(field, f_index) in group.fields"
                                                 :class="'field-type field-'+field.type.slug"
                                                 :key="f_index">

                                                <div v-if="!field.content || typeof field.content === 'string'
                                                    || assets.non_repeatable_fields.includes(field.type.slug)"
                                                     class="columns is-gapless">
                                                    <div class="column" >
                                                        <ContentFieldAll :field_type="field.type"
                                                                         :field_slug="field.type.slug"
                                                                         :label="field.name"
                                                                         :meta="field.meta"
                                                                         :placeholder="field.name"
                                                                         :app_url="field.type.slug === 'relation'
                                                                         ? ajax_url+'/getRelationsInTree' : ''"
                                                                         :labelPosition="labelPosition"
                                                                         v-model="field.content"
                                                                         @onInput=""
                                                                         @onChange=""
                                                                         @onBlur=""
                                                                         @onFocus="">
                                                        </ContentFieldAll>

                                                    </div>
                                                    <div class="column is-1" >
                                                        <b-button icon-left="copy"
                                                                  @click="copyCode(group, field,index)">
                                                        </b-button>
                                                    </div>
                                                </div>
                                                <div v-else v-for="(content,key) in field.content"
                                                     class="columns mb-3 is-gapless">
                                                    <div class="column"  >
                                                        <ContentFieldAll :field_type="field.type"
                                                                         :field_slug="field.type.slug"
                                                                         :label="key === 0
                                                                         || field.type.slug === 'image'
                                                                         || field.type.slug === 'media'
                                                                         ? field.name : ''"
                                                                         :meta="field.meta"
                                                                         :app_url="field.type.slug === 'relation'
                                                                         ? ajax_url+'/getRelationsInTree' : ''"
                                                                         :placeholder="field.name"
                                                                         :labelPosition="labelPosition"
                                                                         v-model="field.content[key]"
                                                                         @onInput=""
                                                                         @onChange=""
                                                                         @onBlur=""
                                                                         @onFocus="">
                                                        </ContentFieldAll>
                                                    </div>
                                                    <div v-if="key === 0" class="column is-2"
                                                         style="width: 14.6%">
                                                        <b-button icon-left="file"
                                                                  @click="copyCode(group, field,index,key)">
                                                        </b-button>
                                                        <b-button icon-left="copy"
                                                                  @click="copyCode(group, field,index)">
                                                        </b-button>
                                                    </div>
                                                    <div v-else class="column is-2"
                                                         style="width: 14.6%">
                                                        <b-button icon-left="file"
                                                                  @click="copyCode(group, field,index,key)">
                                                        </b-button>
                                                        <b-button type="is-danger" icon-left="minus"
                                                                  @click="removeField(field,key)">
                                                        </b-button>
                                                    </div>
                                                </div>

                                                <div v-if="field.is_repeatable && !assets.non_repeatable_fields.includes(field.type.slug)" class="columns is-centered">
                                                    <div class="column is-2">
                                                        <b-button type="is-small" icon-left="plus"
                                                                  @click="addField(field)">
                                                            Add Field
                                                        </b-button>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div v-if="group.is_repeatable
                                    && arr_groups.length - 1 === index"
                                         class="columns is-centered my-3">
                                        <div class="column is-2">
                                            <b-button type="is-small" icon-left="plus"
                                                      @click="addGroup(arr_groups,group)">
                                                Add Group
                                            </b-button>
                                        </div>
                                    </div>
                                </div>



                            </div>

                        </div>

                    </div>



                </div>

            </div>

        </b-collapse>

        <hr class="is-marginless"/>

    </div>


</template>

