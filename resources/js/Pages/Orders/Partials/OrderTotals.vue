<template>
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
</template>
<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: {
        order: Object
    }
})
</script>
