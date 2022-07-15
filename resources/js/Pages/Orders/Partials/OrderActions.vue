<template>
    <JetDropdown align="right" width="72">
        <template #trigger>
            <Button size="md" color="secondary">
                <LightningBoltIcon class="w-4 h-4 mr-3" />Actions
            </Button>
        </template>
        <template #content>
            <div class="">
                <JetDropdownLink
                    as="button"
                    @click="goToStore()">
                    See order on kindhumans.com
                </JetDropdownLink>
                <JetDropdownLink
                    as="button"
                    @click="simulateShipHero">
                    Simulate ShipHero Shipping
                </JetDropdownLink>
                <JetDropdownLink
                    as="button"
                    @click="syncOrder">
                    Sync Order
                </JetDropdownLink>
            </div>
        </template>
    </JetDropdown>
</template>
<script>
    import { defineComponent } from 'vue'
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
    import { LightningBoltIcon } from '@heroicons/vue/outline'
    import Button from '@/Components/Button.vue'

    export default defineComponent({
        props: {
            order: Object
        },

        components: {
            JetDropdown,
            JetDropdownLink,
            LightningBoltIcon,
            Button
        },

        methods: {
            goToStore() {
                window.open(this.order.permalink_on_store, '_blank').focus();
            },

            simulateShipHero() {
                this.$inertia.post(route('kinja.orders.simulateShipHero'), {
                    order_id: this.order.id
                }, {
                    replace: true,
                    onSuccess: function () {

                    }
                })
            },

            syncOrder() {
                this.$inertia.post(route('kinja.orders.syncOrder'), {
                    order_id: this.order.id
                }, {
                    replace: true,
                })
            }
        }
    })
</script>
