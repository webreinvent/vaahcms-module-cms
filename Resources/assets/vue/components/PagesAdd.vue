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

            <div class="col-sm-12 col-md-8">
                <h4>Title</h4>

            </div>


            <div class="col-sm-12 col-md-4">
                <h4>Description</h4>

            </div>


        </div>
        <!--/content header-->

        <!--content body-->



        <!--/content body-->
    </div>
</template>
<script src="./PagesAddJs.js"></script>