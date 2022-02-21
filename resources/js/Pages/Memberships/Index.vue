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
                        class="border-gray-300 focus:border-cyan-600 focus:ring focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm ml-2"
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
                    <select v-model="action" @change="bulkActions()" class="border-gray-300 focus:border-cyan-600 focus:ring focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm ml-2" style="width:200px">
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
                            <option value="status_to_expired">Change Status to Expired</option>
                        </optgroup>
                        <optgroup label="Actions">
                            <option value="expire">Cancell Membership</option>
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
                          <a href="javascript:void(0)" @click="showDetail(membership.id)" class="text-cyan-500 font-bold">Show</a>
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

        <!-- Membership Detail Modal -->
        <jet-modal :show="showDetailModal" @close="showDetailModal = false" maxWidth="7xl">
            <MembershipDetail :membership="membership" />
            <div class="p-4">
                <jet-button @click="showSyncConfirmation = false">
                    Close
                </jet-button>
            </div>
        </jet-modal>
    </layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import axios from 'axios'
    import Layout from '@/Layouts/Layout.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetModal from '@/Jetstream/Modal.vue'
    import ListWrapper from '@/Components/List/ListWrapper.vue'
    import ListFilter from '@/Components/List/ListFilter.vue'
    import ListTable from '@/Components/List/ListTable.vue'
    import ListPagination from '@/Components/List/ListPagination'
    import MembershipDetail from './Detail.vue'

    export default defineComponent({
        props: [
          'sessions', 'memberships',
          // Pagination Props
          'total', 'nextUrl', 'prevUrl', '_perPage', '_currentPage',
          // Ordering Props
          '_order', '_orderBy',
          // Custom Props
          '_status', 'statuses', '_s', '_shippingStatus', 'shippingStatuses'],

        components: {
            Layout,
            JetInput,
            JetButton,
            JetModal,
            ListWrapper,
            ListFilter,
            ListTable,
            ListPagination,
            MembershipDetail
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
                membership: {}
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

            async showDetail(id) {
                const request = await axios.get(route('kinja.memberships.show', id));

                if (request.data.membership) {
                    this.membership = request.data.membership
                    this.showDetailModal = true
                }
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

            bulkActions() {
                if (!this.action) return false;

                this.$inertia.post(route('kinja.memberships.actions'), {
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
