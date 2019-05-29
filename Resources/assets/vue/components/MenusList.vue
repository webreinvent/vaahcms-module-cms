<template>
    <div>


        <div class="row">
            <div class="col-sm">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mg-b-0 tx-spacing--1">Menus</h4>
                    </div>
                    <div class="d-none d-md-block">

                        <button class="btn btn-sm pd-x-15 btn-primary btn-uppercase"
                                @click="showModalMenuAdd">
                            <i class="fas fa-plus"></i> Add New
                        </button>


                    </div>
                </div>

            </div>

        </div>

        <!--content body-->
        <div class="row mg-t-15">


            <div class="col-12">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Theme Location</label>
                        <select class="custom-select">
                            <option selected>Select Location</option>
                            <option value="1">Top Menu</option>
                            <option value="2">Side Bar</option>
                            <option value="3">Footer</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label >Menu</label>
                        <select class="custom-select">
                            <option selected>Select Location</option>
                            <option value="1">Top Menu</option>
                            <option value="2">Side Bar</option>
                            <option value="3">Footer</option>
                        </select>
                    </div>
                </div>

                <hr/>

                <div class="admin-menus">


                    <ul>
                        <li>

                            <div class="menu-block">
                                Menu 1

                                <span class="btn-group btn-group-xs">
                                    <button class="btn btn-xs btn-light btn-icon pull-right" ><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-xs btn-light btn-icon pull-right" ><i class="fas fa-plus"></i></button>
                                </span>

                            </div>

                        </li>
                        <li>
                            <div class="menu-block">
                                Menu 3

                                <span class="btn-group btn-group-xs">
                                    <button class="btn btn-xs btn-light btn-icon pull-right" ><i class="fas fa-plus"></i></button>
                                    <button class="btn btn-xs btn-light btn-icon pull-right" ><i class="fas fa-arrow-down"></i></button>
                                </span>

                            </div>

                            <ul>
                                <li>
                                    <div class="menu-block">
                                    Sub menu 1
                                        <span class="btn-group btn-group-xs">
                                        <button class="btn btn-xs btn-light btn-icon pull-right" ><i class="fas fa-plus"></i></button>
                                        <button class="btn btn-xs btn-light btn-icon pull-right" ><i class="fas fa-arrow-down"></i></button>
                                        </span>

                                    </div>

                                </li>
                                <li>
                                    <div class="menu-block">
                                        Sub menu 2
                                        <span class="btn-group btn-group-xs pull-right">
                                        <button class="btn btn-xs btn-light btn-icon " ><i class="fas fa-plus"></i></button>
                                        <button class="btn btn-xs btn-light btn-icon" ><i class="fas fa-arrow-down"></i></button>
                                        </span>

                                    </div>

                                </li>
                            </ul>
                        </li>


                    </ul>


                </div>

            </div>


        </div>


        <!--/content body-->


        <!--modal-->
        <div class="modal fade" id="ModalAddMenu" tabindex="-1" role="dialog" >
              <div class="modal-dialog" v-if="assets">
                <div class="modal-content tx-14">
                  <div class="modal-header">
                    <h6 class="modal-title">Add New Menu</h6>
                    <button type="button" class="close" data-dismiss="modal">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">

                   <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Select Theme Location</label>
                    <div class="col-sm-10">

                        <vh-select
                                :options="assets.theme_menu_locations"
                                v-model="new_menu.vh_theme_location_id"
                                option_value="id"
                                option_text="name"
                                default_text="Select Theme Location"
                                select_class="custom-select"
                        ></vh-select>

                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Menu Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" placeholder="Menu Name">
                    </div>
                  </div>

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary tx-13" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary tx-13" @click="storeMenu">Save changes</button>
                  </div>
                </div>
              </div>
        </div>
        <!--/modal-->



    </div>
</template>
<script src="./MenusListJs.js"></script>