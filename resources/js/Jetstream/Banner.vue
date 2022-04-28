<template>
    <div>
        <!-- <div :class="{ 'bg-cyan-500': style == 'success', 'bg-red-700': style == 'danger' }" v-if="show && message"> -->
        <div class="fixed bottom-5 left-1/2 transform -translate-x-2/4 rounded shadow" :class="{ 'bg-cyan-500': style == 'success', 'bg-red-700': style == 'danger' }" v-if="show && message" >
            <div class=" min-w-min py-2 inline-flex px-6">
                <div class="flex items-center justify-between flex-wrap w-full">
                    <div class="flex-1 flex items-center min-w-0">
                        <p class=" font-medium text-sm text-white truncate">
                            {{ message }}
                        </p>
                    </div>

                    <div class="shrink-0 sm:ml-3">
                        <button
                            type="button"
                            class="-mr-1 flex p-2 rounded-md focus:outline-none sm:-mr-2 transition"
                            :class="{ 'hover:bg-cyan-600 focus:bg-cyan-600': style == 'success', 'hover:bg-red-600 focus:bg-red-600': style == 'danger' }"
                            aria-label="Dismiss"
                            @click.prevent="show = false">
                            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'

    export default defineComponent({
        data() {
            return {
                show: true,
            }
        },

        created() {
            setTimeout(() => {
                this.show = false
            }, 20000);
        },

        computed: {
            style() {
                return this.$page.props.jetstream.flash?.bannerStyle || 'success'
            },

            message() {
                return this.$page.props.jetstream.flash?.banner || ''
            },
        }
    })
</script>
