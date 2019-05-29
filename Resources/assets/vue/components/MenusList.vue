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
        <div class="row mg-t-15">

            <div class="col-xs-12 col-sm-5 col-md-3">

                <div class="card">
                    <div class="card-header">Pages</div>
                    <div class="card-body pd-0-f">

                        <div>

                            <table class="table  table-condensed  table-sm">

                                <tr>
                                    <td width="25">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" >
                                            <label class="custom-control-label"></label>
                                        </div>
                                    </td>
                                    <td>
                                        Page title
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" >
                                            <label class="custom-control-label"></label>
                                        </div>
                                    </td>
                                    <td>
                                        Page title
                                    </td>
                                </tr>

                            </table>

                        </div>


                    </div>

                    <div class="card-footer">

                        <button class="btn btn-xs btn-primary">Add</button>

                    </div>

                </div>

            </div>
            <div class="col-xs-12 col-sm-7 col-md-9">
                Right
            </div>


        </div>


        <!--/content body-->


    </div>
</template>
<script src="./MenusListJs.js"></script>