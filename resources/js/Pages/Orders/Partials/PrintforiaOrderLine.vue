<template>
    <div class="flex flex-wrap items-start mb-5 pb-5">
        <img v-if="item.product.image" :src="item.product.image.src" class="h-40 w-40 mr-4 object-cover rounded-md" />
        <img
            v-else
            class="w-40 h-40 mr-4 object-cover rounded-md"
            src="https://images.unsplash.com/photo-1559893088-c0787ebfc084?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1050&amp;q=80" alt="">
        <div>
            <p class="text-sm font-medium mb-1">
                <strong class="text-gray-400">ID {{ item.product.product_id }}</strong>
            </p>
            <p class="text-sm font-medium mb-2">{{ item.product.name }}</p>
            <p class="text-xs text-gray-500 mb-2"><span class="text-gray-700 font-medium">SKU:</span> {{ item.product.sku }}</p>
            <p class="text-xs text-gray-500 mb-2"><span class="text-gray-700 font-medium">PRINTFORIA SKU:</span> {{ item.sku }}</p>
            <p class="text-xs text-gray-500 mb-2"><span class="text-gray-700 font-medium">QUANTITY:</span> {{ item.quantity }}</p>
            <p class="text-xs text-gray-500 mb-2"><span class="text-gray-700 font-medium">PRINTFORIA ITEM ID:</span> {{ item.item_id }}</p>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue'
import Column from '@/Components/Layouts/Column.vue'
import Row from '@/Components/Layouts/Row.vue'

export default defineComponent({
    props: {
        item: Object
    },

    components: {
        Column,
        Row
    }
})
</script>
