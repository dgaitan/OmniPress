<template>
    <Box headline="Orders">
        <Column>
            <table class="table-auto w-full py-4">
                <thead>
                    <tr class="text-xs text-gray-500 text-left">
                        <td class="pb-3 font-medium">
                            Order ID
                        </td>
                        <td class="pb-3 font-medium">
                            Date
                        </td>
                        <td class="pb-3 font-medium">
                            Status
                        </td>
                        <td class="pb-3 font-medium">
                            Total
                        </td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="order in membership.orders"
                        :key="order.order_id"
                        class="text-xs bg-gray-50">
                        <td class="py-5 px-6 font-medium">#{{ order.order_id }}</td>
                        <td class="font-medium">{{ displayMoment(order.date_completed, 'LL') }}</td>
                        <td class="font-medium">
                            <Status :status="order.status" />
                        </td>
                        <td class="font-medium">
                            $ {{ moneyFormat(order.total) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </Column>
    </Box>
</template>
<script>
    import { defineComponent } from 'vue'
    import Box from '@/Components/Content/Box.vue'
    import Status from '@/Components/Status.vue'
    import Column from '@/Components/Layouts/Column.vue'

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
