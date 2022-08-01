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
                <Column>
                    <Row>
                        <Column :mdSize="6" v-for="period in validPeriods" :key="period.slug">
                            <JetDropdownLink  as="button" @click="filter(period.slug)" :class="buttonActive(period.slug)">
                                {{ period.label }}
                            </JetDropdownLink>
                        </Column>
                    </Row>
                </Column>
                <div class="block px-4 py-2 text-xs text-gray-400">
                    Max # of Items in stats
                </div>
                <Column class="mb-2">
                    <JetInput type="number" v-model="perPage" @keypress.enter="triggerPerPage" />
                </Column>
            </div>
        </template>
    </JetDropdown>
</template>
<script>
    import { defineComponent } from "vue";
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
    import { FilterIcon } from '@heroicons/vue/outline'
    import Button from '@/Components/Button.vue'
    import Row from '@/Components/Layouts/Row.vue'
    import Column from '@/Components/Layouts/Column.vue'
    import JetInput from '@/Jetstream/Input.vue'

    export default defineComponent({
        components: {
            JetDropdown,
            JetDropdownLink,
            FilterIcon,
            Button,
            Row,
            Column,
            JetInput
        },

        props: {
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
                show: false,
                filterBy: 'week_to_date',
                perPage: 10
            }
        },

        created() {
            this.perPage = this.$page.props.perPage
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
                this.triggerFilter()
            },

            buttonActive(period) {
                if (period !== this.currentPeriod) {
                    return '';
                }

                return 'bg-gray-100'
            },

            triggerPerPage() {
                this.filterBy = this.$props.currentPeriod
                this.triggerFilter()
            },

            triggerFilter() {
                this.$inertia.get(route('kinja.analytics.causes'), {
                    filterBy: this.filterBy,
                    perPage: this.perPage
                }, {
                    replace: true,
                })
            }
        }
    })
</script>
