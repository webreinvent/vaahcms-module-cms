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
                                   v-for="stat in stats"
                                   v-on:click="setFilterStatus($event, stat.code)"
                                   v-bind:class="{'active': filters.status == stat.code}">
                                    {{stat.label}} ({{stat.count}})
                                </a>

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


        <div class="row">

            <div class="col-sm-3">


                <div class="input-group input-group-sm">
                    <select class="custom-select" id="inputGroupSelect04">
                        <option selected>Bulk Actions</option>
                        <option >Activate</option>
                        <option value="1">Deactivate</option>
                        <option value="2">Update</option>
                        <option value="3">Delete</option>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button">Apply</button>
                    </div>
                </div>


            </div>

        </div>

        <div class="row mg-t-10 mg-b-10">
            <div class="col-sm">
                <table class="table bg-white" v-if="list">
                    <thead class="thead-light">
                    <tr >
                        <th width="30" scope="col">

                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="selectAll">
                                <label class="custom-control-label" for="selectAll"></label>
                            </div>

                        </th>
                        <th scope="col">Title</th>
                        <th scope="col">Status</th>
                        <th scope="col">Create By</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr  v-for="item in list.data"
                         class="">
                        <th scope="row">

                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1"></label>
                            </div>

                        </th>
                        <td>

                            <router-link :to="{ path: '/edit/'+item.id}" class="tx-medium">
                                {{item.title}}
                            </router-link><br/>

                            <small>{{item.name}}</small>
                            
                        </td>

                        <td>
                            {{item.status}}
                        </td>

                        <td>

                        </td>

                    </tr>



                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">

            <div class="col">
                <pagination  v-if="list" :limit="6" :data="list" @pagination-change-page="getList"></pagination>
            </div>

        </div>



        <!--/content body-->


    </div>
</template>
<script src="./PagesListJs.js"></script>