<template>
    <layout :title="pageTitle">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-wrap -mx-4 mb-5">
                <div class="w-full md:w-2/3 px-4 mb-4 md:mb-0">
                    <div class="flex items-center">
                        <img :src="membership.customer.avatar_url" width="60" height="60" class="rounded-full" />
                        <div class="pl-5">
                            <h4 class="text-xl font-bold">{{ membership.customer.first_name }} {{ membership.customer.last_name }}</h4>
                            <p class="text-sm text-gray-600">{{ membership.customer.email }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">

                </div>
            </div>
            <div class="py-5">
                <div class="container mx-auto">
                    <div class="flex flex-wrap -mx-4">
                        <div class="w-full lg:w-2/3 px-4 mb-8 lg:mb-0">
                            <div class="bg-white rounded mb-10">
                                <div class="p-5 border-b border-gray-200">
                                    <h3 class="mr-2 text-xl font-bold">Membership #{{ membership.id }}</h3>
                                </div>
                                <div class="p-5 flex flex-wrap">
                                    <div class="w-1/2 mb-5">
                                        <p class="text-md font-medium text-gray-500 mb-2">Status</p>
                                        <span :class="`status ${membership.status}`">{{ parseRole(membership.status) }}</span>
                                    </div>
                                    <div class="w-1/2 mb-3">
                                        <p class="text-md font-medium text-gray-500 mb-2">Shipping Status</p>
                                        <span :class="`status ${membership.shipping_status}`">{{ parseRole(membership.shipping_status) }}</span>
                                    </div>
                                    <div class="w-1/2 mb-3">
                                        <p class="text-md font-medium text-gray-500 mb-1">Start At</p>
                                        <span class="text-sm text-gray-600">{{ displayMoment(membership.start_at, 'LL') }}</span>
                                    </div>
                                    <div class="w-1/2 mb-3">
                                        <p class="text-md font-medium text-gray-500 mb-1">End At</p>
                                        <span class="text-sm text-gray-600">{{ displayMoment(membership.end_at, 'LL') }}</span>
                                    </div>
                                    <div class="w-full mt-3">
                                        <p class="text-md font-medium text-gray-500 mb-2">Last Gift Product Selected</p>
                                        <div class="flex" style="width:300px;" v-if="membership.giftProduct">
                                            <img
                                                v-if="membership.giftProduct.images.length === 0"
                                                class="w-20 h-20 mr-4 object-cover rounded-md"
                                                src="https://images.unsplash.com/photo-1559893088-c0787ebfc084?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1050&amp;q=80" alt="">
                                            <img v-else :src="membership.giftProduct.images[0].src" class="w-20 h-20 mr-4 object-cover rounded-md" >
                                            <div>
                                                <p class="text-sm font-medium mb-1">
                                                    <strong class="text-gray-400">ID {{ membership.giftProduct.product_id }}</strong>
                                                </p>
                                                <p class="text-sm font-medium mb-2">{{ membership.giftProduct.name }}</p>
                                                <p class="text-xs text-gray-500">SKU: {{ membership.giftProduct.sku }}</p>
                                            </div>
                                        </div>
                                        <div v-else>Not defined yet</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Orders -->
                            <div class="bg-white rounded">
                                <div class="p-5 border-b border-gray-200">
                                    <h3 class="mr-2 text-xl font-bold">Orders</h3>
                                </div>
                                <div class="p-5 overflow-x-auto">
                                    <table class="table-auto w-full">
                                        <thead>
                                            <tr class="text-xs text-gray-500 text-left">
                                                <td class="pb-3 font-medium">
                                                    Order ID
                                                </td>
                                                <td class="pb-3 font-medium">
                                                    Date
                                                </td>
                                                <td class="pb-3 font-medium">
                                                    Status
                                                </td>
                                                <td class="pb-3 font-medium">
                                                    Total
                                                </td>
                                                <td></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="order in membership.orders"
                                                :key="order.order_id"
                                                class="text-xs bg-gray-50">
                                                <td class="py-5 px-6 font-medium">#{{ order.order_id }}</td>
                                                <td class="font-medium">{{ displayMoment(order.date_created, 'LL') }}</td>
                                                <td class="font-medium">
                                                    <span :class="`status ${order.status}`">{{ parseRole(order.status) }}</span>
                                                </td>
                                                <td class="font-medium">
                                                    $ {{ moneyFormat(order.total) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="w-full lg:w-1/3 px-4">
                            <div class="relative bg-white rounded">
                                <div class="p-5 border-b border-gray-200">
                                    <h3 class="mr-2 text-xl font-bold">KindCash</h3>
                                </div>
                                <div class="mb-6 p-5 overflow-y-auto" style="max-height: 450px">
                                    <div class="flex flex-wrap mb-5">
                                        <div class="w-1/2">
                                            <p class="text-md font-medium text-gray-500 mb-1">Available KindCash</p>
                                            <span class="text-sm text-gray-600">$ {{ moneyFormat(membership.cash.points) }}</span>
                                        </div>
                                        <div class="w-1/2">
                                            <p class="text-md font-medium text-gray-500 mb-1">Last Earned</p>
                                            <span class="text-sm text-gray-600">$ {{ moneyFormat(membership.cash.last_earned) }}</span>
                                        </div>
                                    </div>
                                    <p class="text-md font-medium text-gray-500 mb-3">Activity</p>
                                    <div class="relative">
                                        <div class="absolute inset-0 h-full ml-1 w-px px-px bg-gray-300"></div>
                                        <div class="relative">
                                            <!-- Log -->
                                            <div
                                                v-for="log in membership.cash.logs"
                                                :key="log.id"
                                                class="flex items-center mb-8">
                                                <span class="inline-block w-2 h-2 ml-px mr-4 rounded-full bg-green-500"></span>
                                                <div>
                                                    <span v-if="log.event !== 'initialized'" class="mb-1 text-sm font-medium">{{ log.description }}</span>
                                                    <span v-else class="mb-1 text-sm font-medium">KindCash Initialized</span>
                                                    <div class="flex items-center text-xs text-gray-500 mb-1 mt-1">
                                                        <span v-if="log.event === 'redeem'">-</span>
                                                        <span v-else>+</span>
                                                        <p>
                                                            $ {{ moneyFormat(log.points) }}
                                                        </p>
                                                    </div>
                                                    <div class="flex items-center text-xs text-gray-500">
                                                        <span class="mr-1">
                                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M6.99992 0.333328C5.68138 0.333328 4.39245 0.724321 3.29612 1.45686C2.19979 2.18941 1.34531 3.2306 0.840725 4.44877C0.336141 5.66695 0.204118 7.00739 0.461353 8.3006C0.718588 9.5938 1.35353 10.7817 2.28588 11.714C3.21823 12.6464 4.40611 13.2813 5.69932 13.5386C6.99253 13.7958 8.33297 13.6638 9.55114 13.1592C10.7693 12.6546 11.8105 11.8001 12.5431 10.7038C13.2756 9.60747 13.6666 8.31854 13.6666 7C13.6646 5.23249 12.9616 3.53794 11.7118 2.28812C10.462 1.0383 8.76743 0.335294 6.99992 0.333328ZM6.99992 12.3333C5.94509 12.3333 4.91394 12.0205 4.03688 11.4345C3.15982 10.8485 2.47623 10.0155 2.07256 9.04097C1.6689 8.06643 1.56328 6.99408 1.76907 5.95951C1.97485 4.92495 2.48281 3.97464 3.22869 3.22876C3.97457 2.48288 4.92487 1.97493 5.95944 1.76914C6.99401 1.56335 8.06636 1.66897 9.0409 2.07264C10.0154 2.47631 10.8484 3.15989 11.4344 4.03695C12.0205 4.91402 12.3333 5.94516 12.3333 7C12.3316 8.41399 11.7692 9.7696 10.7694 10.7694C9.76953 11.7693 8.41391 12.3317 6.99992 12.3333ZM8.39868 5.42252L7.66659 5.84538V3.66666C7.66659 3.48985 7.59635 3.32028 7.47133 3.19526C7.3463 3.07023 7.17673 2.99999 6.99992 2.99999C6.82311 2.99999 6.65354 3.07023 6.52852 3.19526C6.40349 3.32028 6.33325 3.48985 6.33325 3.66666V7C6.33328 7.11701 6.3641 7.23196 6.42262 7.33329C6.48114 7.43463 6.56529 7.51878 6.66662 7.57729C6.76795 7.63581 6.8829 7.66663 6.99991 7.66666C7.11693 7.66669 7.23189 7.63593 7.33325 7.57747L9.06535 6.57747C9.14123 6.53372 9.20775 6.47545 9.2611 6.40598C9.31446 6.33652 9.3536 6.25722 9.3763 6.17262C9.399 6.08802 9.40481 5.99978 9.3934 5.91294C9.38198 5.82609 9.35357 5.74235 9.30978 5.66649C9.266 5.59063 9.20769 5.52414 9.1382 5.47082C9.0687 5.4175 8.98939 5.3784 8.90478 5.35574C8.82017 5.33309 8.73192 5.32732 8.64509 5.33878C8.55825 5.35024 8.47452 5.37869 8.39868 5.42252Z" fill="#67798E"></path>
                                                            </svg>
                                                        </span>
                                                        <p>{{ displayMoment(log.date, 'LL') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </layout>

</template>
<script>
import { defineComponent } from 'vue'
import Layout from '@/Layouts/Layout.vue'

export default defineComponent({
    props: [
        'membership', 'session'
    ],

    components: {
        Layout
    },

    computed: {
        pageTitle() {
            return `Membership #${this.membership.id}`
        }
    },

    methods: {
        parseRole(role) {
            return role ? role.split('_').join(' ') : ''
        },
    }
})
</script>
