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
                            Actions
                        </div>
                    </div>
                </template>
            </JetDropdown>

            <Button size="sm" color="secondary" class="px-3 py-2 ml-2">
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
                this.$inertia.get(route('kinja.causes.index'), {
                    s: e.target.value
                }, { replace: true })
            }

        }
    })
</script>
