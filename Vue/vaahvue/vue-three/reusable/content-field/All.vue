<script setup>
import {reactive, ref, watch } from 'vue';
import {vaah} from '../../../pinia/vaah'
import VhField from './../../primeflex/VhField.vue'
// import VvAutoCompleteUsers from "./VvAutoCompleteUsers.vue";
import VvAddress from "./VvAddress.vue";
// import VvBoolean from "./VvBoolean.vue";
// import VvCurrencyCode from "./VvCurrencyCode.vue";
// import VvDate from "./VvDate.vue";
// import VvDateTime from "./VvDateTime.vue";
// import VvEditor from "./VvEditor.vue";
import VvJoditEditor from "./VvJoditEditor.vue";
import VvEmail from "./VvEmail.vue";
// import VvFacebookCard from "./VvFacebookCard.vue";
// import VvImage from './VvImage.vue';
// import VvImageGroup from "./VvImageGroup.vue";
// import VvJson from "./VvJson.vue";
// import VvList from "./VvList.vue";
// import VvMarkdown from "./VvMarkdown.vue";
// import VvMedia from "./VvMedia.vue";
import VvNumber from "./VvNumber.vue";
import VvPassword from "./VvPassword.vue";
// import VvPhoneNumber from "./VvPhoneNumber.vue";
// import VvRelation from "./VvRelation.vue";
// import VvSeoMetaTags from "./VvSeoMetaTags.vue";
// import VvSlug from "./VvSlug.vue";
// import VvTags from "./VvTags.vue";
import VvText from "./VvText.vue";
// import VvSelect from "./VvSelect.vue";
// import VvTreeSelect from "./VvTreeSelect.vue";
import VvTextarea from "./VvTextarea.vue";
// import VvTime from "./VvTime.vue";
// import VvTitle from "./VvTitle.vue";
// import VvTwitterCard from "./VvTwitterCard.vue";
// import VvUuid from "./VvUuid.vue";
// import VvVisualEditor from "./VvVisualEditor.vue";
// import VvCodeMirror from "./VvCodeMirror.vue";


const props = defineProps({
        field_type: {
            type: Object,
        default:
            null,
        },
        field_slug: {
            type: String,
        default:
            null,
        },
        value: {
            type: String || Number || Array || Object,
            default:null
        },
        type: {
            type: String,
        default:
            null,
        },
        size: {
            type: String,
        default:
            null,
        },
        custom_class: {
            type: String,
        default:
            null,
        },
        label: {
            type: String,
        default:
            null,
        },
        labelPosition: {
            type: String,
        default:
            null,
        },
        placeholder: {
            type: String,
        default:
            null,
        },
        app_url:{
            type: String,
        default:
            null
        },
        upload_url: {
            type: String,
        default:
            null
        },
        currency_options:{
            type: Array,
        default:
            null
        },
        meta:{
            type: Array || Object,
            default:
            null
        },
        ajax_url:{
            type: String,
        default:
            null
        },
        display_column:{
            type: String,
        default:
            'name'
        },
        unique_column:{
            type: String,
        default:
            'email'
        },
        is_simple: {
            type: Boolean,
        default:
            false,
        },
    });
function emitOnInput (data) {
    this.$emit('input', data);
};

function emitOnChange (data) {
    this.$emit('onChange', data);
};

function emitOnBlur (data) {
    this.$emit('onBlur', data);
};

