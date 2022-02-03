<template>
    <layout title="Sync">

        <template #header>
            <div class="w-full flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Orders
                </h2>
                <!-- <jet-button @click="showSyncConfirmation = true">Sync Now</jet-button> -->
            </div>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <section class="pb-8">
              <div class="container px-4 mx-auto">
                <div class="p-4 mb-6 bg-white shadow rounded overflow-x-auto">
                  <!-- Filters -->
                  <div class="flex justify-between mb-5 w-full">
                      <jet-input v-model="q" type="search" v-on:keyup.enter="search" placeholder="Search..." class="w-full" />
                  </div>
                  
                  <table class="table-auto w-full">
                    <thead>
                      <tr class="text-xs text-gray-500 text-left">
                        <th class="pl-6 pb-3 font-medium">Order ID</th>
                        <th class="pb-3 font-medium">Customer</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium">Shipping</th>
                        <th class="pb-3 font-medium">Date Completed</th>
                        <th class="pb-3 font-medium">Total</th>
                      </tr>
                    </thead>
                    <tbody>
                        <tr class="text-xs bg-gray-50" v-for="order in orders" :key="order.id">
                          <td class="py-5 px-6 font-medium">#{{ order.order_id }}</td>
                          <td class="flex px-4 py-3">
                              <div v-if="order.customer">
                                  <p class="font-medium">{{ order.customer.name }}</p>
                                  <p class="text-gray-500">{{ order.customer.email }}</p>
                              </div>
                              <div v-else>-</div>
                          </td>
                          <td>
                              <span :class="`status ${order.status}`">
                                  {{ parseRole(order.status) }}
                              </span>
                          </td>
                          <td class="font-medium"></td>
                          <td>
                            <span class="inline-block py-1 px-2 text-purple-500 bg-purple-50 rounded-full">{{ order.date }}</span>
                          </td>
                          <td>
                            <span class="font-medium">$ {{ moneyFormat(order.total) }}</span>
                          </td>
                          <td>
                          
                          </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="flex flex-wrap -mx-4 items-center justify-between">
                  <div class="w-full lg:w-1/3 px-4 flex items-center mb-4 lg:mb-0">
                    <p class="text-xs text-gray-400">Show</p>
                    <div class="mx-3 py-2 px-2 text-xs text-gray-500 bg-white border rounded">
                      <select v-model="perPage" @change="changePerPage()">
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="300">300</option>
                      </select>
                    </div>
                    <p class="text-xs text-gray-400">of {{ total }}</p>
                  </div>
                  <div class="w-full lg:w-auto px-4 flex items-center justify-center">
                    <a class="inline-flex mr-3 items-center justify-center w-8 h-8 text-xs text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded" href="#">
                      <svg width="6" height="8" viewBox="0 0 6 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.53335 3.99999L4.86668 1.66666C5.13335 1.39999 5.13335 0.999992 4.86668 0.733325C4.60002 0.466659 4.20002 0.466659 3.93335 0.733325L1.13335 3.53333C0.866683 3.79999 0.866683 4.19999 1.13335 4.46666L3.93335 7.26666C4.06668 7.39999 4.20002 7.46666 4.40002 7.46666C4.60002 7.46666 4.73335 7.39999 4.86668 7.26666C5.13335 6.99999 5.13335 6.59999 4.86668 6.33333L2.53335 3.99999Z" fill="#A4AFBB"></path>
                      </svg>
                    </a>
                    <input v-model="page" type="text" @change="changePerPage()" class="inline-flex mr-3 items-center justify-center w-8 h-8 text-xs text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded" />
                    <Link :href="nextUrl" class="inline-flex items-center justify-center w-8 h-8 text-xs text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded">
                      <svg width="6" height="8" viewBox="0 0 6 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.88663 3.52667L2.05996 0.700006C1.99799 0.637521 1.92425 0.587925 1.84301 0.554079C1.76177 0.520233 1.67464 0.502808 1.58663 0.502808C1.49862 0.502808 1.41148 0.520233 1.33024 0.554079C1.249 0.587925 1.17527 0.637521 1.1133 0.700006C0.989128 0.824915 0.919434 0.993883 0.919434 1.17001C0.919434 1.34613 0.989128 1.5151 1.1133 1.64001L3.4733 4.00001L1.1133 6.36001C0.989128 6.48491 0.919434 6.65388 0.919434 6.83001C0.919434 7.00613 0.989128 7.1751 1.1133 7.30001C1.17559 7.36179 1.24947 7.41068 1.33069 7.44385C1.41192 7.47703 1.49889 7.49385 1.58663 7.49334C1.67437 7.49385 1.76134 7.47703 1.84257 7.44385C1.92379 7.41068 1.99767 7.36179 2.05996 7.30001L4.88663 4.47334C4.94911 4.41136 4.99871 4.33763 5.03256 4.25639C5.0664 4.17515 5.08383 4.08801 5.08383 4.00001C5.08383 3.912 5.0664 3.82486 5.03256 3.74362C4.99871 3.66238 4.94911 3.58865 4.88663 3.52667Z" fill="#A4AFBB"></path>
                      </svg>
                    </Link>
                  </div>
                </div>
              </div>
            </section>
        </div>
    </layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/Layout.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import { Link } from '@inertiajs/inertia-vue3'

    export default defineComponent({
        props: [
          'sessions', 'orders', 'total', 
          'nextUrl', 'prevUrl', 'perPage', 
          'currentPage', 's', 'filterByStatus'],

        components: {
            Layout,
            JetButton,
            JetConfirmationModal,
            Link,
            JetInput
        },

        data() {
          return {
            page: this.currentPage,
            q: this.s
          }
        },

        methods: {
            parseRole(role) {
              return role ? role.split('_').join(' ') : ''
            },

            changePerPage() {
              this.$inertia.get(route('kinja.orders.index'), {
                perPage: this.perPage,
                page: this.page,
              }, { replace: true })
            },

            search() {
              this.$inertia.get(route('kinja.orders.index'), {
                s: this.q
              }, { replace: true })
            },
        }
    })
</script>
