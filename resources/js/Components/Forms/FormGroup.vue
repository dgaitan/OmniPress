<template>
    <div class="w-full mb-4 pb-3">
        <jet-label :for="id" :value="label" />
        <span class="block mt-2">
            <slot></slot>
        </span>
        <jet-input-error :message="message" class="mt-2" />
    </div>
</template>
<script>
import { defineComponent } from 'vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetInputError from '@/Jetstream/InputError.vue'

export default defineComponent({
    props: {
        id: {
            type: String,
            default: ''
        },
        label: {
            type: String,
            default: ''
        },
        message: {
            type: String,
            default: ''
        }
    },

    components: {
        JetLabel,
        JetInputError
    }
})
</script>
