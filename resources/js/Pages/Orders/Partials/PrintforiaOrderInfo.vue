<template>
    <Box headline="Information">
        <Column class="mb-5">
            <Label>Kindhumans Order ID</Label>
            <Link class="text-cyan-500 font-medium" :href="order.order.permalink">#{{ order.order.order_id }}</Link>
        </Column>
        <Column class="mb-5">
            <Label>Customer Email</Label>
            <a class="text-cyan-500 font-medium" :href="`mailto:${order.ship_to_address.email}`">{{ order.ship_to_address.email }}</a>
        </Column>
        <Column :mdSize="6" class="mb-5">
            <Label>Ship to address</Label>
            <div class="text-sm text-gray-600" v-html="order.ship_to_address_formatted"></div>
        </Column>
        <Column :mdSize="6" class="mb-3">
            <Label>Return to address</Label>
            <div class="text-sm text-gray-600" v-html="order.return_to_address_formatted"></div>
        </Column>
        <Column class="mb-5" v-if="order.carrier">
            <Label>Shipping Carrier</Label>
            {{ order.carrier }}
        </Column>
        <Column class="mb-5" v-if="order.tracking_url && order.tracking_number">
            <Label>Tracking Number</Label>
            <a class="text-cyan-500 font-medium" :href="order.tracking_url">{{ order.tracking_number }}</a>
        </Column>
    </Box>
    <Box headline="Notes">
        <Column class="mb-5" v-for="note in order.notes" :key="note.id">
            <div class="p-4 border border-gray-200 rounded bg-gray-100">
                <h4 class="font-medium text-md">{{ note.title }}</h4>
                <p class="text-sm text-gray-500 mb-4">{{ note.body }}</p>
                <p class="flex item-center text-sm text-gray-400"><ClockIcon class="w-4 h-4 mr-2" /> {{ note.note_date }}</p>
            </div>
        </Column>
    </Box>
</template>
<script>
import { defineComponent } from 'vue'
import { Link } from '@inertiajs/inertia-vue3';
import Box from '@/Components/Content/Box.vue'
import Column from '@/Components/Layouts/Column.vue'
import Label from '@/Components/Content/Label.vue'
import { ClockIcon } from '@heroicons/vue/solid'

export default defineComponent({
    props: {
        order: Object
    },

    components: {
        Box,
        Column,
        Label,
        ClockIcon,
        Link
    }
})
</script>
