<template>
    <div>


        <div class="row">
            <div class="col-sm">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mg-b-0 tx-spacing--1">Pages</h4>
                    </div>
                    <div class="d-none d-md-block">

                        <router-link class="btn btn-sm pd-x-15 btn-primary btn-uppercase"
                                     :to="{ path: '/add'}">
                            <i class="fas fa-plus"></i> Add New
                        </router-link>

                    </div>
                </div>

            </div>

        </div>

        <!--content header-->
        <div class="row mg-b-10 mg-t-10">

            <div class="col-sm  ">

                <div class="bd-b bd-1 pd-b-10">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <div>
                            <nav class="nav" v-if="stats">
                                <a href="#" class="nav-link pd-l-0"
                                   v-on:click="setFilter($event, 'all')"
                                   v-bind:class="{'active': filters.status == 'all'}">All ({{stats.all}})</a>
                                <a href="#" class="nav-link"
                                   v-on:click="setFilter($event, 'active')"
                                   v-bind:class="{'active': filters.status == 'active'}">Active ({{stats.active}})</a>
                                <a href="#" class="nav-link"
                                   v-on:click="setFilter($event, 'inactive')"
                                   v-bind:class="{'active': filters.status == 'inactive'}">Inactive ({{stats.inactive}})</a>
                                <a href="#" class="nav-link"
                                   v-on:click="setFilter($event, 'update_available')"
                                   v-bind:class="{'active': filters.status == 'update_available'}">Updates Available ({{stats.update_available}})</a>
                            </nav>
                        </div>

                        <div class="d-none d-md-block">
                            <div class="search-form">
                                <input type="search" class="form-control" v-model="filters.q"
                                       v-on:keyup.enter="getList()"
                                       placeholder="Search">
                                <button class="btn" v-on:click="getList()" type="button">
                                    <i class="fas fa-search"></i>
                                </button>

                            </div>


                        </div>

                    </div>


                </div>


            </div>

        </div>
        <!--/content header-->

        <!--content body-->



        <!--/content body-->
    </div>
</template>
<script src="./PagesListJs.js"></script>