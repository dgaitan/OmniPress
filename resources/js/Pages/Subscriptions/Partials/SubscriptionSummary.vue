<template>
    <Box :headline="`Subscription #${subscription.id}`">
        <Column :mdSize="6" class="mb-5">
            <p class="text-md font-medium text-gray-500 mb-2">Status</p>
            <Status :status="subscription.status" />
        </Column>
        <Column :mdSize="6" class="mb-5">
            <p class="text-md font-medium text-gray-500 mb-2">Start Date</p>
            <span class="text-sm text-gray-600">{{ displayMoment(subscription.start_date, 'LL') }}</span>
        </Column>
        <Column :mdSize="6" class="mb-5">
            <p class="text-md font-medium text-gray-500 mb-1">Last Payment Date</p>
            <span class="text-sm text-gray-600">{{ displayMoment(subscription.last_payment, 'LL') }}</span>
        </Column>
        <Column :mdSize="6" class="mb-5">
            <p class="text-md font-medium text-gray-500 mb-1">Next Payment Date</p>
            <span class="text-sm text-gray-600">{{ displayMoment(subscription.end_date, 'LL') }}</span>
        </Column>
        <Column class="mb-5">
            <p class="text-md font-medium text-gray-500 mb-2">Items</p>
            <div class="flex" style="width:300px;" v-for="(item, key) in subscription.items" :key="key">
                <img :src="item.product.image" class="w-20 h-20 mr-4 object-cover rounded-md" >
                <div>
                    <p class="text-sm font-medium mb-1">
                        <strong class="text-gray-400">ID {{ item.product.id }}</strong>
                    </p>
                    <p class="text-sm font-medium mb-2">{{ item.product.name }}</p>
                    <p class="text-xs text-gray-500">SKU: {{ item.product.sku }}</p>
                </div>
            </div>
        </Column>
    </Box>
</template>
<script>
    import { defineComponent } from 'vue'
    import Column from '@/Components/Layouts/Column.vue'
    import Box from '@/Components/Content/Box.vue'
    import Status from '@/Components/Status.vue'

    export default defineComponent({
        props: {
            subscription: Object
        },

        components: {
            Column,
            Box,
            Status
        }
    })
</script>
