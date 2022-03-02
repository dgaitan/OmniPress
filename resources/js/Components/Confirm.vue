<template>
    <jet-confirmation-modal :show="show" @close="$emit('close')">
        <template #title>
            {{ title }}
        </template>

        <template #content>
            {{ message }}
        </template>

        <template #footer>
            <jet-secondary-button @click="$emit('close')">
                <span v-if="canConfirm">Cancel</span>
                <span v-else>Close</span>
            </jet-secondary-button>

            <jet-button v-if="canConfirm" class="ml-3" @click="$emit('confirm')" :class="{ 'opacity-25': processing }" :disabled="processing">
                <span v-if="!processing">Confirm</span>
                <Sppiner v-else />
            </jet-button>
        </template>
    </jet-confirmation-modal>
</template>
<script>
    import { defineComponent } from 'vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import Sppiner from '@/Components/Sppiner.vue'

    export default defineComponent({
        emits: ['confirm', 'close'],
        props: {
            title: {
                type: String,
                default: 'Please Confirm!'
            },
            message: {
                type: String,
                default: ''
            },
            show: {
                type: Boolean,
                default: false
            },
            canConfirm: {
                type: Boolean,
                default: true
            },
            processing: {
                type: Boolean,
                default: false
            },
        },

        components: {
            JetConfirmationModal,
            JetButton,
            JetSecondaryButton,
            Sppiner
        },
    })
</script>
