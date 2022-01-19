<template>
    <app-layout title="Sync">

        <template #header>
            <div class="w-full flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Syncronization
                </h2>
                <jet-button @click="showSyncConfirmation = true">Sync Now</jet-button>
            </div>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

            <!-- Manage API Tokens -->
            <div class="mt-10 sm:mt-0">

                <jet-action-section>
                    <template #title>
                        Sync History
                    </template>

                    <template #description>
                        You may delete any of your existing tokens if they are no longer needed.
                    </template>

                    <!-- API Token List -->
                    <template #content>
                        <div class="space-y-6">
                            <div class="flex items-center justify-between" v-for="sync in syncs.data" :key="sync.id">
                                <div>
                                    Sync run by {{ sync.user.name }}
                                </div>

                                <div class="flex items-center">
                                    <div class="text-sm text-gray-400" v-if="sync.created_at">
                                        Run at {{ sync.created_at }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </jet-action-section>
            </div>
        </div>

        <!-- Sync Confirmation Modal -->
        <jet-confirmation-modal :show="showSyncConfirmation" @close="showSyncConfirmation = false">
            <template #title>
                Are you sure?
            </template>

            <template #content>
                Are you sure you would like to make a new sync?
            </template>

            <template #footer>
                <jet-secondary-button @click="showSyncConfirmation = false">
                    Cancel
                </jet-secondary-button>

                <jet-button class="ml-3" @click="syncNow" :class="{ 'opacity-25': syncForm.processing }" :disabled="syncForm.processing">
                    Confirm
                </jet-button>
            </template>
        </jet-confirmation-modal>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetActionSection from '@/Jetstream/ActionSection.vue'
    import JetFormSection from '@/Jetstream/FormSection.vue'

    export default defineComponent({
        props: ['sessions', 'syncs'],

        components: {
            AppLayout,
            JetActionSection,
            JetButton,
            JetConfirmationModal,
            JetFormSection
        },

        data() {
            return {
                showSyncConfirmation: false,
                syncForm: this.$inertia.form({
                    _method: 'POST',
                }),
            }
        },

        methods: {
            syncNow() {
                this.syncForm.post(route('kinja.sync.execute'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.showSyncConfirmation = false
                    }
                })
            }
        }
    })
</script>
