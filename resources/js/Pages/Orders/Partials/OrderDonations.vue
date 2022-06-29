<template>
    <Box headline="Donations">
        <Column v-for="donation in order.donations" :key="donation.id" class="mb-2">
            <div class="text-sm text-gray-600 bg-gray-100 p-3 rounded cursor-pointer">
                <span class="block mb-2">
                    {{ donation.cause.name }} ({{ donation.cause.type }})
                </span>
                <span class="flex items-center">
                    <strong class="inline-block mr-2">Donated:</strong>
                    {{ donation.amount.format }}
                </span>
            </div>
        </Column>
        <Column class="mt-2">
            <span class="block">
                <strong class="inline-block mr-2">Total Donated:</strong>
                {{ order.total_donations }}
            </span>
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
        Column,
    }
})
</script>
