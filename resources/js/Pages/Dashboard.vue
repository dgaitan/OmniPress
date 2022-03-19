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
            <Row>
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
                        percentage="0" />
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

    export default defineComponent({
        props: ['user', 'orders', 'customers'],
        components: {
            Layout,
            Welcome,
            Stats,
            Stat,
            Container,
            Row,
            Column
        },

        methods: {
            logout() {
                this.$inertia.post(route('logout'));
            },
        }
    })
</script>
