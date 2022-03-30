<template>
    <layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <Container>
            <Row>
                <Column :mdSize="6">
                    <div class=" rounded mb-5 ">
                        <h3 class="mr-2 text-xl font-medium">
                            Welcome, {{ user.name }}
                        </h3>
                        <form @submit.prevent="logout">
                            <span class="block mt-2 text-sm leading-5 text-gray-700 cursor-pointer">
                                Log Out
                            </span>
                        </form>
                    </div>
                </Column>
                <Column :mdSize="6">
                </Column>
            </Row>
            <Row class="pb-3">
                <Column :mdSize="4">
                    <Stat
                        title="Incomes"
                        :value="`$ ${moneyFormat(orders.net_sales)}`"
                        :percentage="orders.percentage" />
                </Column>
                <Column :mdSize="4">
                    <Stat
                        title="Orders"
                        :value="orders.total_orders"
                        :percentage="orders.percentage_count" />
                </Column>
                <Column :mdSize="4">
                    <Stat
                        title="New Customers"
                        :value="customers.total_customers"
                        :percentage="customers.percentage" />
                </Column>
            </Row>
            <Row>
                <Column :mdSize="8">
                    <Box
                        :headline="`Latest Orders (${orders.total_orders_to_fulfill} orders to fulfill)`"
                        :button="{link: route('kinja.orders.index'), label: 'See all'}">
                        <Column v-if="orders.latest_orders.data.length > 0">
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
                                        v-for="order in orders.latest_orders.data"
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
                </Column>
                <Column :mdSize="4">
                    <Stat
                        title="New Memberships"
                        :value="memberships.total_memberships"
                        :percentage="memberships.percentage" />
                </Column>
            </Row>
        </Container>
    </layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/Layout.vue'
    import Welcome from '@/Jetstream/Welcome.vue'
    import Stats from '@/Components/Stats.vue'
    import Container from '@/Components/Layouts/Container.vue'
    import Row from '@/Components/Layouts/Row.vue'
    import Column from '@/Components/Layouts/Column.vue'
    import Stat from '@/Components/Widgets/Stat.vue'
    import Status from '@/Components/Status.vue'
    import Box from '@/Components/Content/Box.vue'
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'

    export default defineComponent({
        props: ['user', 'orders', 'customers', 'memberships'],
        components: {
            Layout,
            Welcome,
            Stats,
            Stat,
            Container,
            Row,
            Column,
            Box,
            JetDropdownLink,
            JetDropdown,
            Status
        },

        methods: {
            logout() {
                this.$inertia.post(route('logout'));
            },
        }
    })
</script>
