<template>
    <div>


        <div class="row">
            <div class="col-sm">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mg-b-0 tx-spacing--1">Menus</h4>
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

        <!--content body-->
        <div class="row">

            <div class="col-xs-12 col-md-3">
                Left
            </div>
            <div class="col-xs-12 col-md-9">
                Right
            </div>


        </div>


        <!--/content body-->


    </div>
</template>
<script src="./MenusListJs.js"></script>