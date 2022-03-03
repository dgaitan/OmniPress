<template>
    <div class="bg-white rounded mb-10">
        <div v-if="headline" class="p-4 border-b border-gray-200">
            <h3 class="mr-2 text-xl font-medium">
                {{ headline }}
            </h3>
        </div>

        <div class="py-5 flex flex-wrap">
            <slot></slot>
        </div>
    </div>
</template>
<script>
    import { defineComponent } from 'vue'

    export default defineComponent({
        props: {
            headline: {
                type: String,
                default: ''
            }
        }
    })
</script>
