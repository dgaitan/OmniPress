<template>
    <layout title="Memberships">

        <ListWrapper title="Memberships" :fluid="true" :exportButton="true" @export="exportCsv">
            <!-- Actions -->
            <template #actions>
                <Actions
                    :filters="filters"
                    :shippingStatuses="shippingStatuses"
                    :action="action"
                    @bulkAction="confirmAction"
                    @showFilters="showFilters = $event"/>
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
                        v-for="(membership, i) in memberships"
                        :key="membership.id"
                        v-bind:class="[isOdd(i) ? '' : 'bg-gray-50']">

                        <!-- MEmbership ID -->
                        <td class="py-4 px-6 font-medium whitespace-nowrap">
                            <div class="flex">
                                <input class="mr-3" type="checkbox" @change="setIds($event)" :checked="ids.includes(membership.id)" :value="membership.id">
                                <span>#{{ membership.id }}</span>
                            </div>
                        </td>

                        <!-- Order ID -->
                        <td class="font-medium py-4 px-2 whitespace-nowrap">
                            <Link :href="membership.order.link" class="text-cyan-500">#{{ membership.order.id }}</Link>
                        </td>

                        <!-- Customer -->
                        <td class="font-medium py-4 px-2 whitespace-nowrap">
                            <div v-if="membership.customer">
                                <p class="font-medium">{{ membership.customer.username }}</p>
                                <p class="text-gray-500">{{ membership.customer.email }}</p>
                            </div>
                        </td>

                        <!-- Product Includes -->
                        <td scope="row" class="font-medium py-4 px-2 flex items-center whitespace-nowrap" v-if="membership.giftProduct">
                            <img
                                v-if="membership.giftProduct.images.length === 0"
                                class="w-20 h-20 object-cover rounded-md"
                                src="https://images.unsplash.com/photo-1559893088-c0787ebfc084?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1050&amp;q=80" alt="">
                            <img v-else :src="membership.giftProduct.images[0].src" class="w-20 h-20 object-cover rounded-md" >
                            <div class="pl-3 w-72">
                                <p class="text-sm font-medium mb-1">
                                    <strong class="text-gray-400">ID {{ membership.giftProduct.product_id }}</strong>
                                </p>
                                <p class="text-sm font-medium mb-2 whitespace-normal">{{ membership.giftProduct.name }}</p>
                                <p class="text-xs text-gray-500">SKU: {{ membership.giftProduct.sku }}</p>
                            </div>
                        </td>
                        <td v-else class="font-medium py-4 px-2 whitespace-nowrap">
                            Not Defined Yet.
                        </td>

                        <!-- Status -->
                        <td class="font-medium py-4 px-2 whitespace-nowrap">
                            <Status :status="membership.status" />
                        </td>

                        <!-- Shipping Status -->
                        <td class="font-medium py-4 px-2 whitespace-nowrap">
                            <Status :status="membership.shipping_status" />
                        </td>

                        <!-- Shipping Status -->
                        <td class="font-medium py-4 px-2 whitespace-nowrap">
                            <div v-html="membership.shipping_address"></div>
                        </td>

                        <!-- Kind Cash -->
                        <td
                            class="font-medium py-4 px-2 cursor-pointer whitespace-nowrap"
                            @click="updateKindCash(membership)">
                            $ {{ moneyFormat(membership.cash.points) }}
                        </td>

                        <!-- Start At -->
                        <td class="font-medium py-4 px-2 whitespace-nowrap">
                            {{ displayMoment(membership.start_at, 'LL') }}
                        </td>

                        <!-- End At -->
                        <td class="font-medium py-4 px-2 whitespace-nowrap">
                            {{ displayMoment(membership.end_at, 'LL') }}
                        </td>

                        <!-- Actions -->
                        <td class="font-medium py-4 px-2 whitespace-nowrap">
                            <jet-dropdown align="right" width="48">
                                <template #trigger>
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                                        Actions
                                    </button>
                                </template>
                                <template #content>
                                    <div class="">
                                        <jet-dropdown-link
                                            :href="route('kinja.memberships.show', membership.id)">
                                            Show
                                        </jet-dropdown-link>
                                        <jet-dropdown-link
                                            as="button"
                                            @click="updateKindCash(membership)">
                                            Update KindCash
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

        <!-- Sync Confirmation Modal -->
        <Confirm
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
            @close="showFilters = false"/>
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
    import Actions from './Partials/MembershipListActions.vue'
    import UpdateKindCash from './Partials/UpdateKindCash.vue'
    import Filters from './Partials/Filters.vue'

    export default defineComponent({
        props: [
            'sessions', 'memberships',
            // Pagination Props
            'total', 'nextUrl', 'prevUrl', '_perPage', '_currentPage',
            // Ordering Props
            '_order', '_orderBy',
            // Custom Props
            '_status', 'statuses', '_s', '_shippingStatus', 'shippingStatuses',
            '_fromDate', '_toDate', '_dateFieldToFilter'
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
            Confirm,
            Actions,
            UpdateKindCash,
            Filters
        },

        data() {
            return {
                filters: {
                    status: this._status ? this._status : 'all',
                    s: this._s,
                    shippingStatus: this._shippingStatus,
                    // Pagination Data
                    page: this._currentPage,
                    perPage: this._perPage,
                    // Ordering Data
                    order: this._order,
                    orderBy: this._orderBy,
                    // Dates
                    fromDate: this._fromDate,
                    toDate: this._toDate,
                    dateFieldToFilter: this._dateFieldToFilter
                },
                action: '',
                ids: [],
                showDetailModal: false,
                membership: {},
                confirmationMessages: {
                    shipping_status_to_cancelled: 'Please confirm if you want to cancell shipment of these memberships.',
                    shipping_status_to_shipped: 'Please confirm if you want to mark these memberships as shipped.',
                    shipping_status_to_no_ship: 'Please confirm if you want to make as no ship these memberships.',
                    status_to_in_renewal: 'Please confirm if you want to change these memberships to In Renewal.',
                    status_to_active: 'Please confirm if you want to active these memberships',
                    status_to_awaiting_pick_gift: 'Please confirm if you want to change these memberships to Awaiting Pick Gift Product.',
                    status_to_cancelled: 'Please confirm if you want to cancell these memberships.',
                    expire: 'Please confirm if you want to expire these memberships.',
                    renew: 'Please confirm if you want to renew these memberships manually.',
                    run_cron: 'Please confirm if you want to run the membership cron manually.'
                },
                confirmationMessage: '',
                showActionConfirmation: false,
                runningBulk: false,
                showUpdateKindCash: false,
                showFilters: false
            }
        },

        computed: {
          columns() {
            return [
                {
                    name: 'Member ID',
                    sortable: true,
                    key: 'id'
                },
                {
                    name: 'Order ID',
                    sortable: false,
                    key: ''
                },
                {
                    name: 'Customer',
                    sortable: false,
                    key: ''
                },
                {
                    name: 'Product Included',
                    sortable: false,
                    key: '',
                },
                {
                    name: 'Status',
                    sortable: false,
                    key: ''
                },
                {
                    name: 'Shipping Status',
                    sortable: false,
                    key: ''
                },
                {
                    name: 'Shipping Address',
                    sortable: false,
                    key: ''
                },
                {
                    name: 'Kind Cash',
                    sortable: true,
                    key: 'kind_cash'
                },
                {
                    name: 'Start At',
                    sortable: true,
                    key: 'start_at'
                },
                {
                    name: 'End At',
                    sortable: true,
                    key: 'end_at'
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
            },

            exportCsv() {
                let params = Object.keys(this.filters).map(filter => {
                    return `${filter}=${this.filters[filter]}`
                }).join('&');
                const url = `${route('kinja.memberships.export')}?${params}`;

                window.open(url, '_blank')
            }
        }
    })
</script>
