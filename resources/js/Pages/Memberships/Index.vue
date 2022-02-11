<template>
    <layout title="Memberships">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <ListWrapper title="Memberships">
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
                  :key="s.slug"
                  :active="s.slug === status">
                  {{ s.label }}
                </ListFilter>
              </template>

              <template #table>
                <ListTable :columns="columns">
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
                <ListPagination :url="route('kinja.memberships.index')" :params="this.$props" />
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
          'sessions', 'memberships', 'total', 
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
            q: this.s
          }
        },

        computed: {
          columns() {
            return [
              {
                name: 'ID',
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
                name: 'Shipping Status',
                sortable: false,
                link: ''
              },
              {
                name: 'Kind Cash',
                sortable: true,
                link: ''
              },
              {
                name: 'Start At',
                sortable: true,
                link: ''
              },
              {
                name: 'End At',
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
              this.$inertia.get(route('kinja.memberships.index'), {
                status: status
              }, { replace: true });
            },

            search() {
              this.$inertia.get(route('kinja.memberships.index'), {
                s: this.q
              }, { replace: true })
            },
        }
    })
</script>
