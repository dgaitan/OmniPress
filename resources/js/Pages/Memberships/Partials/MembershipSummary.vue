<template>
    <Box :headline="`Membership #${membership.id}`">
        <Column :mdSize="6" class="mb-5">
            <p class="text-md font-medium text-gray-500 mb-2">Status</p>
            <Status :status="membership.status" />
        </Column>
        <Column :mdSize="6" class="mb-5">
            <p class="text-md font-medium text-gray-500 mb-2">Shipping Status</p>
            <Status :status="membership.shipping_status" />
        </Column>
        <Column :mdSize="6" class="mb-5">
            <p class="text-md font-medium text-gray-500 mb-1">Start At</p>
            <span class="text-sm text-gray-600">{{ displayMoment(membership.start_at, 'LL') }}</span>
        </Column>
        <Column :mdSize="6" class="mb-5">
            <p class="text-md font-medium text-gray-500 mb-1">End At</p>
            <span class="text-sm text-gray-600">{{ displayMoment(membership.end_at, 'LL') }}</span>
        </Column>
        <Column :mdSize="6" class="mb-5">
            <p class="text-md font-medium text-gray-500 mb-2">Last Gift Product Selected</p>
            <div class="flex" style="width:300px;" v-if="membership.status !== 'awaiting_pick_gift' && membership.giftProduct">
                <img
                    v-if="!membership.giftProduct.image"
                    class="w-20 h-20 mr-4 object-cover rounded-md"
                    src="https://images.unsplash.com/photo-1559893088-c0787ebfc084?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1050&amp;q=80" alt="">
                <img v-else :src="membership.giftProduct.image.src" class="w-20 h-20 mr-4 object-cover rounded-md" >
                <div>
                    <p class="text-sm font-medium mb-1">
                        <strong class="text-gray-400">ID {{ membership.giftProduct.product_id }}</strong>
                    </p>
                    <p class="text-sm font-medium mb-2">{{ membership.giftProduct.name }}</p>
                    <p class="text-xs text-gray-500">SKU: {{ membership.giftProduct.sku }}</p>
                </div>
            </div>
            <div v-else>Not defined yet (Member needs to pick gift)</div>
        </Column>
        <Column :mdSize="6" class="mb-5">
            <p class="text-md font-medium text-gray-500 mb-2">Last Payment Date</p>
            <span class="text-sm text-gray-600">{{ displayMoment(membership.last_payment_intent, 'LL') }}</span>
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
            membership: Object
        },

        components: {
            Column,
            Box,
            Status
        }
    })
</script>
