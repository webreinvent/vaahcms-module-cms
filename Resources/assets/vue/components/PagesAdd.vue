<template>
    <div>


        <div class="row">
            <div class="col-sm">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mg-b-0 tx-spacing--1">Add Pages</h4>
                    </div>
                    <div class="d-none d-md-block">

                        <router-link class="btn btn-sm pd-x-15 btn-light btn-uppercase"
                                     :to="{ path: '/'}">
                            <i class="fas fa-arrow-left"></i> Back
                        </router-link>

                    </div>
                </div>

            </div>

        </div>

        <!--content header-->
        <div class="row mg-b-10 mg-t-10">

            <!--right hand side form-->
            <div class="col-sm-12 col-md-8">

                <div class="form-group">
                    <input class="form-control" v-model="page_data.title" placeholder="Page Title" />

                    <div class="mg-t-5">

                        <div v-if="page_data.slug_edit == true" class="input-group input-group-sm mg-b-10" >

                            <div class="input-group-prepend">
                                <span class="input-group-text" >{{urls.base}}/</span>
                            </div>

                            <input type="text" class="form-control" v-model="page_data.slug" placeholder="Username"
                            />

                            <div class="input-group-append">
                                <button class="btn btn-outline-light" v-on:click="updatePageSlug" type="button" >Save</button>
                                <button class="btn btn-outline-light" v-on:click="setSlugEdit(null)" type="button" >Cancel</button>
                            </div>

                        </div>

                        <p v-else>

                            <strong>Permalink:</strong>

                            <a :href="page_data.permalink">{{urls.base}}/{{page_data.slug}}</a>

                            <button class="btn btn-xs btn-light mg-l-10" v-on:click="setSlugEdit(true)" >
                                Edit
                            </button>

                        </p>


                    </div>

                </div>

                <div class="form-group">
                    <textarea class="form-control" rows="10"
                              v-model="page_data.content"
                              placeholder="Content"></textarea>
                </div>

                <hr class="mg-t-30 mg-b-30">

                <h5>Custom Fields</h5>

                <div v-if="page_data.custom_fields">

                    <div v-for="group in page_data.custom_fields">


                        <div v-for="field in group.fields">

                            <div class="form-group" v-if="field.type == 'text'">
                                <label>{{field.name}}</label>
                                <input class="form-control" v-model="field.content" placeholder="Page Title" />
                                <div v-if="field.excerpt" class="invalid-feedback show">{{field.excerpt}}</div>
                            </div>

                            <div class="form-group" v-if="field.type == 'textarea'">
                                <label>{{field.name}}</label>
                                <textarea class="form-control" v-model="field.content" placeholder="Content"></textarea>
                                <div v-if="field.excerpt" class="invalid-feedback show">{{field.excerpt}}</div>
                            </div>

                        </div>

                    </div>

                </div>



            </div>
            <!--/right hand side form-->


            <!--left hand side form-->
            <div class="col-sm-12 col-md-4" v-if="assets">

                <div class="card mg-b-15">
                    <div class="card-header pd-10"><strong>Publish</strong></div>
                    <div class="card-body pd-10">


                        <div class="row mg-b-10">
                            <div class="col-sm-12">
                                <input class="form-control form-control-sm"
                                       v-model="page_data.name"
                                       placeholder="Page Name"/>
                            </div>
                        </div>

                        <div class="row mg-b-10">
                            <div class="col-sm-6">
                                <button v-on:click="storeDraft" class="btn btn-sm btn-light">Save Draft</button>
                            </div>
                            <div class="col-sm-6 text-right">
                                <button class="btn btn-sm btn-light pull-right">Preview</button>
                            </div>
                        </div>

                        <table class="table table-condensed">
                            <tr>
                                <td width="100">Status</td>
                                <td>
                                    <v-select v-model="page_status" :options="assets.page_statuses"></v-select>
                                </td>
                            </tr>

                            <tr>
                                <td>Visibility</td>
                                <td>
                                    <v-select v-model="page_visibility" :options="assets.page_visibilities"></v-select>
                                </td>
                            </tr>

                            <tr>
                                <td>Published At</td>
                                <td>
                                    <strong>May 4, 2017 @ 14:11</strong>
                                </td>
                            </tr>


                        </table>


                        <div class="row mg-b-10">
                            <div class="col-sm-4">

                            </div>
                            <div class="col-sm-4 offset-sm-4 text-right">
                                <button class="btn btn-sm btn-success pull-right">Publish</button>
                            </div>
                        </div>


                    </div>
                </div>


                <div class="card">
                    <div class="card-header pd-10"><strong>Page Attribute</strong></div>
                    <div class="card-body pd-10">


                        <table class="table table-condensed">
                            <tr>
                                <td width="100">Parent</td>
                                <td>
                                    <v-select  v-model="page_parent" :options="assets.pages_list"></v-select>
                                </td>
                            </tr>

                            <tr>
                                <td>Template</td>
                                <td>

                                    <div class="input-group">
                                        <v-select  v-model="page_template"
                                                   style="min-width: 150px;"
                                                   v-on:input="getCustomFields"
                                                   :options="assets.page_templates">
                                        </v-select>

                                        <div class="input-group-append">

                                            <button class="btn btn-xs btn-light"
                                            v-on:click="getCustomFields">
                                                <i class="fas fa-sync"></i>
                                            </button>

                                        </div>
                                    </div>



                                </td>
                            </tr>

                        </table>



                    </div>
                </div>

            </div>
            <!--/left hand side form-->


        </div>
        <!--/content header-->

        <!--content body-->



        <!--/content body-->
    </div>
</template>
<script src="./PagesAddJs.js"></script>