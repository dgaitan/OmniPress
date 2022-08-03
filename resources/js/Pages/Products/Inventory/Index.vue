<template>
    <Layout title="Product Inventory">
        <ListWrapper title="Product Inventory" :fluid="true">
            <template #actions>
                <div class="flex justify-between items-center">
                    <JetInput type="search" placeholder="Search..." v-model="filters.s" />
                    <div>
                        <Button v-if="canUpdate" type="button" color="primary" @click="updateInventory">Update Inventory</Button>
                    </div>
                </div>
            </template>

            <template #table>
                <Table :headers="tableHeaders" :datasets="datasets" @inputChanged="handleInputChange" />
            </template>

            <template #pagination>
                <ListPagination :url="route('kinja.products.inventory')" :params="this.$props" :stateData="this.filters" />
            </template>
        </ListWrapper>
    </Layout>
</template>
<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/Layout.vue'
    import Container from '@/Components/Layouts/Container.vue'
    import Row from '@/Components/Layouts/Row.vue'
    import Column from '@/Components/Layouts/Column.vue'
    import Table from './Partials/Table.vue'
    import ListWrapper from '@/Components/List/ListWrapper.vue'
    import ListPagination from '@/Components/List/ListPagination'
    import JetInput from '@/Jetstream/Input.vue'
    import Button from '@/Components/Button.vue'

    export default defineComponent({
        props: {
            products: {
                type: Object,
                default: {}
            },
            _currentPage: {
                type: Number,
                default: 1,
            },
            _perPage: {
                type: Number,
                default: 50
            },
            total: {
                type: Number,
                default: 0
            },
            nextUrl: {
                type: String,
                default: ''
            },
            prevUrl: {
                type: String,
                default: ''
            },
            _s: {
                type: String,
                default: ''
            }
        },

        components: {
            Layout,
            Container,
            Row,
            Column,
            Table,
            ListWrapper,
            ListPagination,
            JetInput,
            Button
        },

        data() {
            return {
                datasets: [],
                dataToUpdate: {},
                canUpdate: false,
                filters: {
                    s: this._s,
                    // Pagination Data
                    page: this._currentPage,
                    perPage: this._perPage
                },
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
        },

        methods: {
            prepareDatasets() {
                return Object.values(this.products.data).map(product => {
                    const data = {}

                    Object.keys(product).map(key => {
                        data[key] = {
                            key: key,
                            value: product[key],
                            currentValue: product[key],
                            hasChanged: false
                        }
                    })

                    return data
                })
            },

            handleInputChange(value, key) {
                this.datasets[key]['stock'].value = value
                this.datasets[key]['stock'].hasChanged = parseInt(value) !== parseInt(this.datasets[key]['stock'].currentValue)

                if (this.datasets[key]['stock'].hasChanged) {
                    this.dataToUpdate[key] = this.datasets[key]
                } else {
                    delete this.dataToUpdate[key]
                }


                this.canUpdate = Object.keys(this.dataToUpdate).length > 0;
            },

            updateInventory() {
                this.$inertia.post(route('kinja.products.inventoryUpdate'), { products: Object.values(this.dataToUpdate) }, {
                    replace: true,
                    onSuccess: () => {
                        this.dataToUpdate = {}
                        this.canUpdate = false
                    }
                })
            }
        }
    })
</script>
