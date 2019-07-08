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
        <div class="row mg-t-15" v-if="assets">

            <div class="col-sm-12 col-md-3">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Theme Location</label>

                        <vh-select
                                :options="assets.theme_menu_locations"
                                v-model="active_location_id"
                                option_value="id"
                                option_text="name"
                                default_text="Select Theme Location"
                                select_class="custom-select"
                                v-on:change="getLocationMenus"
                        ></vh-select>


                    </div>
                    <div class="form-group col-md-12" v-if="menus_list">
                        <label >Menu</label>
                        <vh-select
                                :options="menus_list"
                                v-model="active_menu_id"
                                option_value="id"
                                option_text="name"
                                default_text="Select Menu"
                                select_class="custom-select"
                                v-on:change="getMenuItems"
                        ></vh-select>



                    </div>

                    <div class="form-group col-md-12">
                    <button class="btn btn-block btn-sm btn-success" v-on:click="getMenuItems">
                        <i class="fas fa-sync-alt"></i> Reload Menu
                    </button>

                    </div>

                </div>
            </div>

            <div class="col-sm-12 col-md-9">

                <div class="admin-menus">

                    <menutree v-if="menu_items" v-for="(menu_item, index) in menu_items"
                              :key="index"
                              :urls="urls"
                              @deleteItem="deleteItem"
                              @addSubMenu="addSubMenu"
                              @editMenu="editMenu"
                              :menu_item="menu_item"></menutree>


                    <button class="btn mg-t-10 btn-sm pd-x-15 btn-primary btn-uppercase"
                            v-if="menu_items"
                            @click="addRootMenu">
                        <i class="fas fa-plus"></i> Add Menu Item
                    </button>

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
                      <input type="text" class="form-control" v-model="new_menu.name" placeholder="Menu Name">
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

        <!--modal-->
        <div class="modal fade" id="ModalAddMenuItem" tabindex="-1" role="dialog" >
              <div class="modal-dialog">
                <div class="modal-content tx-14">
                  <div class="modal-header">
                    <h6 class="modal-title">Add Menu Item</h6>
                    <button type="button" class="close" data-dismiss="modal">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">


                  <div class="form-group row" v-if="assets">
                      <label class="col-sm-5 col-form-label">Select Page</label>
                      <div class="col-sm-7">

                          <select v-model="new_menu_item.vh_page_id" class="custom-select">
                              <option selected>Select Page</option>
                              <option v-if="assets.pages"
                                      v-for="page in assets.pages"
                                      v-bind:value="page.id"

                              >{{page.name}}</option>
                          </select>

                      </div>
                  </div>

                   <div class="form-group row">
                    <label class="col-sm-5 col-form-label">Menu Link Name</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" v-model="new_menu_item.name" placeholder="Menu Link Name">
                    </div>
                  </div>

                      <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Menu Link Name</label>
                          <div class="col-sm-7">

                              <div class="custom-control custom-checkbox">
                                  <input type="checkbox" class="custom-control-input" v-model="new_menu_item.is_home" id="isHome">
                                  <label class="custom-control-label" for="isHome">Make this as Home Page</label>
                              </div>

                          </div>
                      </div>

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary tx-13" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary tx-13" v-on:click="storeMenuItem" >Save changes</button>
                  </div>
                </div>
              </div>
        </div>
        <!--/modal-->



    </div>
</template>
<script src="./ListJs.js"></script>