function emitOnFocus (data) {
    this.$emit('onFocus', data);
};
</script>
<template>
    <div v-if="field_slug">

        <template v-if="field_slug === 'text' || field_slug === 'slug'
        || field_slug === 'title'
        ">
            <vv-text :content="value"
                     :custom_class="custom_class"
                     :label="label"
                     :labelPosition="labelPosition"
                     :placeholder="placeholder"
                     :ref="field_slug"
                     @input="emitOnInput"
                     @onChange="emitOnChange"
                     @onBlur="emitOnBlur"
                     @onFocus="emitOnFocus" >
            </vv-text>
        </template>

        <template v-else-if="field_slug === 'textarea'">
            <vv-textarea :content="value"
                         :size="size"
                         :class="custom_class"
                         :label="label"
                         :labelPosition="labelPosition"
                         :placeholder="placeholder"
                         :ref="field_slug"
                         @input="emitOnInput"
                         @onChange="emitOnChange"
                         @onBlur="emitOnBlur"
                         @onFocus="emitOnFocus" >
            </vv-textarea>
        </template>

        <template v-else-if="field_slug === 'number' || field_slug === 'phone-number'
         || field_slug === 'price'">
            <vv-number :content="value"
                       :size="size"
                       :class="custom_class"
                       :label="label"
                       :labelPosition="labelPosition"
                       :placeholder="placeholder"
                       :ref="field_slug"
                       @input="emitOnInput"
                       @onChange="emitOnChange"
                       @onBlur="emitOnBlur"
                       @onFocus="emitOnFocus" >
            </vv-number>
        </template>

        <template v-else-if="field_slug === 'password'">
            <vv-password :content="value"
                         :size="size"
                         :class="custom_class"
                         :label="label"
                         :labelPosition="labelPosition"
                         :placeholder="placeholder"
                         :ref="field_slug"
                         @input="emitOnInput"
                         @onChange="emitOnChange"
                         @onBlur="emitOnBlur"
                         @onFocus="emitOnFocus" >
            </vv-password>
        </template>

        <template v-else-if="field_slug === 'editor'">
            <vv-jodit-editor :content="value"
                             :size="size"
                             :class="custom_class"
                             :label="label"
                             :is_simple="is_simple"
                             :labelPosition="labelPosition"
                             :placeholder="placeholder"
                             :ref="field_slug"
                             @input="emitOnInput"
                             @onChange="emitOnChange"
                             @onBlur="emitOnBlur"
                             @onFocus="emitOnFocus" >
            </vv-jodit-editor>
        </template>

        <template v-else-if="field_slug === 'email'">
            <vv-email :content="value"
                      :size="size"
                      :class="custom_class"
                      :label="label"
                      :labelPosition="labelPosition"
                      :placeholder="placeholder"
                      :ref="field_slug"
                      @input="emitOnInput"
                      @onChange="emitOnChange"
                      @onBlur="emitOnBlur"
                      @onFocus="emitOnFocus" >
            </vv-email>
        </template>

        <template v-else-if="field_slug === 'address'">
            <vv-address :content="value"
                        :size="size"
                        :class="custom_class"
                        :label="label"
                        :labelPosition="labelPosition"
                        :placeholder="placeholder"
                        :ref="field_slug"
                        @input="emitOnInput"
                        @onChange="emitOnChange"
                        @onBlur="emitOnBlur"
                        @onFocus="emitOnFocus" >
            </vv-address>
        </template>

<!--        <template v-else-if="field_slug === 'uuid'">-->
<!--            <vv-uuid :content="value"-->
<!--                     :size="size"-->
<!--                     :class="custom_class"-->
<!--                     :label="label"-->
<!--                     :labelPosition="labelPosition"-->
<!--                     :placeholder="placeholder"-->
<!--                     :ref="field_slug"-->
<!--                     @input="emitOnInput"-->
<!--                     @onChange="emitOnChange"-->
<!--                     @onBlur="emitOnBlur"-->
<!--                     @onFocus="emitOnFocus" >-->
<!--            </vv-uuid>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'boolean'">-->
<!--            <vv-boolean :content="value"-->
<!--                        :size="size"-->
<!--                        :class="custom_class"-->
<!--                        :label="label"-->
<!--                        :labelPosition="labelPosition"-->
<!--                        :placeholder="placeholder"-->
<!--                        :ref="field_slug"-->
<!--                        @input="emitOnInput"-->
<!--                        @onChange="emitOnChange"-->
<!--                        @onBlur="emitOnBlur"-->
<!--                        @onFocus="emitOnFocus" >-->
<!--            </vv-boolean>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'code-mirror'">-->

<!--            <vv-code-mirror :content="value"-->
<!--                            :mode="type"-->
<!--                            :label="label"-->
<!--                            :labelPosition="labelPosition"-->
<!--                            :ref="field_slug"-->
<!--                            @input="emitOnInput"-->
<!--                            @onChange="emitOnChange"-->
<!--                            @onBlur="emitOnBlur"-->
<!--                            @onFocus="emitOnFocus" >-->
<!--            </vv-code-mirror>-->

<!--        </template>-->

<!--        <template v-else-if="field_slug === 'select' && meta">-->

<!--            <vv-select :content="value"-->
<!--                       :type="type"-->
<!--                       :size="size"-->
<!--                       :options="meta.option"-->
<!--                       :is_multiple="meta.is_multiple"-->
<!--                       :class="custom_class"-->
<!--                       :label="label"-->
<!--                       :labelPosition="labelPosition"-->
<!--                       :placeholder="placeholder"-->
<!--                       :ref="field_slug"-->
<!--                       @input="emitOnInput"-->
<!--                       @onChange="emitOnChange"-->
<!--                       @onBlur="emitOnBlur"-->
<!--                       @onFocus="emitOnFocus" >-->
<!--            </vv-select>-->

<!--        </template>-->

