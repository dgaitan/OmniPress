<template>
    <layout title="Printforia Orders">

        <ListWrapper title="Printforia Orders" :fluid="true">
            <!-- Actions -->
            <template #actions>
                <div class="flex items-center">
                    <!-- SearchBox -->
                    <jet-input v-model="filters.s" type="search" v-on:keyup.enter="search" placeholder="Search..." style="width:350px" />
                </div>
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
                            v-for="(order, i) in orders.data"
                            :key="order.id"
                            v-bind:class="[isOdd(i) ? '' : 'bg-gray-50']">

                            <!-- Printforia Order ID -->
                            <td class="flex items-start py-5 px-6 font-medium cursor-pointer" @click="goToDetail(order)">
                                <input class="mr-3" type="checkbox" @change="setIds($event)" :checked="ids.includes(order.id)" :value="order.id">
                                {{ order.printforia_order_id }}
                            </td>

                            <!-- Order Id -->
                            <td class="font-medium px-2 cursor-pointer" @click="goToDetail(order)">
                                #{{ order.order.order_id }}
                            </td>

                            <!-- Status -->
                            <td class="font-medium px-2 cursor-pointer" @click="goToDetail(order)">
                                <Status :status="order.status" />
                            </td>

                            <!-- Created At -->
                            <td class="font-medium px-2 cursor-pointer" @click="goToDetail(order)">
                                {{ displayMoment(order.created_at, 'LL') }}
                            </td>

                            <!-- Email -->
                            <td class="font-medium px-2 cursor-pointer" @click="goToDetail(order)">
                                {{ order.ship_to_address.email }}
                            </td>

                            <td class="px-2 py-3 font-medium text-gray-500 cursor-pointer" width="250px" @click="goToDetail(order)">
                                <span v-html="order.ship_to_address_formatted"></span>
                            </td>

                            <td class="px-2 font-medium text-gray-500 cursor-pointer" width="250px" @click="goToDetail(order)">
                                <span v-html="order.return_to_address_formatted"></span>
                            </td>

                            <!-- Actions -->
                            <td class="font-medium px-2">
                                <jet-dropdown align="right" width="48">
                                    <template #trigger>
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                                            Actions
                                        </button>
                                    </template>
                                    <template #content>
                                        <div class="">
                                            <jet-dropdown-link :href="order.permalink" @click="goToDetail(order)">
                                                Show
                                            </jet-dropdown-link>
                                            <jet-dropdown-link :href="order.woo_permalink" as="a">See WooCommerce Order</jet-dropdown-link>
                                        </div>
                                    </template>
                                </jet-dropdown>
                            </td>
                         </tr>
                    </template>
                </ListTable>
            </template>

            <template #pagination>
                <ListPagination :url="route('kinja.orders.printforiaOrders')" :params="this.$props" :stateData="this.filters" />
            </template>
        </ListWrapper>
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
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
    import { Link } from '@inertiajs/inertia-vue3'
    import Status from '@/Components/Status.vue'

    export default defineComponent({
        props: [
            'sessions', 'orders',
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
            JetDropdown,
            JetDropdownLink,
            ListWrapper,
            ListFilter,
            ListTable,
            ListPagination,
            Status
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
                    name: 'Printforia Order ID',
                    sortable: false,
                    key: ''
                },
                {
                    name: 'Order ID',
                    sortable: false,
                    key: ''
                },
                {
                    name: 'Status',
                    sortable: false,
                    key: ''
                },
                {
                    name: 'Created At',
                    sortable: false,
                    key: ''
                },
                {
                    name: 'Contact Email',
                    sortable: false,
                    key: ''
                },
                {
                    name: 'Ship to address',
                    sortable: false,
                    key: ''
                },
                {
                    name: 'Return to address',
                    sortable: false,
                    key: ''
                }
            ]
          },
        },

        methods: {
            goToDetail(order) {
                this.$inertia.visit(order.permalink)
            },

            parseRole(role) {
              return role ? role.split('_').join(' ') : ''
            },

            filterStatus(status) {
                this.filters.status = status
                this.$inertia.get(route('kinja.orders.printforiaOrders'), {
                    ...this.filters
                }, { replace: true });
            },

            search() {
                this.$inertia.get(route('kinja.orders.printforiaOrders'), {
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
                checked ? this.orders.data.map(m => this.ids.push(m.id)) : this.ids = [];
            },

            bulkActions() {
                if (!this.action) return false;

                this.$inertia.post(route('kinja.orders.printforiaOrders'), {
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
