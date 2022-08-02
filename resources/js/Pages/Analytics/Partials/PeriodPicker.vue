<template>
    <JetDropdown align="right" width="96" :closeOnClickItem="false" :show="show">
        <template #trigger>
            <Button size="sm" color="secondary" class="px-3 py-2 leading-7 ml-2 inline-flex" @click="show = !show">
                <FilterIcon class="w-5 h-5 mr-1" />
                Date Range
            </Button>
        </template>
        <template #content>
            <div>
                <div class="block px-4 py-2 text-xs text-gray-400">
                    Select a date range.
                </div>
                <Column class="mb-2 pb-2 border-b border-gray-200 flex">
                    <Button type="button" :color="buttonColorType('preset')" class=" w-3/6 rounded-none justify-center" @click="toggleFilterType('preset')">Presets</Button>
                    <Button type="button" :color="buttonColorType('custom')" class=" w-3/6 rounded-none justify-center" @click="toggleFilterType('custom')">Custom</Button>
                </Column>
                <Column>
                    <Row v-if="filterType === 'preset'">
                        <Column :mdSize="6" v-for="period in validPeriods" :key="period.slug">
                            <JetDropdownLink  as="button" @click="filter(period.slug)" :class="buttonActive(period.slug)">
                                {{ period.label }}
                            </JetDropdownLink>
                        </Column>
                    </Row>
                    <Row v-else>
                        <Column>
                            <Datepicker v-model="dateRange" class="w-full" format="MM/dd/yyyy" range />
                        </Column>
                    </Row>
                </Column>
                <div class="block px-4 py-2 text-xs text-gray-400">
                    Max # of Items in stats
                </div>
                <Column class="mb-2">
                    <JetInput type="number" class="w-full" v-model="perPage"/>
                </Column>
                <Column class="mb-2">
                    <Button type="button" color="primary" class="w-full justify-center" @click="updateFilters">Update</Button>
                </Column>
            </div>
        </template>
    </JetDropdown>
</template>
<script>
    import { defineComponent } from "vue";
    // import Datepicker from 'vue3-datepicker'
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
    import { FilterIcon } from '@heroicons/vue/outline'
    import Button from '@/Components/Button.vue'
    import Row from '@/Components/Layouts/Row.vue'
    import Column from '@/Components/Layouts/Column.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import Datepicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css'

    export default defineComponent({
        components: {
            JetDropdown,
            JetDropdownLink,
            FilterIcon,
            Button,
            Row,
            Column,
            JetInput,
            Datepicker
        },

        props: {
            url: {
                type: String,
                default: '',
            },
            periods: {
                type: Object,
                default: []
            },
            currentPeriod: {
                type: String,
                default: 'month_to_date'
            }
        },

        data() {
            return {
                filterType: 'preset', // two options: preset and custom
                show: false,
                filterBy: 'week_to_date',
                from: '',
                to: '',
                perPage: 10,
                dateRange: new Date()
            }
        },

        created() {
            this.filterType = this.$page.props.filterType
            this.perPage = this.$page.props.perPage

            if (this.filterType === 'custom') {
                this.dateRange = [new Date(this.$page.props.fromDate), new Date(this.$page.props.toDate)]
            }

            this.filterBy = this.currentPeriod
        },

        computed: {
            validPeriods() {
                return Object.keys(this.periods).map(period => {
                    return {
                        slug: period,
                        label: this.periods[period]
                    }
                })
            }
        },

        methods: {
            filter(period) {
                this.filterBy = period
                this.updateFilters()
            },

            buttonActive(period) {
                if (period === this.filterBy) {
                    return 'bg-gray-100';
                }

                return ''
            },

            toggleFilterType(type) {
                this.filterType = type
            },

            buttonColorType(type) {
                return type === this.filterType ? 'primary' : 'secondary'
            },

            updateFilters() {
                const filters = {
                    filterType: this.filterType,
                    perPage: this.perPage
                }

                if (filters.filterType === 'preset') {
                    filters['filterBy'] = this.$props.currentPeriod === this.filterBy
                        ? this.$props.currentPeriod
                        : this.filterBy
                } else {
                    filters['fromDate'] = this.dateRange[0]
                    filters['toDate'] = this.dateRange[1]
                }

                this.$inertia.get(this.url, filters, {
                    replace: true,
                })
            }
        }
    })
</script>
