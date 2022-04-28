<template>
    <modal :show="show" maxWidth="2xl" :closeable="true" @close="$emit('close')">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="text-center sm:text-left w-full">
                    <h3 class="text-lg font-bold mb-5">
                        Filters
                    </h3>

                    <Row>
                        <Column :mdSize="6">
                            <FormGroup
                                id="fromDate"
                                label="From ">
                                <Datepicker v-model="fromDate" id="fromDate" class="border-gray-300 focus:border-cyan-600 focus:ring focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm py-2 pl-2 pr-10 w-full"  />
                            </FormGroup>
                        </Column>
                        <Column :mdSize="6">
                            <FormGroup
                                id="toDate"
                                label="To">
                                <Datepicker v-model="toDate" id="toDate" class="border-gray-300 focus:border-cyan-600 focus:ring focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm py-2 pl-2 pr-10 w-full"  />
                            </FormGroup>
                        </Column>
                        <Column>
                            <FormGroup
                                id="dateFieldToFilter"
                                label="Date field to filter">
                                <Select
                                    v-model="dateFieldToFilter"
                                    class="w-full"
                                    id="dateFieldToFilter">
                                    <option value="start_at">Membership Start Date</option>
                                    <option value="end_at">Membership End Date</option>
                                </Select>
                            </FormGroup>
                        </Column>
                        <Column>
                            <FormGroup
                                id="shipping_status"
                                label="Filter By Shipping Status">
                                <Select
                                    v-model="shippingStatus"
                                    class="w-full"
                                    id="shipping_status">
                                    <option value="">----------</option>
                                    <option
                                        v-for="status in shippingStatuses"
                                        :key="status.slug"
                                        :value="status.slug">
                                        {{ status.label }}
                                    </option>
                                </Select>
                            </FormGroup>
                        </Column>
                    </Row>
                </div>
            </div>
        </div>
        <div class="flex flex-row justify-end items-center px-6 py-4 bg-gray-100 text-right">
            <Button color="secondary" @click="$emit('close')">Close</Button>
            <Button @click="filter" class="ml-3">
                Filter
            </Button>
        </div>
    </modal>
</template>
<script>
    import { defineComponent } from 'vue'
    import Modal from '@/Jetstream/Modal.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import Button from '@/Components/Button.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetActionMessage from '@/Jetstream/ActionMessage.vue'
    import FormGroup from '@/Components/Forms/FormGroup.vue'
    import Sppiner from '@/Components/Sppiner.vue'
    import Row from '@/Components/Layouts/Row.vue'
    import Column from '@/Components/Layouts/Column.vue'
    import Select from '@/Components/Select.vue'
    import Datepicker from 'vue3-datepicker'

    export default defineComponent({
        emits: ['close'],

        props: {
            filters: {
                type: Object,
                deafult: {}
            },
            shippingStatuses: {
                type: Array,
                default: []
            },
            show: {
                type: Boolean,
                deafult: false
            }
        },

        data() {
            return {
                shippingStatus: this.filters.shippingStatus,
                fromDate: new Date(this.filters.fromDate),
                toDate: new Date(this.filters.toDate),
                dateFieldToFilter: this.filters.dateFieldToFilter
            }
        },

        components: {
            Modal,
            JetInput,
            JetButton,
            JetSecondaryButton,
            JetActionMessage,
            FormGroup,
            Sppiner,
            Row,
            Column,
            Select,
            Datepicker,
            Button
        },

        methods: {
            filter() {
                this.$props.filters.shippingStatus = this.shippingStatus
                this.$props.filters.fromDate = this.fromDate.toString() === 'Invalid Date'
                    ? ''
                    : this.fromDate;
                this.$props.filters.toDate = this.toDate.toString() === 'Invalid Date'
                    ? ''
                    : this.toDate;
                this.$props.filters.dateFieldToFilter = this.dateFieldToFilter
                this.$inertia.get(route('kinja.memberships.index'), {
                    ...this.filters
                }, { replace: true })
            }
        }
    })
</script>
