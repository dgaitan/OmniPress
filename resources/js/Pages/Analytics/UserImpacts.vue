<template>
    <layout title="Analytics">
        <Container :fluid="true">
            <Row class="mb-5">
                <Column>
                    <Heading>User Impact Stats</Heading>
                </Column>
            </Row>
            <Row>
                <Column :mdSize="6">
                    <div class="flex flex-col">
                        <span class="font-medium text-xs text-gray-500 mb-2">Total Donated</span>
                        <h4 class="font-medium text-3xl text-gray-800">{{ stats.totalDonated.formatted }}</h4>
                    </div>
                </Column>
                <Column :mdSize="6" class="flex justify-end">
                    <PeriodPicker :url="route('kinja.analytics.userImpacts')" :periods="periods" :currentPeriod="period" />
                </Column>
                <Column><hr class="pt-5 mt-5" /></Column>
            </Row>
            <Row>
                <Column :mdSize="3">
                    <div v-if="stats.userImpacts.length > 0">
                        <div v-for="impact in stats.userImpacts" :key="impact.customer.id">
                            <div class="flex content-center justify-between mb-5 w-full">
                                <div class="flex">
                                    <span class="inline-block rounded-full w-4 h-4 mr-2 mt-1" :style="`background-color: ${impact.color}`"></span>
                                    <div>
                                        <h4 class="font-medium text-sm text-gray-700">{{ impact.customer.first_name }}</h4>
                                        <span class="text-xs text-gray-500">{{ impact.customer.email }}</span>
                                    </div>
                                </div>
                                <span>{{ impact.donated.formatted }}</span>
                            </div>
                        </div>
                    </div>
                </Column>
                <Column :mdSize="9">
                    <div v-if="stats.userImpacts.length > 0">
                        <LineChart :chartData="userImpactsBarChartData" />
                    </div>
                    <div v-else>
                        <h4>Data Not Found</h4>
                    </div>
                </Column>
            </Row>
            <Row>
                <Column :mdSize="4">
                </Column>
                <Column :mdSize="8">
                    <!-- <BarChart chartId="line-chart" :chartData="customerBarChartData" /> -->
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
    import LineChart from '@/Components/Charts/LineChart.vue'
    import PeriodPicker from './Partials/PeriodPicker.vue'

    export default defineComponent({
        props: [
            'sessions',
            'stats',
            'periods',
            'period',
            'perPage'
        ],

        components: {
            Layout,
            Link,
            Container,
            Row,
            Column,
            Heading,
            BarChart,
            PeriodPicker,
            LineChart
        },

        data() {
            return {}
        },

        computed: {
            userImpactsBarChartData() {
                const labels = this.stats.userImpacts[0].intervals.map(interval => {
                    let label = interval.label.split(' ')

                    if (label.length === 1) {
                        return label[0]
                    }

                    return parseInt(label[1]) === 1 ? interval.label : label[1]
                })

                const datasets = this.stats.userImpacts.map(item => {
                    return {
                        label: item.customer.first_name,
                        backgroundColor: item.color,
                        borderColor: item.color,
                        data: item.intervals.map(i => {
                            return parseFloat(parseInt(i.amount.amount) / 100).toFixed(2)
                        })
                    }
                })

                return { labels, datasets }
            },
        },

        methods: {


        }
    })
</script>
