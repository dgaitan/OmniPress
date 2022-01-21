<template>
    <layout title="Sync">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Syncronization
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Manage API Tokens -->
            <div class="mt-10 sm:mt-0">

                <jet-action-section>
                    <template #title>
                        Sync Data from Kindhumans store
                    </template>

                    <template #description>
                        Select the data you want to retrieve. It will take a few minutes.
                    </template>

                    <!-- API Token List -->
                    <template #content>
                        <div class="space-y-6">
                            <!-- Sync Type -->
                            <div class="col-span-6 sm:col-span-4">
                                <label class="block text-sm font-medium mb-2" for="content_type">Sync Type</label>
                                <div class="relative">
                                    <select 
                                        v-model="syncForm.content_type" 
                                        class="appearance-none block w-full px-4 py-3 mb-2 border-gray-300 focus:border-cyan-600 focus:ring focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm" 
                                        name="content_type"
                                        id="content_type">
                                      <option value="">Select sync content type</option>
                                      <option value="customers">Customers</option>
                                      <option value="coupons">Coupons</option>
                                      <option value="products">Products</option>
                                      <option value="orders">Orders</option>
                                    </select>
                                </div>
                                <jet-input-error :message="syncForm.errors.content_type" class="mt-2" />
                            </div>

                            <!-- Offset (optional) -->
                            <!-- <div class="col-span-6 sm:col-span-4">
                                <label class="block text-sm font-medium mb-2" for="offset">Offset (optional)</label>
                                <div class="relative">
                                    <textarea v-model="syncForm.description" class="w-full border-gray-300 focus:border-cyan-600 focus:ring focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm" name="offset" rows="4"></textarea>
                                    <jet-input id="offset" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />
                                </div>
                                <jet-input-error :message="syncForm.errors.description" class="mt-2" />
                            </div> -->


                            <!-- Sync Description (optional) -->
                            <div class="col-span-6 sm:col-span-4">
                                <label class="block text-sm font-medium mb-2" for="description">Description (optional)</label>
                                <div class="relative">
                                    <textarea v-model="syncForm.description" class="w-full border-gray-300 focus:border-cyan-600 focus:ring focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm" name="description" rows="4"></textarea>
                                </div>
                                <jet-input-error :message="syncForm.errors.description" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4 flex justify-end">
                                <jet-button @click="showSyncConfirmation = true">Sync Now</jet-button>
                            </div>
                        </div>
                    </template>
                </jet-action-section>
            </div>

            <jet-section-border/>

            <!-- Syncs run -->
            <section class="py-8 max-w-7xl mx-auto py-10 ">
                <div class="">
                    <div class="py-4 bg-white rounded">
                        <div class="flex px-6 pb-4 border-b">
                            <h3 class="text-xl font-bold">Sync History</h3>
                            <div class="ml-auto">
                                <button class="py-2 px-3 text-xs text-gray-400 font-medium">Daily</button>
                                <button class="py-2 px-3 text-xs text-gray-400 font-medium">Weekly</button>
                                <button class="py-2 px-3 text-xs text-white font-medium bg-indigo-500 rounded-md">Monthly</button>
                            </div>
                        </div>
                        <div class="pt-4 px-4 overflow-x-auto">
                            <table class="table-auto w-full">
                                <thead>
                                    <tr class="text-xs text-gray-500 text-left">
                                        <th class="font-medium">Info</th>
                                        <th class="font-medium">Date</th>
                                        <th class="font-medium">Run by</th>
                                        <th class="font-medium">Content Synced</th>
                                        <th class="font-medium">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-xs bg-gray-50" v-for="sync in syncs.data">
                                        <td class="flex px-4 py-3">
                                            <div>
                                                <p class="font-medium">{{ sync.name }}</p>
                                                <p class="text-gray-500">{{ sync.description }}</p>
                                            </div>
                                        </td>
                                        <td class="font-medium">{{ sync.created_at }}</td>
                                        <td class="font-medium">{{ sync.user.name }}</td>
                                        <td class="font-medium">{{ sync.content_type }}</td>
                                        <td>
                                            <span :class="`status ${sync.status}`">{{ sync.status }}</span>
                                        </td>
                                        <td>
                                            <Button 
                                                v-if="sync.status === 'pending' || sync.status === 'failed'"
                                                type="button" 
                                                color="secondary" 
                                                size="sm"
                                                @click="tryAgain(sync.id, $event)">
                                                Try Again
                                            </Button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
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
    </layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/Layout.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetActionSection from '@/Jetstream/ActionSection.vue'
    import JetFormSection from '@/Jetstream/FormSection.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import Button from '@/Components/Button.vue';

    export default defineComponent({
        props: ['sessions', 'syncs'],

        components: {
            Layout,
            JetActionSection,
            JetButton,
            JetConfirmationModal,
            JetFormSection,
            JetSectionBorder,
            Button,
            JetInput
        },

        data() {
            return {
                showSyncConfirmation: false,
                syncForm: this.$inertia.form({
                    _method: 'POST',
                    content_type: '',
                    description: ''
                })
            }
        },

        methods: {
            syncNow() {
                this.syncForm.post(route('kinja.sync.execute'), {
                    preserveScroll: true,
                    errorBag: 'syncError',
                    onSuccess: () => {
                        this.showSyncConfirmation = false
                    }
                })
            },

            tryAgain(sync_id, e) {
                e.target.disable = true
                e.target.textContent = 'Executing...'
                this.$inertia.post(route('kinja.sync.intent'), {sync_id},{
                    preserveScroll: true,
                    errorBar: 'syncIntentError',
                    onSuccess: () => {
                        e.target.disable = false
                        e.target.textContent = 'Try Again'
                    }
                })
            }

        }
    })
</script>
