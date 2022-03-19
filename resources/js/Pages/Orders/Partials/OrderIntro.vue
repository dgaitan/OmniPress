<template>
    <div>
        <Link @click="back($event)" class="inline-flex items-center mb-2 text-sm">
            <unicon name="arrow-left" fill="dark" class="mr-2" /> Back
        </Link>
        <div class="flex">
            <h2 class="text-2xl font-bold">Order #{{ order.order_id }}</h2>
            <Status class="ml-3" :status="order.status" />
        </div>
        <p class="text-sm text-gray-500">Date -  <span class="text-gray-900">{{ order.date }}</span></p>
    </div>
</template>
<script>
import { defineComponent } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import Status from '@/Components/Status.vue'

export default defineComponent({
    props: {
        order: Object
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
