<template>
    <div class="bg-white rounded mb-10 border border-gray-200 shadow-gray-200 shadow-sm">
        <div v-if="headline" class="p-4 border-b border-gray-200 flex justify-between w-full">
            <h3 class="mr-2 text-lg font-medium">
                {{ headline }}
            </h3>
            <div v-if="button.link">
                <Link :href="button.link" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition">
                    {{ button.label }}
                </Link>
            </div>
        </div>

        <div class="py-5 flex flex-wrap">
            <slot></slot>
        </div>
    </div>
</template>
<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3'

    export default defineComponent({
        props: {
            headline: {
                type: String,
                default: ''
            },
            button: {
                type: Object,
                default: {
                    link: null,
                    label: null
                }
            }
        },

        components: {
            Link
        }
    })
</script>
