<template>
    <JetDropdown align="right" width="60">
        <template #trigger>
            <Button type="button" color="secondary">
                <DownloadIcon class="w-5 h-5" />
            </Button>
        </template>
        <template #content>
            <div class="w-60">
                <JetDropdownLink as="button" @click="exportSubscription">
                    Export Subscription Products
                </JetDropdownLink>
            </div>
        </template>
    </JetDropdown>
</template>
<script>
    import { defineComponent } from 'vue'
    import { DownloadIcon } from '@heroicons/vue/outline'
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
    import Button from '@/Components/Button.vue'

    export default defineComponent({
        components: {
            DownloadIcon,
            JetDropdown,
            JetDropdownLink,
            Button
        },

        methods: {
            exportSubscription() {
                window.open(route('kinja.products.export-subscriptions'), '_blank')
            }
        }
    })
</script>
