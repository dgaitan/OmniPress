<template>
    <div class="flex items-center content-between bg-gray-100 rounded p-2">
        <div class=" w-1/2">
            <!-- SearchBox -->
            <input
                type="search"
                :value="filters.s"
                v-on:keyup.enter="search($event)"
                placeholder="Search..."
                class="bg-white border border-gray-300 rounded py-2 px-3 w-full focus:border-gray-400 focus:ring-0 active:border-gray-400" />
        </div>

        <!-- Actions -->
        <div class="w-1/2 flex content-end justify-end">

            <JetDropdown align="right" width="72">
                <template #trigger>
                    <Button size="sm" color="secondary" class="px-3 py-2 leading-7 ml-2 inline-flex">
                        <LightningBoltIcon class="w-5 h-5 mr-1" />
                        Actions
                    </Button>
                </template>
                <template #content>
                    <div class="w-72">
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            Shipping Statuses
                        </div>
                        <JetDropdownLink as="button" @click="bulkActions('shipping_status_to_cancelled')">
                            Change Shipping Status to Cancelled
                        </JetDropdownLink>
                        <JetDropdownLink as="button" @click="bulkActions('shipping_status_to_shipped')">
                            Change Shipping Status to Shipped
                        </JetDropdownLink>
                        <JetDropdownLink as="button" @click="bulkActions('shipping_status_to_no_ship')">
                            Change Shipping Status to No Ship
                        </JetDropdownLink>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            Membership Statuses
                        </div>
                        <JetDropdownLink as="button" @click="bulkActions('status_to_active')">
                            Change Status to Active
                        </JetDropdownLink>
                        <JetDropdownLink as="button" @click="bulkActions('status_to_in_renewal')">
                            Change Status to In Renewal
                        </JetDropdownLink>
                        <JetDropdownLink as="button" @click="bulkActions('status_to_awaiting_pick_gift')">
                            Change Status to Awaiting Pick Gift
                        </JetDropdownLink>
                        <JetDropdownLink as="button" @click="bulkActions('status_to_cancelled')">
                            Change Status to Cancelled
                        </JetDropdownLink>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            Actions
                        </div>

                        <JetDropdownLink as="button" @click="bulkActions('expire')">
                            Expire Membership
                        </JetDropdownLink>
                        <JetDropdownLink as="button" @click="bulkActions('renew')">
                            Renew Memberships
                        </JetDropdownLink>
                        <JetDropdownLink as="button" @click="bulkActions('run_cron')">
                            Run Renewal Cron
                        </JetDropdownLink>
                    </div>
                </template>
            </JetDropdown>

            <Button @click="$emit('showFilters', true)" size="sm" color="secondary" class="px-3 py-2 ml-2">
                <FilterIcon class="w-5 h-5 mr-1" />
                Filters
            </Button>
        </div>
    </div>
</template>
<script>
    import { defineComponent } from 'vue'
    import Button from '@/Components/Button.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import { FilterIcon, LightningBoltIcon } from '@heroicons/vue/outline'

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
            JetDropdownLink,
            JetSecondaryButton,
            FilterIcon,
            LightningBoltIcon,
            Button
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

            bulkActions(action) {
                this.$emit('bulkAction', action);
            }
        }
    })
</script>
