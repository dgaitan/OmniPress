<template>
    <Box headline="Information">
        <Column class="mb-5">
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
        </Column>
        <Column :mdSize="12" class="mb-5">
            <p class="text-md font-medium text-gray-800 mb-2">Email</p>
            <a :href="order.billing.email" class="text-sm text-cyan-500">{{ order.billing.email }}</a>
        </Column>
        <Column :mdSize="6" class="mb-5">
            <p class="text-md font-medium text-gray-800 mb-2">Billing</p>
            <div class="text-sm text-gray-600" v-html="order.billing_address"></div>
        </Column>
        <Column :mdSize="6" class="mb-3">
            <p class="text-md font-medium text-gray-800 mb-2">Shipping</p>
            <div class="text-sm text-gray-600" v-html="order.shipping_address"></div>
        </Column>
    </Box>
</template>
<script>
import { defineComponent } from 'vue'
import Box from '@/Components/Content/Box.vue'
import Column from '@/Components/Layouts/Column.vue'

export default defineComponent({
    props: {
        order: Object
    },

    components: {
        Box,
        Column
    }
})
</script>
