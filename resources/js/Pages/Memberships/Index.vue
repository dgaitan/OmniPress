<template>
    <layout title="Memberships">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <ListWrapper title="Memberships">
                <!-- Actions -->
                <template #actions>
                    <!-- SearchBox -->
                    <jet-input v-model="filters.s" type="search" v-on:keyup.enter="search" placeholder="Search..." style="width:350px" />

                    <!-- Shipping Status Filter -->
                    <select
                        v-model="filters.shippingStatus"
                        @change="changeShippingStatus()"
                        class="border-gray-300 focus:border-cyan-600 focus:ring focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm ml-2 py-2 pl-2 pr-10"
                        name="content_type"
                        id="content_type">
                        <option value="" :selected="filters.shippingStatus === ''">Filter By Shipping Status</option>
                        <option
                            v-for="s in shippingStatuses"
                            :key="s.slug"
                            :value="s.slug"
                            :selected="filters.shippingStatus === s.slug">
                            {{ s.label }}
                        </option>
                    </select>

                    <!-- Actions -->
                    <select v-model="action" @change="confirmAction()" class="border-gray-300 focus:border-cyan-600 focus:ring focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm ml-2 py-2 pl-2 pr-10" style="width:200px">
                        <option value="">Actions</option>
                        <optgroup label="Shipping Statuses">
                            <option value="shipping_status_to_cancelled">Change Shipping Status to Cancelled</option>
                            <option value="shipping_status_to_shipped">Change Shipping Status to Shipped</option>
                            <option value="shipping_status_to_no_ship">Change Shipping Status to No Ship</option>
                        </optgroup>
                        <optgroup label="Membership Statuses">
                            <option value="status_to_active">Change Status to Active</option>
                            <option value="status_to_awaiting_pick_gift">Change Status to Awaiting Pick Gift</option>
                            <option value="status_to_cancelled">Change Status to Cancelled</option>
                        </optgroup>
                        <optgroup label="Actions">
                            <option value="expire">Expire Membership</option>
                            <option value="renew">Renew Memberships</option>
                            <option value="run_cron">Run Renewal Cron</option>
                        </optgroup>
                    </select>
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
                      <td class="flex items-center py-5 px-6 font-medium">
                        <input class="mr-3" type="checkbox" @change="setIds($event)" :checked="ids.includes(membership.id)" :value="membership.id">
                          <span>#{{ membership.id }}</span>
                      </td>
                      <!-- Customer -->
                      <td class="font-medium">
                        <div v-if="membership.customer">
                          <p class="font-medium">{{ membership.customer.username }}</p>
                          <p class="text-gray-500">{{ membership.customer.email }}</p>
                        </div>
                      </td>
                      <!-- Status -->
                      <td class="font-medium">
                        <span :class="`status ${membership.status}`">
                          {{ parseRole(membership.status) }}
                        </span>
                      </td>

                      <!-- Shipping Status -->
                      <td class="font-medium">
                        <span :class="`status ${membership.shipping_status}`">
                          {{ parseRole(membership.shipping_status) }}
                        </span>
                      </td>

                      <!-- Kind Cash -->
                      <td class="font-medium">
                        $ {{ moneyFormat(membership.cash.points) }}
                      </td>

                      <!-- Start At -->
                      <td class="font-medium">
                        {{ displayMoment(membership.start_at, 'LL') }}
                      </td>

                      <!-- End At -->
                      <td class="font-medium">
                        {{ displayMoment(membership.end_at, 'LL') }}
                      </td>

                        <!-- Actions -->
                        <td class="font-medium">
                            <Link :href="route('kinja.memberships.show', membership.id)" class="text-cyan-500 font-bold">Show</Link>
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
        <jet-confirmation-modal :show="showActionConfirmation" @close="showActionConfirmation = false">
            <template #title>
                Are you sure?
            </template>

            <template #content>
                {{ confirmationMessage }}
            </template>

            <template #footer>
                <jet-secondary-button @click="showActionConfirmation = false" class="">
                    <span v-if="ids.length > 0 || action === 'run_cron'">Cancel</span>
                    <span v-else>Close</span>
                </jet-secondary-button>

                <jet-button v-if="ids.length > 0 || action === 'run_cron'" class="ml-3" @click="bulkActions" :class="{ 'opacity-25': runningBulk }" :disabled="runningBulk">
                    <span v-if="!runningBulk">Confirm</span>
                    <Sppiner v-else />
                </jet-button>
            </template>
        </jet-confirmation-modal>
    </layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3'
    import Layout from '@/Layouts/Layout.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import ListWrapper from '@/Components/List/ListWrapper.vue'
    import ListFilter from '@/Components/List/ListFilter.vue'
    import ListTable from '@/Components/List/ListTable.vue'
    import ListPagination from '@/Components/List/ListPagination'
    import Sppiner from '@/Components/Sppiner.vue'

    export default defineComponent({
        props: [
            'sessions', 'memberships',
            // Pagination Props
            'total', 'nextUrl', 'prevUrl', '_perPage', '_currentPage',
            // Ordering Props
            '_order', '_orderBy',
            // Custom Props
            '_status', 'statuses', '_s', '_shippingStatus', 'shippingStatuses'
        ],

        components: {
            Layout,
            Link,
            JetInput,
            JetButton,
            JetSecondaryButton,
            JetConfirmationModal,
            ListWrapper,
            ListFilter,
            ListTable,
            ListPagination,
            Sppiner
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
                    orderBy: this._orderBy
                },
                action: '',
                ids: [],
                showDetailModal: false,
                membership: {},
                confirmationMessages: {
                    shipping_status_to_cancelled: 'Please confirm if you want to cancell shipment of these memberships.',
                    shipping_status_to_shipped: 'Please confirm if you want to mark these memberships as shipped.',
                    shipping_status_to_no_ship: 'Please confirm if you want to make as no ship these memberships.',
                    status_to_active: 'Please confirm if you want to active these memberships',
                    status_to_awaiting_pick_gift: 'Please confirm if you want to change these memberships to Awaiting Pick Gift Product.',
                    status_to_cancelled: 'Please confirm if you want to cancell these memberships.',
                    expire: 'Please confirm if you want to expire these memberships.',
                    renew: 'Please confirm if you want to renew these memberships manually.',
                    run_cron: 'Please confirm if you want to run the membership cron manually.'
                },
                confirmationMessage: '',
                showActionConfirmation: false,
                runningBulk: false
            }
        },

        computed: {
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
                    name: 'Shipping Status',
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

            parseRole(role) {
                return role ? role.split('_').join(' ') : ''
            },

            changeShippingStatus() {
                this.$inertia.get(route('kinja.memberships.index'), {
                    ...this.filters
                }, { replace: true })
            },

            filterStatus(status) {
                this.filters.status = status
                this.$inertia.get(route('kinja.memberships.index'), {
                    ...this.filters
                }, { replace: true });
            },

            search() {
                this.$inertia.get(route('kinja.memberships.index'), {
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
                checked ? this.memberships.map(m => this.ids.push(m.id)) : this.ids = [];
            },

            confirmAction() {
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
        }
    })
</script>
