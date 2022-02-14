<template>
    <layout title="Memberships">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <ListWrapper title="Memberships">
              <!-- Actions -->
              <template #actions>
                <jet-input v-model="s" type="search" v-on:keyup.enter="search" placeholder="Search..." style="width:350px" />
                <select
                    v-model="shippingStatus"
                    @change="changeShippingStatus()"
                    class="border-gray-300 focus:border-cyan-600 focus:ring focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm ml-2"
                    name="content_type"
                    id="content_type">
                    <option value="" :selected="shippingStatus === ''">Filter By Shipping Status</option>
                    <option
                        v-for="s in shippingStatuses"
                        :key="s.slug"
                        :value="s.slug"
                        :selected="shippingStatus === s.slug">
                        {{ s.label }}
                    </option>
                </select>
              </template>

              <!-- FIlters -->
              <template #filters>
                <ListFilter @click="filterStatus('all')" :active="status === '' || status === 'all'">
                  All
                </ListFilter>
                <ListFilter
                  v-for="s in statuses"
                  @click="filterStatus(s.slug)"
                  :key="s.slug"
                  :active="s.slug === status">
                  {{ s.label }}
                </ListFilter>
              </template>

              <template #table>
                <ListTable :columns="columns" :stateData="this.$data">
                  <template #body>
                    <tr class="text-xs"
                      v-for="(membership, i) in memberships"
                      :key="membership.id"
                      v-bind:class="[isOdd(i) ? '' : 'bg-gray-50']">
                      <!-- MEmbership ID -->
                      <td class="flex items-center py-5 px-6 font-medium">
                        <input class="mr-3" type="checkbox" name="" id="">
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
                    </tr>
                  </template>
                </ListTable>
              </template>

              <template #pagination>
                <ListPagination :url="route('kinja.memberships.index')" :params="this.$props" :stateData="this.$data" />
              </template>
            </ListWrapper>

        </div>
    </layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/Layout.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
    import ListWrapper from '@/Components/List/ListWrapper.vue'
    import ListFilter from '@/Components/List/ListFilter.vue'
    import ListTable from '@/Components/List/ListTable.vue'
    import ListPagination from '@/Components/List/ListPagination'

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
            JetDropdown,
            JetDropdownLink,
            ListWrapper,
            ListFilter,
            ListTable,
            ListPagination
        },

        data() {
          return {
            status: this._status ? this._status : 'all',
            s: this._s,
            shippingStatus: this._shippingStatus,
            // Pagination Data
            page: this._currentPage,
            perPage: this._perPage,
            // Ordering Data
            order: this._order,
            orderBy: this._orderBy
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
                    ...this.$data
                }, { replace: true })
            },

            filterStatus(status) {
                this.status = status
                this.$inertia.get(route('kinja.memberships.index'), {
                    ...this.$data
                }, { replace: true });
            },

            search() {
                this.$inertia.get(route('kinja.memberships.index'), {
                    s: this.s
                }, { replace: true })
            },
        }
    })
</script>
