<template>
    <layout title="Sync">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <ListWrapper title="Orders">
              <!-- Actions -->
              <template #actions>
                <jet-input v-model="filters.s" type="search" v-on:keyup.enter="search" placeholder="Search..." />
              </template>

              <!-- FIlters -->
              <template #filters>
                <ListFilter @click="filterStatus('all')" :active="filters.status === '' || filters.status === 'all'">
                  All
                </ListFilter>
                <ListFilter
                  v-for="s in statuses"
                  :key="s.slug"
                  @click="filterStatus(s.slug)"
                  :active="s.slug === filters.status">
                  {{ s.label }}
                </ListFilter>
              </template>

              <template #table>
                <ListTable :columns="columns" :stateData="this.filters" :selectIds="selectAllIds">
                  <template #body>
                    <tr
                      class="text-xs"
                      v-for="(order, i) in orders"
                      v-bind:class="[isOdd(i) ? '' : 'bg-gray-50']"
                      :key="order.id">
                      <!-- Order ID -->
                        <td class="flex items-center py-5 px-6 font-medium">
                            <input class="mr-3" type="checkbox" @change="setIds($event)" :checked="ids.includes(order.id)" :value="order.id">
                            <span>#{{ order.order_id }}</span>
                        </td>

                        <!-- Customer -->
                        <td class="font-medium">
                            <div>
                                <p class="font-medium">{{ order.billing.first_name }} {{ order.billing.last_name }}</p>
                                <p class="text-gray-500">{{ order.billing.email }}</p>
                            </div>
                        </td>

                      <!-- Status -->
                      <td class="font-medium">
                        <span :class="`status ${order.status}`">
                          {{ parseRole(order.status) }}
                        </span>
                      </td>

                      <!-- Shipping Status -->
                      <td class="font-medium">
                        <div class="text-gray-500 py-2" v-html="order.shipping"></div>
                      </td>

                      <!-- Date -->
                      <td class="font-medium">
                        {{ order.date }}
                      </td>

                      <!-- Total -->
                      <td class="font-medium">
                        $ {{ moneyFormat(order.total) }}
                      </td>

                        <td class="font-medium">
                            <Link :href="route('kinja.orders.show', order.order_id)" class="text-cyan-500 font-bold">Show</Link>
                        </td>
                    </tr>
                  </template>
                </ListTable>
              </template>

              <template #pagination>
                <ListPagination :url="route('kinja.orders.index')" :params="this.$props" :stateData="this.filters" />
              </template>
            </ListWrapper>
        </div>
    </layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3'
    import Layout from '@/Layouts/Layout.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import ListWrapper from '@/Components/List/ListWrapper.vue'
    import ListFilter from '@/Components/List/ListFilter.vue'
    import ListTable from '@/Components/List/ListTable.vue'
    import ListPagination from '@/Components/List/ListPagination'

    export default defineComponent({
        props: [
            'sessions', 'orders',
            // Pagination Props
            'total', 'nextUrl', 'prevUrl', '_perPage', '_currentPage',
            // Ordering Props
            '_order', '_orderBy',
            // Custom Props
            '_status', 'statuses', '_s'],

        components: {
            Layout,
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
            ids: []
          }
        },

        computed: {
          columns() {
            return [
              {
                name: 'Order ID',
                sortable: true,
                key: 'order_id'
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
                name: 'Shipping',
                sortable: false,
                key: ''
              },
              {
                name: 'Date Completed',
                sortable: true,
                key: 'date_created'
              },
              {
                name: 'Total',
                sortable: true,
                key: 'total'
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

            filterStatus(status) {
                this.filters.status = status
                this.$inertia.get(route('kinja.orders.index'), {
                    ...this.filters
                }, { replace: true });
            },

            search() {
              this.$inertia.get(route('kinja.orders.index'), {
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
        }
    })
</script>
