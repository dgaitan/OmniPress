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
                        <td class="font-medium">{{ order.date }}</td>
                        <td class="font-medium">
                            <Status :status="order.status" />
                        </td>
                        <td class="font-medium">
                            $ {{ moneyFormat(order.total) }}
                        </td>
                        <td class="font-medium">
                            <jet-dropdown align="right" width="48">
                                <template #trigger>
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
	                                    Actions
	                                </button>
                                </template>
                                <template #content>
                                    <div class="">
                                        <!-- Team Settings -->
	                                    <jet-dropdown-link :href="order.permalink">
	                                        Show
	                                    </jet-dropdown-link>
                                        <jet-dropdown-link :href="order.storePermalink" as="a" target="_blank">Show on kindhumans</jet-dropdown-link>
                                    </div>
                                </template>
                            </jet-dropdown>
                        </td>
                    </tr>
                </tbody>
            </table>
        </Column>
    </Box>
</template>
<script>
    import { defineComponent } from 'vue'
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
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
            Status,
            JetDropdown,
            JetDropdownLink
        }
    })
</script>
