<template>
    <div class="flex content-between">
        <!-- SearchBox -->
        <jet-input :value="filters.s" type="search" v-on:keyup.enter="search($event)" placeholder="Search..." style="width:350px" />

        <!-- Actions -->
        <div>

            <select :value="action" @change="bulkActions($event)" class="border-gray-300 focus:border-cyan-600 focus:ring focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm ml-2 py-2 pl-2 pr-10" style="width:200px">
                <option value="">Actions</option>
                <optgroup label="Shipping Statuses">
                    <option value="shipping_status_to_cancelled">Change Shipping Status to Cancelled</option>
                    <option value="shipping_status_to_shipped">Change Shipping Status to Shipped</option>
                    <option value="shipping_status_to_no_ship">Change Shipping Status to No Ship</option>
                </optgroup>
                <optgroup label="Membership Statuses">
                    <option value="status_to_active">Change Status to Active</option>
                    <option value="status_to_in_renewal">Change Status to In Renewal</option>
                    <option value="status_to_awaiting_pick_gift">Change Status to Awaiting Pick Gift</option>
                    <option value="status_to_cancelled">Change Status to Cancelled</option>
                </optgroup>
                <optgroup label="Actions">
                    <option value="expire">Expire Membership</option>
                    <option value="renew">Renew Memberships</option>
                    <option value="run_cron">Run Renewal Cron</option>
                </optgroup>
            </select>

            <JetSecondaryButton @click="$emit('showFilters', true)" class="px-5 py-3 ml-2">Filters</JetSecondaryButton>
        </div>
    </div>
</template>
<script>
    import { defineComponent } from 'vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'

    export default defineComponent({
        props: {
            filters: Object,
            shippingStatuses: Array,
            action: String
        },

        emits: ['bulkAction', 'showFilters'],

        components: {
            JetInput,
            JetDropdown,
            JetSecondaryButton
        },

        methods: {
            search(e) {
                this.$inertia.get(route('kinja.memberships.index'), {
                    s: e.target.value
                }, { replace: true })
            },

            changeShippingStatus(e) {
                this.$props.filters.shippingStatus = e.target.value
                this.$inertia.get(route('kinja.memberships.index'), {
                    ...this.filters
                }, { replace: true })
            },

            bulkActions(e) {
                this.$emit('bulkAction', e.target.value);
            }
        }
    })
</script>
