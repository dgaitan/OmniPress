<template>
    <layout title="Subscriptions">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <ListWrapper title="Subscriptions">
                <!-- Actions -->
                <template #actions>
                    <!-- <Actions
                        :filters="filters"
                        :shippingStatuses="shippingStatuses"
                        :action="action"
                        @bulkAction="confirmAction"
                        @showFilters="showFilters = $event"/> -->
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
                            v-for="(subscription, i) in subscriptions"
                            :key="subscription.id"
                            v-bind:class="[isOdd(i) ? '' : 'bg-gray-50']">

                            <!-- Subscription ID -->
                            <td class="flex items-center py-5 px-6 font-medium">
                                <input class="mr-3" type="checkbox" @change="setIds($event)" :checked="ids.includes(subscription.id)" :value="subscription.id">
                                <span>#{{ subscription.id }}</span>
                            </td>

                            <!-- Customer -->
                            <td class="font-medium p-2 w-32">
                                <div v-if="subscription.customer">
                                    <p class="font-medium">{{ subscription.customer.username }}</p>
                                    <p class="text-gray-500">{{ subscription.customer.email }}</p>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="font-medium">
                                <Status :status="subscription.status" />
                            </td>

                            <!-- Items -->
                            <td class="font-medium p-2 w-52">
                                <div v-if="subscription.items && subscription.items.length > 0">
                                    <div v-for="(item, key) in subscription.items" :key="key" class="flex items-start mb-3">
                                        <img :src="item.product.image" class=" w-10 mr-2" />
                                        <div class="flex flex-col">
                                            <span>{{ item.product.name }}</span>
                                            <span class="text-gray-600 mt-2 inline-block">
                                                <strong>Qty: </strong>{{ item.quantity }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Total -->
                            <td class="font-medium">
                                $ {{ moneyFormat(subscription.total) }}
                            </td>

                            <!-- Start At -->
                            <td class="font-medium">
                                {{ displayMoment(subscription.start_date, 'LL') }}
                            </td>

                            <!-- Last Payment Date -->
                            <td class="font-medium">
                                {{ displayMoment(subscription.last_intent, 'LL') }}
                            </td>

                            <!-- Next Payment Date -->
                            <td class="font-medium">
                                {{ displayMoment(subscription.next_payment_date, 'LL') }}
                            </td>

                            <!-- Actions -->
                            <td class="font-medium">
                                <jet-dropdown align="right" width="48">
                                    <template #trigger>
                                        <button type="button" class="inline-flex items-center px-3 py-2 text-xs border border-gray-200 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                                            Actions
                                        </button>
                                    </template>
                                    <template #content>
                                        <div class="">
                                            <jet-dropdown-link
                                                :href="route('kinja.memberships.show', subscription.id)">
                                                Show
                                            </jet-dropdown-link>
                                        </div>
                                    </template>
                                </jet-dropdown>
                            </td>
                        </tr>
                    </template>
                </ListTable>
            </template>

            <template #pagination>
                <ListPagination :url="route('kinja.memberships.index')" :params="this.$props" :stateData="this.filters" />
            </template>
            </ListWrapper>

        </div>

        <!-- Sync Confirmation Modal -->
        <!-- <Confirm
            title="Are you sure?"
            :message="confirmationMessage"
            :show="showActionConfirmation"
            :canConfirm="ids.length > 0 || action === 'run_cron'"
            :processing="runningBulk"
            @close="showActionConfirmation = false"
            @confirm="bulkActions" />

        <UpdateKindCash
            :membership="membership"
            :show="showUpdateKindCash"
            @close="showUpdateKindCash = false"/>

        <Filters
            :filters="filters"
            :shippingStatuses="shippingStatuses"
            :show="showFilters"
            @close="showFilters = false"/> -->
    </layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3'
    import Layout from '@/Layouts/Layout.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
    import ListWrapper from '@/Components/List/ListWrapper.vue'
    import ListFilter from '@/Components/List/ListFilter.vue'
    import ListTable from '@/Components/List/ListTable.vue'
    import ListPagination from '@/Components/List/ListPagination'
    import Status from '@/Components/Status.vue'
    import Confirm from '@/Components/Confirm.vue'


    export default defineComponent({
        props: [
            'sessions', 'data',
            // Pagination Props
            'total', 'nextUrl', 'prevUrl', '_perPage', '_currentPage',
            // Ordering Props
            '_order', '_orderBy',
            // Custom Props
            'statuses', '_status'
        ],

        components: {
            Layout,
            Link,
            JetInput,
            JetDropdown,
            JetDropdownLink,
            ListWrapper,
            ListFilter,
            ListTable,
            ListPagination,
            Status,
            Confirm
        },

        data() {
            return {
                filters: {
                    status: this._status,
                    // Pagination Data
                    page: this._currentPage,
                    perPage: this._perPage,
                    // Ordering Data
                    order: this._order,
                    orderBy: this._orderBy,
                },
                action: '',
                ids: [],
                showDetailModal: false,
                confirmationMessage: '',
                showActionConfirmation: false,
                runningBulk: false,
                showUpdateKindCash: false,
                showFilters: false
            }
        },

        computed: {
            subscriptions() {
                return this.data.data;
            },

            columns() {
                return [
                    {
                        name: 'ID',
                        sortable: true,
                        key: 'id'
                    },
                    {
                        name: 'Customer',
                        sortable: false,
                        key: ''
                    },
                    {
                        name: 'Status',
                        sortable: false,
                        key: ''
                    },
                    {
                        name: 'Items',
                        sortable: false,
                        key: ''
                    },
                    {
                        name: 'Total',
                        sortable: true,
                        key: 'kind_cash'
                    },
                    {
                        name: 'Start Date',
                        sortable: true,
                        key: 'start_date'
                    },
                    {
                        name: 'Last Payment',
                        sortable: true,
                        key: 'last_payment'
                    },
                    {
                        name: 'Next Payment',
                        sortable: true,
                        key: 'next_payment_date'
                    },
                    {
                        name: '',
                        sortable: false,
                        key: ''
                    }
                ]
            }
        },

        methods: {

            filterStatus(status) {
                this.filters.status = status
                this.$inertia.get(route('kinja.memberships.index'), {
                    ...this.filters
                }, { replace: true });
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

            updateKindCash(membership) {
                this.membership = membership
                this.showUpdateKindCash = true
            },

            selectAllIds(checked) {
                checked ? this.memberships.map(m => this.ids.push(m.id)) : this.ids = [];
            },

            confirmAction(action = '') {
                this.action = action
                if (this.ids.length === 0 && this.action !== 'run_cron') {
                    this.confirmationMessage = 'Please select at least one membership to execute this action.'
                } else {
                    this.confirmationMessage = this.confirmationMessages[this.action]
                }

                this.showActionConfirmation = true
            },

            bulkActions() {
                if (!this.action) return false;
                this.runningBulk = true;

                this.$inertia.post(route('kinja.memberships.actions'), {
                    ids: this.ids,
                    action: this.action,
                    filters: this.filters
                }, {
                    replace: true,
                    onSuccess: () => {
                        this.ids = []
                        this.action = ''
                        this.runningBulk = false
                        this.confirmationMessage = ''
                        this.showActionConfirmation = false
                    }
                });
            }
        }
    })
</script>