<!--        <template v-else-if="field_slug === 'date'">-->
<!--            <vv-date :content="value"-->
<!--                     :size="size"-->
<!--                     :class="custom_class"-->
<!--                     :label="label"-->
<!--                     :labelPosition="labelPosition"-->
<!--                     :placeholder="placeholder"-->
<!--                     :ref="field_slug"-->
<!--                     @input="emitOnInput"-->
<!--                     @onChange="emitOnChange"-->
<!--                     @onBlur="emitOnBlur"-->
<!--                     @onFocus="emitOnFocus" >-->
<!--            </vv-date>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'time'">-->
<!--            <vv-time :content="value"-->
<!--                     :size="size"-->
<!--                     :class="custom_class"-->
<!--                     :label="label"-->
<!--                     :labelPosition="labelPosition"-->
<!--                     :placeholder="placeholder"-->
<!--                     :ref="field_slug"-->
<!--                     @input="emitOnInput"-->
<!--                     @onChange="emitOnChange"-->
<!--                     @onBlur="emitOnBlur"-->
<!--                     @onFocus="emitOnFocus" >-->
<!--            </vv-time>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'date-time'">-->
<!--            <vv-date-time :content="value"-->
<!--                          :size="size"-->
<!--                          :class="custom_class"-->
<!--                          :label="label"-->
<!--                          :labelPosition="labelPosition"-->
<!--                          :placeholder="placeholder"-->
<!--                          :ref="field_slug"-->
<!--                          @input="emitOnInput"-->
<!--                          @onChange="emitOnChange"-->
<!--                          @onBlur="emitOnBlur"-->
<!--                          @onFocus="emitOnFocus" >-->
<!--            </vv-date-time>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'user'">-->
<!--            <vv-auto-complete-users :content="value"-->
<!--                                    :size="size"-->
<!--                                    :class="custom_class"-->
<!--                                    :label="label"-->
<!--                                    :labelPosition="labelPosition"-->
<!--                                    :placeholder="placeholder"-->
<!--                                    :ref="field_slug"-->
<!--                                    @input="emitOnInput"-->
<!--                                    @onChange="emitOnChange"-->
<!--                                    @onBlur="emitOnBlur"-->
<!--                                    @onFocus="emitOnFocus" >-->
<!--            </vv-auto-complete-users>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'currency-code'">-->
<!--            <vv-currency-code :content="value"-->
<!--                              :size="size"-->
<!--                              :class="custom_class"-->
<!--                              :label="label"-->
<!--                              :labelPosition="labelPosition"-->
<!--                              :placeholder="placeholder"-->
<!--                              :ref="field_slug"-->
<!--                              :options="currency_options"-->
<!--                              @input="emitOnInput"-->
<!--                              @onChange="emitOnChange"-->
<!--                              @onBlur="emitOnBlur"-->
<!--                              @onFocus="emitOnFocus" >-->
<!--            </vv-currency-code>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'list'">-->
<!--            <vv-list :content="value"-->
<!--                     :size="size"-->
<!--                     :class="custom_class"-->
<!--                     :label="label"-->
<!--                     :labelPosition="labelPosition"-->
<!--                     :placeholder="placeholder"-->
<!--                     :ref="field_slug"-->
<!--                     @input="emitOnInput"-->
<!--                     @onChange="emitOnChange"-->
<!--                     @onBlur="emitOnBlur"-->
<!--                     @onFocus="emitOnFocus" >-->
<!--            </vv-list>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'image'">-->
<!--            <vv-image :content="value"-->
<!--                      :size="size"-->
<!--                      :class="custom_class"-->
<!--                      :label="label"-->
<!--                      :labelPosition="labelPosition"-->
<!--                      :placeholder="placeholder"-->
<!--                      :upload_url="upload_url"-->
<!--                      :app_url="app_url"-->
<!--                      @input="emitOnInput"-->
<!--                      @onChange="emitOnChange"-->
<!--                      @onBlur="emitOnBlur"-->
<!--                      @onFocus="emitOnFocus" >-->
<!--            </vv-image>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'media'">-->
<!--            <vv-media :content="value"-->
<!--                      :size="size"-->
<!--                      :class="custom_class"-->
<!--                      :label="label"-->
<!--                      :labelPosition="labelPosition"-->
<!--                      :placeholder="placeholder"-->
<!--                      :upload_url="upload_url"-->
<!--                      :app_url="app_url"-->
<!--                      @input="emitOnInput"-->
<!--                      @onChange="emitOnChange"-->
<!--                      @onBlur="emitOnBlur"-->
<!--                      @onFocus="emitOnFocus" >-->
<!--            </vv-media>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'image-group'">-->
<!--            <vv-image-group :content="value"-->
<!--                            :size="size"-->
<!--                            :class="custom_class"-->
<!--                            :label="label"-->
<!--                            :labelPosition="labelPosition"-->
<!--                            :placeholder="placeholder"-->
<!--                            :upload_url="upload_url"-->
<!--                            :app_url="app_url"-->
<!--                            @input="emitOnInput"-->
<!--                            @onChange="emitOnChange"-->
<!--                            @onBlur="emitOnBlur"-->
<!--                            @onFocus="emitOnFocus" >-->
<!--            </vv-image-group>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'phone-number'">-->
<!--            <vv-phone-number :content="value"-->
<!--                             :size="size"-->
<!--                             :class="custom_class"-->
<!--                             :label="label"-->
<!--                             :labelPosition="labelPosition"-->
<!--                             :placeholder="placeholder"-->
<!--                             :ref="field_slug"-->
<!--                             @input="emitOnInput"-->
<!--                             @onChange="emitOnChange"-->
<!--                             @onBlur="emitOnBlur"-->
<!--                             @onFocus="emitOnFocus" >-->
<!--            </vv-phone-number>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'relation'">-->
<!--            <vv-tree-select :content="value"-->
<!--                            :type="type"-->
<!--                            :size="size"-->
<!--                            :ajax_url="app_url"-->
<!--                            :is_multiple="meta.is_multiple"-->
<!--                            :meta="meta"-->
<!--                            :class="custom_class"-->
<!--                            :label="label"-->
<!--                            :labelPosition="labelPosition"-->
<!--                            :placeholder="placeholder"-->
<!--                            :ref="field_slug"-->
<!--                            @input="emitOnInput"-->
<!--                            @onChange="emitOnChange"-->
<!--                            @onBlur="emitOnBlur"-->
<!--                            @onFocus="emitOnFocus" >-->
<!--            </vv-tree-select>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'tags'">-->
<!--            <vv-tags :content="value"-->
<!--                     :size="size"-->
<!--                     :class="custom_class"-->
<!--                     :label="label"-->
<!--                     :labelPosition="labelPosition"-->
<!--                     :placeholder="placeholder"-->
<!--                     :ref="field_slug"-->
<!--                     :ajax_url="ajax_url"-->
<!--                     :display_column="display_column"-->
<!--                     :unique_column="unique_column"-->
<!--                     @input="emitOnInput"-->
<!--                     @onChange="emitOnChange"-->
<!--                     @onBlur="emitOnBlur"-->
<!--                     @onFocus="emitOnFocus" >-->
<!--            </vv-tags>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'json'">-->
<!--            <vv-json :content="value"-->
<!--                     :size="size"-->
<!--                     :class="custom_class"-->
<!--                     :label="label"-->
<!--                     :labelPosition="labelPosition"-->
<!--                     :placeholder="placeholder"-->
<!--                     :ref="field_slug"-->
<!--                     @input="emitOnInput"-->
<!--                     @onChange="emitOnChange"-->
<!--                     @onBlur="emitOnBlur"-->
<!--                     @onFocus="emitOnFocus" >-->
<!--            </vv-json>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'seo-meta-tags'">-->
<!--            <vv-seo-meta-tags :content="value"-->
<!--                              :size="size"-->
<!--                              :class="custom_class"-->
<!--                              :label="label"-->
<!--                              :labelPosition="labelPosition"-->
<!--                              :placeholder="placeholder"-->
<!--                              :ref="field_slug"-->
<!--                              @input="emitOnInput"-->
<!--                              @onChange="emitOnChange"-->
<!--                              @onBlur="emitOnBlur"-->
<!--                              @onFocus="emitOnFocus" >-->
<!--            </vv-seo-meta-tags>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'twitter-card'">-->
<!--            <vv-twitter-card :content="value"-->
<!--                             :size="size"-->
<!--                             :class="custom_class"-->
<!--                             :label="label"-->
<!--                             :labelPosition="labelPosition"-->
<!--                             :placeholder="placeholder"-->
<!--                             :ref="field_slug"-->
<!--                             @input="emitOnInput"-->
<!--                             @onChange="emitOnChange"-->
<!--                             @onBlur="emitOnBlur"-->
<!--                             @onFocus="emitOnFocus" >-->
<!--            </vv-twitter-card>-->
<!--        </template>-->

<!--        <template v-else-if="field_slug === 'facebook-card'">-->
<!--            <vv-facebook-card :content="value"-->
<!--                              :size="size"-->
<!--                              :class="custom_class"-->
<!--                              :label="label"-->
<!--                              :labelPosition="labelPosition"-->
<!--                              :placeholder="placeholder"-->
<!--                              :ref="field_slug"-->
<!--                              @input="emitOnInput"-->
<!--                              @onChange="emitOnChange"-->
<!--                              @onBlur="emitOnBlur"-->
<!--                              @onFocus="emitOnFocus" >-->
<!--            </vv-facebook-card>-->
<!--        </template>-->

<!--        <template v-else>-->
<!--            {{placeholder}}-->
<!--        </template>-->

    </div>
</template>

