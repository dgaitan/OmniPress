<template>
    <layout :title="pageTitle">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Heading -->
            <div class="flex flex-wrap mb-5">
                <div class="w-full md:w-2/3 px-4 mb-4 md:mb-0">
                    <div class="flex">
                        <h2 class="text-2xl font-bold">Order #{{ order.order_id }}</h2>
                        <Status class="ml-3" :status="order.status" />
                    </div>
                    <p class="text-sm text-gray-500">Date -  <span class="text-gray-900">{{ order.date }}</span></p>
                </div>
                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">

                </div>
            </div>

            <!-- Content -->
            <div class="flex flex-wrap">
                <div class="w-full md:w-2/3 px-4 mb-4">
                    <div class="bg-white rounded mb-1">
                        <div class="p-5 border-b border-gray-200">
                            <h3 class="mr-2 text-xl font-bold">Order Lines</h3>
                        </div>
                        <div class="p-5">
                            <div
                                v-for="item in order.items"
                                :key="item.id"
                                class="flex flex-wrap items-start mb-5 pb-5">
                                <img v-if="item.product.images && item.product.images.length > 0" :src="item.product.images[0].src" class="h-40 w-40 mr-4 object-cover rounded-md" >
                                <img
                                    v-else
                                    class="w-40 h-40 mr-4 object-cover rounded-md"
                                    src="https://images.unsplash.com/photo-1559893088-c0787ebfc084?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1050&amp;q=80" alt="">
                                <div>
                                    <p class="text-sm font-medium mb-1">
                                        <strong class="text-gray-400">ID {{ item.product.product_id }}</strong>
                                    </p>
                                    <p class="text-sm font-medium mb-2">{{ item.product.name }}</p>
                                    <p class="text-xs text-gray-500">SKU: {{ item.product.sku }}</p>
                                    <div class="mt-5">
                                        <span class="text-sm font-semibold text-gray-800">Quantity <span class="text-gray-500 ml-1">{{ item.quantity }}</span></span>
                                        <span class="text-gray-400 mx-3">|</span>
                                        <span class="text-sm font-semibold text-gray-800">Total <span class="text-gray-500 ml-1">$ {{ moneyFormat(item.total) }}</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Resume -->
                    <div style="max-width: 400px;" class="ml-auto mt-5">
                        <div class="w-full flex justify-between pb-2 mb-2">
                            <p class="text-md font-medium text-gray-800">Subtotal</p>
                            <p class="text-md font-medium text-gray-500">$ {{ moneyFormat(order.sub_total) }}</p>
                        </div>
                        <div v-if="parseInt(order.discount_total) > 0" class="w-full flex justify-between pb-2 mb-2">
                            <p class="text-md font-medium text-gray-800">
                                Discount
                                <span
                                    v-for="coupon in order.coupon_lines"
                                    :data-product-id="coupon.id"
                                    :key="coupon.id"
                                    class="py-1 px-2 text-xs text-gray-700 bg-gray-200 mr-2 mb-2 rounded-full">
                                    {{ coupon.code }}
                                </span>
                            </p>
                            <p class="text-md font-medium text-gray-500">- $ {{ moneyFormat(order.discount_total) }}</p>
                        </div>
                        <div class="w-full flex justify-between pb-2 mb-2">
                            <p class="text-md font-medium text-gray-800">Shipping</p>
                            <p class="text-md font-medium text-gray-500">$ {{ moneyFormat(order.shipping_total) }}</p>
                        </div>
                        <div class="w-full flex justify-between pb-2 mb-2">
                            <p class="text-md font-medium text-gray-800">Tax</p>
                            <p class="text-md font-medium text-gray-500">$ {{ moneyFormat(order.total_tax) }}</p>
                        </div>
                        <div class="w-full flex justify-between pt-3 mt-3 border-t border-gray-300">
                            <p class="text-md font-medium text-gray-800">Total</p>
                            <p class="text-md font-medium text-gray-500">$ {{ moneyFormat(order.total) }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-4 mb-4">
                    <div class="bg-white rounded mb-10">
                        <div class="p-5 border-b border-gray-200">
                            <h3 class="mr-2 text-xl font-bold">Information</h3>
                        </div>
                        <div class="p-5 flex flex-wrap">
                            <div class="w-full mb-5">
                                <p class="text-md font-medium text-gray-800 mb-2">Customer</p>
                                <div v-if="order.customer" class="text-sm text-gray-600 flex items-center">
                                    <img v-if="order.customer.avatar_url" :src="order.customer.avatar_url" class="h-10 w-10 mr-4 object-cover rounded-full" />
                                    <img
                                        v-else
                                        class="w-20 h-20 mr-4 object-cover rounded-full"
                                        src="https://images.unsplash.com/photo-1559893088-c0787ebfc084?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1050&amp;q=80" alt="">
                                    <div>
                                        <p class="text-sm font-medium mb-1">
                                            <strong class="text-gray-800">{{ order.customer.first_name }} {{ order.customer.last_name }}</strong>
                                        </p>
                                    </div>
                                </div>
                                <div v-else class="text-sm text-gray-600">Guest</div>
                            </div>
                            <div class="w-full mb-5">
                                <p class="text-md font-medium text-gray-800 mb-2">Email</p>
                                <a :href="order.billing.email" class="text-sm text-cyan-500">{{ order.billing.email }}</a>
                            </div>
                            <div class="w-1/2 mb-5">
                                <p class="text-md font-medium text-gray-800 mb-2">Billing</p>
                                <div class="text-sm text-gray-600" v-html="order.billing_address"></div>
                            </div>
                            <div class="w-1/2 mb-3">
                                <p class="text-md font-medium text-gray-800 mb-2">Shipping</p>
                                <div class="text-sm text-gray-600" v-html="order.shipping_address"></div>
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
import Status from '@/Components/Status.vue'

export default defineComponent({
    props: [
        'order', 'session'
    ],

    components: {
        Layout,
        Status
    },

    computed: {
        pageTitle() {
            return `Order #${this.order.order_id}`
        }
    },

    methods: {
        parseRole(role) {
            return role ? role.split('_').join(' ') : ''
        },
    }
})
</script>
