<template>
    <modal :show="show" maxWidth="2xl" :closeable="true" @close="$emit('close')">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="text-center sm:text-left w-full">
                    <h3 class="text-lg font-bold mb-5">
                        Updating kind cash of membership #{{ membership.id }}
                    </h3>

                    <div class="mt-2" v-if="form">
                        <FormGroup
                            id="kind_cash"
                            label="Kind Cash"
                            :message="form.points.end_at">
                            <JetInput id="kind_cash" type="text" v-model="form.points" class="w-full" />
                        </FormGroup>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-row justify-end items-center px-6 py-4 bg-gray-100 text-right">
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>
            <JetSecondaryButton @click="$emit('close')">Close</JetSecondaryButton>
            <JetButton @click="updateMembership" class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                <span v-if="!form.processing" class="text-xs uppercase">Update</span>
                <Sppiner v-else />
            </JetButton>
        </div>
    </modal>
</template>
<script>
    import { defineComponent } from 'vue'
    import Modal from '@/Jetstream/Modal.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetActionMessage from '@/Jetstream/ActionMessage.vue'
    import FormGroup from '@/Components/Forms/FormGroup.vue'
    import Sppiner from '@/Components/Sppiner.vue'

    export default defineComponent({
        emits: ['close'],

        props: {
            membership: {
                type: Object,
                deafult: {}
            }
        },

        data() {
            let form = {}

            if (this.$props.membership) {
                form = this.$inertia.form({
                    points: this.membership.cash.points
                })
            }

            return {
                form: form
            }
        },
        
        components: {
            Modal,
            JetInput,
            JetButton,
            JetSecondaryButton,
            JetActionMessage,
            FormGroup,
            Sppiner
        },

        methods: {
            update() {
                this.form.post(
                    route('kinja.memberships.updateKindCash', membership.id),
                    {
                        errorBag: 'updateMembershipKindCash',
                        preserveScroll: true,
                        onSuccess: () => {
                            this.$props.membership.cash.points = this.form.points
                        }
                    }
                )
            }
        }
    })
</script>
