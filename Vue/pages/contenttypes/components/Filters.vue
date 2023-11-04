<script  setup>

import { useContentTypeStore } from '../../../stores/store-contenttypes'
import VhFieldVertical from './../../../vaahvue/vue-three/primeflex/VhFieldVertical.vue'

const store = useContentTypeStore();

</script>

<template>
    <div>

        <Sidebar v-model:visible="store.show_filters"
                 position="right">

            <VhFieldVertical >
                <template #label>
                    <b>Sort By:</b>
                </template>

                <div class="field-radiobutton">
                    <RadioButton name="sort-none"
                                 data-testid="contenttypes-filters-sort-none"
                                 value=""
                                 v-model="store.query.filter.sort" />
                    <label for="sort-none">None</label>
                </div>

                <div class="field-radiobutton">
                    <RadioButton name="sort-ascending"
                                 data-testid="contenttypes-filters-sort-ascending"
                                 value="updated_at"
                                 v-model="store.query.filter.sort" />
                    <label for="sort-ascending">Updated (Ascending)</label>
                </div>

                <div class="field-radiobutton">
                    <RadioButton name="sort-descending"
                                 data-testid="contenttypes-filters-sort-descending"
                                 value="updated_at:desc"
                                 v-model="store.query.filter.sort" />
                    <label for="sort-descending">Updated (Descending)</label>
                </div>

                <div class="field-radiobutton">
                    <RadioButton name="sort-ascending"
                                 data-testid="contenttypes-filters-sort-ascending"
                                 value="name"
                                 v-model="store.query.filter.sort" />
                    <label for="sort-ascending">Name (Ascending)</label>
                </div>

                <div class="field-radiobutton">
                    <RadioButton name="sort-descending"
                                 data-testid="contenttypes-filters-sort-descending"
                                 value="name:desc"
                                 v-model="store.query.filter.sort" />
                    <label for="sort-descending">Name (Descending)</label>
                </div>

                <div class="field-radiobutton">
                    <RadioButton name="sort-ascending"
                                 data-testid="contenttypes-filters-sort-ascending"
                                 value="plural"
                                 v-model="store.query.filter.sort" />
                    <label for="sort-ascending">Plural Name (Ascending)</label>
                </div>

                <div class="field-radiobutton">
                    <RadioButton name="sort-descending"
                                 data-testid="contenttypes-filters-sort-descending"
                                 value="plural:desc"
                                 v-model="store.query.filter.sort" />
                    <label for="sort-descending">Plural Name (Descending)</label>
                </div>

                <div class="field-radiobutton">
                    <RadioButton name="sort-ascending"
                                 data-testid="contenttypes-filters-sort-ascending"
                                 value="singular"
                                 v-model="store.query.filter.sort" />
                    <label for="sort-ascending">Singular Name (Ascending)</label>
                </div>

                <div class="field-radiobutton">
                    <RadioButton name="sort-descending"
                                 data-testid="contenttypes-filters-sort-descending"
                                 value="singular:desc"
                                 v-model="store.query.filter.sort" />
                    <label for="sort-descending">Singular Name (Descending)</label>
                </div>

            </VhFieldVertical>

            <Divider/>

            <VhFieldVertical >
                <template #label>
                    <b>Is Published:</b>
                </template>

                <div class="field-radiobutton">
                    <RadioButton name="active-all"
                                 value="null"
                                 data-testid="contenttypes-filters-active-all"
                                 v-model="store.query.filter.is_published" />
                    <label for="active-all">All</label>
                </div>
                <div class="field-radiobutton">
                    <RadioButton name="active-true"
                                 data-testid="contenttypes-filters-active-true"
                                 value="true"
                                 v-model="store.query.filter.is_published" />
                    <label for="active-true">Only Published</label>
                </div>
                <div class="field-radiobutton">
                    <RadioButton name="active-false"
                                 data-testid="contenttypes-filters-active-false"
                                 value="false"
                                 v-model="store.query.filter.is_published" />
                    <label for="active-false">Only Unpublished</label>
                </div>

            </VhFieldVertical>

            <VhFieldVertical >
                <template #label>
                    <b>Trashed:</b>
                </template>

                <div class="field-radiobutton">
                    <RadioButton name="trashed-exclude"
                                 data-testid="contenttypes-filters-trashed-exclude"
                                 value=""
                                 v-model="store.query.filter.trashed" />
                    <label for="trashed-exclude">Exclude Trashed</label>
                </div>
                <div class="field-radiobutton">
                    <RadioButton name="trashed-include"
                                 data-testid="contenttypes-filters-trashed-include"
                                 value="include"
                                 v-model="store.query.filter.trashed" />
                    <label for="trashed-include">Include Trashed</label>
                </div>
                <div class="field-radiobutton">
                    <RadioButton name="trashed-only"
                                 data-testid="contenttypes-filters-trashed-only"
                                 value="only"
                                 v-model="store.query.filter.trashed" />
                    <label for="trashed-only">Only Trashed</label>
                </div>

            </VhFieldVertical>


        </Sidebar>

    </div>
</template>
