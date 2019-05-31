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


            <div class="col-12">



                <tree v-if="menu_items" :menu_items="menu_items"></tree>


                <div class="form-row">
                    <div class="form-group col-md-6">
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
                    <div class="form-group col-md-6" v-if="menus_list">
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

                    <button v-on:click="getMenuItems">Reload</button>
                </div>

                <hr/>

                <div class="admin-menus">


                    <table class="table table-ordered">

                        <template v-if="menu_items">

                            <tr v-for="item in menu_items">
                                <td>{{item.id}}</td>
                                <td>{{item.depth}}</td>
                                <td>

                                    <span v-for="index in item.depth">
                                        -
                                    </span>

                                    {{item.name}}
                                </td>
                            </tr>

                        </template>

                    </table>



                    <button class="btn btn-sm pd-x-15 btn-primary btn-uppercase"
                            @click="showModalMenuItemAdd">
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
                      <label class="col-sm-2 col-form-label">Select Page</label>
                      <div class="col-sm-10">
                          <vh-select
                                  :options="assets.pages"
                                  v-model="new_menu_item.vh_page_id"
                                  option_value="id"
                                  option_text="name"
                                  default_text="Select Page"
                                  select_class="custom-select"
                          ></vh-select>
                      </div>
                  </div>

                   <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Menu Link Name</label>
                    <div class="col-sm-10">
                      <input type="email" class="form-control" v-model="new_menu_item.name" placeholder="Menu Link Name">
                    </div>
                  </div>


                  <div class="form-group row" v-if="assets && assets.menu_items">

                    <label class="col-sm-2 col-form-label">Parent Menu Item</label>
                    <div class="col-sm-10">
                        <vh-select
                                :options="assets.menu_items"
                                v-model="new_menu_item.parent_id"
                                option_value="id"
                                option_text="name"
                                default_text="Select Parent Menu Item"
                                select_class="custom-select"
                        ></vh-select>
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
<script src="./MenusListJs.js"></script>