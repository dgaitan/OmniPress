<template>
    <div class="flex flex-wrap items-start mb-5 pb-5" v-if="item.product">
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
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: {
        item: Object
    }
})
</script>
