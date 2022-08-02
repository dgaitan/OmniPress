<template>
    <div class="bg-white rounded mb-5 border border-gray-200 shadow-gray-200 shadow-sm p-5">
        <Bar
            :chart-options="chartOptions"
            :chart-data="chartData"
            :chart-id="chartId"
            :dataset-id-key="datasetIdKey"
            :plugins="plugins"
            :css-classes="cssClasses"
            :styles="styles"
            :width="width"
            :height="height"
        />
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Bar } from 'vue-chartjs'
    import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

    ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

    export default defineComponent({
        name: 'BarChart',
        components: { Bar },
        props: {
            chartId: {
                type: String,
                default: 'bar-chart'
            },
            datasetIdKey: {
                type: String,
                default: 'label'
            },
            width: {
                type: Number,
                default: 400
            },
            height: {
                type: Number,
                default: 400
            },
            cssClasses: {
                default: '',
                type: String
            },
            styles: {
                type: Object,
                default: () => {}
            },
            plugins: {
                type: Object,
                default: () => {}
            },
            chartData: {
                type: Object,
                default: {
                    datasets: [{
                        label: 'Sample Label',
                        data: [{y:'Sales', x:20}, {y:'Revenue', x:10}],
                        backgroundColor: 'rgb(59 130 246 / 0.5)'
                    }]
                }
            },
            chartOptions: {
                type: Object,
                default: {
                    response: true,
                }
            }
        },
        // data() {
        //     return {
        //         chartData: {
        //             labels: [ 'January', 'February', 'March' ],
        //             datasets: [ { data: [40, 20, 12] } ]
        //         },
        //         chartOptions: {
        //             responsive: true
        //         }
        //     }
        // }
    })
</script>
