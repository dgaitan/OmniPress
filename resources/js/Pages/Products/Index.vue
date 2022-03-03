<template>
    <layout title="Products">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <ListWrapper title="Products">
                <!-- Actions -->
                <template #actions>
                    <!-- SearchBox -->
                    <jet-input v-model="filters.s" type="search" v-on:keyup.enter="search" placeholder="Search..." style="width:350px" />
                </template>

                <!-- FIlters -->
                <template #filters>
                    <ListFilter @click="filterStatus('all')" :active="filters.status === '' || filters.status === 'all'">
                        All
                    </ListFilter>
                    <ListFilter
                        v-for="s in statuses"
                        @click="filterStatus(s.slug)"
                        :key="s.slug"
                        :active="s.slug === filters.status">
                        {{ s.label }}
                    </ListFilter>
                </template>

                <template #table>
                    <ListTable :columns="columns" :stateData="this.filters" :selectIds="selectAllIds">
                        <template #body>
                            <tr class="text-xs"
                                v-for="(product, i) in products"
                                :key="product.id"
                                v-bind:class="[isOdd(i) ? '' : 'bg-gray-50']">

                                <!-- Product Info -->
                                <td class="flex items-start py-5 px-6 font-medium">
                                    <input class="mr-3" type="checkbox" @change="setIds($event)" :checked="ids.includes(product.id)" :value="product.id">
                                    <div class="flex" style="width:300px;">
                                        <img
                                            v-if="product.images.length === 0"
                                            class="w-20 h-20 mr-4 object-cover rounded-md"
                                            src="https://picsum.photos/id/160/80/80" alt="">
                                        <img v-else :src="product.images[0].src" class="w-20 h-20 mr-4 object-cover rounded-md" >
                                        <div>
                                            <p class="text-sm font-medium mb-1">
                                                <strong class="text-gray-400">ID {{ product.product_id }}</strong>
                                            </p>
                                            <p class="text-sm font-medium mb-2">{{ product.name }}</p>
                                            <p class="text-xs text-gray-500">SKU: {{ product.sku }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="font-medium">
                                    <span :class="`status ${product.status}`">
                                        {{ parseRole(product.status) }}
                                    </span>
                                </td>

                                <!-- Price -->
                                <td class="font-medium">
                                    <span class="text-xs" v-html="product.settings.price_html"></span>
                                </td>

                                <!-- Date Created -->
                                <td class="font-medium">
                                    <span class="inline-block py-1 px-2 text-sm text-purple-500 bg-purple-50 rounded-full">
                                        {{ product.date_created ? displayMoment(product.date_created, 'LL') : displayMoment(product.date_modified, 'LL') }}
                                    </span>
                                </td>

                                <td class="font-medium" width="250px">
                                    <div class="py-4 flex flex-wrap items-center" style="max-width: 200px" v-if="product.categories.length > 0">
                                        <a
                                        v-for="category in product.categories"
                                        :data-product-id="category.id"
                                        :key="category.id"
                                        class="py-1 px-2 text-xs text-sky-500 bg-sky-100 mr-2 mb-2 rounded-full"
                                        href="#">
                                        {{ category.name }}
                                        </a>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="font-medium">
                                    <a href="javascript:void(0)" @click="showDetail(membership.id)" class="text-cyan-500 font-bold">Show</a>
                                </td>
                             </tr>
                        </template>
                    </ListTable>
                </template>

                <template #pagination>
                    <ListPagination :url="route('kinja.products.index')" :params="this.$props" :stateData="this.filters" />
                </template>
            </ListWrapper>
        </div>
    </layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/Layout.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import ListWrapper from '@/Components/List/ListWrapper.vue'
    import ListFilter from '@/Components/List/ListFilter.vue'
    import ListTable from '@/Components/List/ListTable.vue'
    import ListPagination from '@/Components/List/ListPagination'
    import { Link } from '@inertiajs/inertia-vue3'

    export default defineComponent({
        props: [
            'sessions', 'products',
            // Pagination Props
            'total', 'nextUrl', 'prevUrl', '_perPage', '_currentPage',
            // Ordering Props
            '_order', '_orderBy',
            // Custom Props
            '_status', 'statuses', '_s'
        ],

        components: {
            Layout,
            JetButton,
            JetConfirmationModal,
            Link,
            JetInput,
            ListWrapper,
            ListFilter,
            ListTable,
            ListPagination
        },

        data() {
          return {
            filters: {
                status: this._status ? this._status : 'all',
                s: this._s,
                // Pagination Data
                page: this._currentPage,
                perPage: this._perPage,
                // Ordering Data
                order: this._order,
                orderBy: this._orderBy
            },
            action: '',
            ids: []
          }
        },

        computed: {
          columns() {
            return [
                {
                    name: 'Information',
                    sortable: true,
                    key: 'id'
                },
                {
                    name: 'Status',
                    sortable: false,
                    key: ''
                },
                {
                    name: 'Price',
                    sortable: true,
                    key: 'price'
                },
                {
                    name: 'Date Created',
                    sortable: true,
                    key: 'date_created'
                },
                {
                    name: 'Categories',
                    sortable: false,
                    key: ''
                }
            ]
          },
        },

        methods: {
            parseRole(role) {
              return role ? role.split('_').join(' ') : ''
            },

            filterStatus(status) {
                this.filters.status = status
                this.$inertia.get(route('kinja.products.index'), {
                    ...this.filters
                }, { replace: true });
            },

            search() {
                this.$inertia.get(route('kinja.products.index'), {
                    s: this.filters.s
                }, { replace: true })
            },

            /**
             *
             */
            setIds(e) {
                const ID = parseInt(e.target.value);

                if (e.target.checked) {
                    this.ids.push(ID);
                } else {
                    this.ids.splice(this.ids.indexOf(ID), 1);
                }
            },

            selectAllIds(checked) {
                checked ? this.products.map(m => this.ids.push(m.id)) : this.ids = [];
            },

            bulkActions() {
                if (!this.action) return false;

                this.$inertia.post(route('kinja.products.actions'), {
                    ids: this.ids,
                    action: this.action,
                    filters: this.filters
                }, {
                    replace: true,
                    onSuccess: () => {
                        this.ids = []
                        this.action = ''
                    }
                });
            },
        }
    })
</script>
