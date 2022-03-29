<template>
    <div>
        <Link @click="back($event)" class="inline-flex items-center mb-2 text-sm">
            <unicon name="arrow-left" fill="dark" class="mr-2" /> Back
        </Link>
        <div class="flex">
            <h2 class="text-2xl font-bold">{{ product.name }}</h2>
            <Status class="ml-3" :status="product.status" />
        </div>
        <div class="flex mt-2">
            <p class="text-sm text-gray-500 mr-4">SKU - <span class="text-gray-900">{{ product.sku }}</span></p>
            <p class="text-sm text-gray-500">STOCK - <span class="text-gray-900">{{ product.stock_quantity }}</span></p>
        </div>
    </div>
</template>
<script>
import { defineComponent } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import Status from '@/Components/Status.vue'

export default defineComponent({
    props: {
        product: Object
    },

    components: {
        Status,
        Link
    },

    methods: {
        back(e) {
            e.preventDefault();
            return window.history.back()
        }
    }
})
</script>
