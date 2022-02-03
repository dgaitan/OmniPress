<template>
    <layout title="Sync">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <ListWrapper title="Orders">
              <!-- Actions -->
              <template #actions>
                <jet-input v-model="q" type="search" v-on:keyup.enter="search" placeholder="Search..." />
              </template>

              <!-- FIlters -->
              <template #filters>
                <ListFilter @click="filterStatus('all')" :active="status === '' || status === 'all'">
                  All
                </ListFilter>
                <ListFilter 
                  v-for="s in statuses"
                  @click="filterStatus(s.slug)"
                  :active="s.slug === status">
                  {{ s.label }}
                </ListFilter>
              </template>

              <template #table>
                <ListTable :columns="columns">
                  <template #body>
                    <tr class="text-xs" v-for="(order, i) in orders" v-bind:class="[isOdd(i) ? '' : 'bg-gray-50']">
                      <!-- Order ID -->
                      <td class="flex items-center py-5 px-6 font-medium">
                        <input class="mr-3" type="checkbox" name="" id="">
                          <span>#{{ order.order_id }}</span>
                      </td>
                      
                      <!-- Customer -->
                      <td class="font-medium">
                        <div v-if="order.customer">
                          <p class="font-medium">{{ order.customer.name }}</p>
                          <p class="text-gray-500">{{ order.customer.email }}</p>
                        </div>
                        <div v-else>-</div>
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
                    </tr>
                  </template>
                </ListTable>
              </template>

              <template #pagination>
                <ListPagination :url="route('kinja.orders.index')" :params="this.$props" />
              </template>
            </ListWrapper>
        </div>
    </layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/Layout.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import ListWrapper from '@/Components/List/ListWrapper.vue'
    import ListFilter from '@/Components/List/ListFilter.vue'
    import ListTable from '@/Components/List/ListTable.vue'
    import ListPagination from '@/Components/List/ListPagination'

    export default defineComponent({
        props: [
          'sessions', 'orders', 'total', 
          'nextUrl', 'prevUrl', 'perPage',
          'status', 'statuses',
          'currentPage', 's', 'filterByStatus'],

        components: {
            Layout,
            JetInput,
            ListWrapper,
            ListFilter,
            ListTable,
            ListPagination
        },

        data() {
          return {
            page: this.currentPage,
            q: this.s
          }
        },

        computed: {
          columns() {
            return [
              {
                name: 'Order ID',
                sortable: true,
                link: ''
              },
              {
                name: 'Customer',
                sortable: false,
                link: ''
              },
              {
                name: 'Status',
                sortable: false,
                link: ''
              },
              {
                name: 'Shipping',
                sortable: false,
                link: ''
              },
              {
                name: 'Date Completed',
                sortable: true,
                link: ''
              },
              {
                name: 'Total',
                sortable: true,
                link: ''
              }
            ]
          }
        },

        methods: {
            parseRole(role) {
              return role ? role.split('_').join(' ') : ''
            },

            filterStatus(status) {
              this.$inertia.get(route('kinja.orders.index'), {
                status: status
              }, { replace: true });
            },

            search() {
              this.$inertia.get(route('kinja.orders.index'), {
                s: this.q
              }, { replace: true })
            },
        }
    })
</script>
