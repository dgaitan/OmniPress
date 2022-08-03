<template>
    <Layout title="Product Inventory">
        <Container :fluid="true">
            <Row>
                <Column>
                    <Table :headers="tableHeaders" :datasets="datasets" />
                </Column>
            </Row>
        </Container>
    </Layout>
</template>
<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/Layout.vue'
    import Container from '@/Components/Layouts/Container.vue'
    import Row from '@/Components/Layouts/Row.vue'
    import Column from '@/Components/Layouts/Column.vue'
    import Table from './Partials/Table.vue'

    export default defineComponent({
        props: {
            products: {
                type: Object,
                default: {}
            }
        },

        components: {
            Layout,
            Container,
            Row,
            Column,
            Table
        },

        data() {
            return {
                datasets: []
            }
        },

        computed: {
            tableHeaders() {
                return [
                    'ID', 'Parent ID', 'Name', 'Type', 'SKU', 'Stock Qty'
                ]
            }
        },

        created() {
            this.datasets = this.prepareDatasets()
            console.log(this.datasets)
        },

        methods: {
            prepareDatasets() {
                return Object.values(this.products.data).map(product => {
                    return Object.keys(product).map(key => {
                        return {
                            key: key,
                            value: product[key],
                            currentValue: product[key],
                            hasChanged: false
                        }
                    })
                })
            }
        }
    })
</script>
