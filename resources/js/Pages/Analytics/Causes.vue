<template>
    <layout title="Analytics">
        <Container>
            <Row class="mb-5">
                <Column>
                    <Heading>Cause Stats</Heading>
                </Column>
            </Row>
            <Row>
                <Column :mdSize="6">
                    <div class="flex flex-col">
                        <span class="font-medium text-xs text-gray-500 mb-2">Total Donated</span>
                        <h4 class="font-medium text-3xl text-gray-800">{{ stats.totalDonated.formatted }}</h4>
                    </div>
                </Column>
                <Column :mdSize="6">
                </Column>
                <Column><hr class="pt-5 mt-5" /></Column>
            </Row>
            <Row>
                <Column :mdSize="4">
                    <div v-for="causeDonation in stats.causeDonations" :key="causeDonation.cause.id">
                        <div class="flex content-center justify-between mb-5 w-full">
                            <div>
                                <h4 class="font-medium text-sm text-gray-700">{{ causeDonation.cause.name }}</h4>
                                <span class="text-xs text-gray-500">{{ causeDonation.cause.cause_type_label }}</span>
                            </div>
                            <span>{{ causeDonation.donated.formatted }}</span>
                        </div>
                    </div>
                </Column>
                <Column :mdSize="8">
                    <BarChart :chartData="causesBarChartData" />
                </Column>
            </Row>
            <Row>
                <Column :mdSize="4">
                </Column>
                <Column :mdSize="8">
                    <BarChart :chartData="customerBarChartData" />
                </Column>
            </Row>
        </Container>
    </layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3'
    import Layout from '@/Layouts/Layout.vue'
    import Container from '@/Components/Layouts/Container.vue'
    import Row from '@/Components/Layouts/Row.vue'
    import Column from '@/Components/Layouts/Column.vue'
    import Heading from '@/Components/Content/Headline.vue'
    import BarChart from '@/Components/Charts/BarChart.vue'

    export default defineComponent({
        props: [
            'sessions',
            'stats'
        ],

        components: {
            Layout,
            Link,
            Container,
            Row,
            Column,
            Heading,
            BarChart
        },

        data() {
            return {}
        },

        computed: {
            causesBarChartData() {
                let data = this.stats.causeDonations.map(item => {
                    return {
                        x: `${item.cause.name} - ${item.donated.formatted}`,
                        y: parseFloat(parseInt(item.donated.amount) / 100).toFixed(2)
                    }
                })

                return {
                    datasets: [
                        {
                            label: 'Cause Stats',
                            data: data,
                            backgroundColor: 'rgb(59 130 246 / 0.5)'
                        }
                    ]
                }
            },

            customerBarChartData() {
                return {
                    labels: this.stats.customerDonations.map(item => {
                        return `${item.customer.first_name} - ${item.donated.formatted}`
                    }),
                    datasets: [
                        {
                            label: 'Customer Stats',
                            data: this.stats.customerDonations.map(item => {
                                return parseFloat(parseInt(item.donated.amount) / 100).toFixed(2)
                            }),
                            backgroundColor: 'rgb(59 130 246 / 0.5)'
                        }
                    ]
                }
            }
        },

        methods: {


        }
    })
</script>
