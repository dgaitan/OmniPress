<template>
    <modal :show="show" maxWidth="2xl" :closeable="true" @close="$emit('close')">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="text-center sm:text-left w-full">
                    <h3 class="text-lg font-bold mb-5">
                        Editing Membership #{{ membership.id }}
                    </h3>

                    <div class="mt-2">
                        <FormGroup
                            id="end_at"
                            label="End At"
                            :message="form.errors.end_at">
                            <Datepicker v-model="form.end_at" id="end_at" class="border-gray-300 focus:border-cyan-600 focus:ring focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm py-2 pl-2 pr-10 w-full"  />
                        </FormGroup>

                        <FormGroup
                            id="kind_cash"
                            label="Kind Cash"
                            :message="form.errors.points">
                            <JetInput type="number" v-model="form.points" id="kind_cash" class="w-full" />
                        </FormGroup>

                        <FormGroup
                            id="status"
                            label="Status"
                            :message="form.status.end_at">
                            <Select id="status" v-model="form.status" class="w-full">
                                <option v-for="status in statuses" :key="status.slug" :value="status.slug">{{ status.label }}</option>
                            </Select>
                        </FormGroup>

                        <FormGroup
                            id="status"
                            label="Shipping Status"
                            :message="form.shipping_status.end_at">
                            <Select id="shipping_status" v-model="form.shipping_status" class="w-full">
                                <option v-for="status in shippingStatuses" :key="status.slug" :value="status.slug">{{ status.label }}</option>
                            </Select>
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
    import { defineComponent, ref } from 'vue'
    import Datepicker from 'vue3-datepicker'
    import Modal from '@/Jetstream/Modal.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetActionMessage from '@/Jetstream/ActionMessage.vue'
    import Select from '@/Components/Select.vue'
    import FormGroup from '@/Components/Forms/FormGroup.vue'
    import Sppiner from '@/Components/Sppiner.vue'

    export default defineComponent({
        emits: ['close'],

        components: {
            Modal,
            JetInput,
            JetButton,
            JetSecondaryButton,
            JetActionMessage,
            Datepicker,
            Select,
            FormGroup,
            Sppiner
        },

        props: {
            show: {
                type: Boolean,
                default: false
            },
            membership: Object,
            statuses: Array,
            shippingStatuses: Array
        },

        data() {
            return {
                form: this.$inertia.form({
                    _method: 'PUT',
                    end_at: new Date(this.membership.end_at),
                    status: this.membership.status,
                    shipping_status: this.membership.shipping_status,
                    points: parseFloat(this.membership.cash.points / 100)
                }),
            }
        },

        methods: {
            updateMembership() {
                this.form.post(route('kinja.memberships.update', this.membership.id), {
                    errorBag: 'updateMembership',
                    preserveScroll: true,
                    onSuccess: () => {
                        this.$props.membership.status = this.form.status,
                        this.$props.membership.shipping_status = this.form.shipping_status
                    }
                })
            }
        }
    })
</script>
