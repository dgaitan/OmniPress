<template>
    <app-layout title="Sync">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Syncronization
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

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
                                      <option value="orders">Orders</option>
                                    </select>
                                    <!-- <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                      <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                                      </svg>
                                    </div> -->
                                </div>
                                <jet-input-error :message="syncForm.errors.content_type" class="mt-2" />
                            </div>

                            <!-- Sync Description (optional) -->
                            <div class="col-span-6 sm:col-span-4">
                                <label class="block text-sm font-medium mb-2" for="content_type">Description (optional)</label>
                                <div class="relative">
                                    <textarea v-model="syncForm.description" class="w-full border-gray-300 focus:border-cyan-600 focus:ring focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm" name="descirption" rows="4"></textarea>
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
                            <h3 class="text-xl font-bold">Sales History</h3>
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
                                        <th class="font-medium">Run by</th>
                                        <th class="font-medium">Content Synced</th>
                                        <th class="font-medium">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-xs bg-gray-50" v-for="sync in syncs.data">
                                        <td class="flex px-4 py-3">
                                            <img class="w-8 h-8 mr-4 object-cover rounded-md" src="https://images.unsplash.com/photo-1575537302964-96cd47c06b1b?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1050&amp;q=80" alt="">
                                            <div>
                                                <p class="font-medium">{{ sync.name }}</p>
                                                <p class="text-gray-500">{{ sync.description }}</p>
                                            </div>
                                        </td>
                                        <td class="font-medium">{{ sync.user.name }}</td>
                                        <td class="font-medium">{{ sync.content_type }}</td>
                                        <td>
                                            <span class="inline-block py-1 px-2 text-white bg-green-500 rounded-full">{{ sync.status }}</span>
                                        </td>
                                        <td>
                                            
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
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetActionSection from '@/Jetstream/ActionSection.vue'
    import JetFormSection from '@/Jetstream/FormSection.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder'

    export default defineComponent({
        props: ['sessions', 'syncs'],

        components: {
            AppLayout,
            JetActionSection,
            JetButton,
            JetConfirmationModal,
            JetFormSection,
            JetSectionBorder
        },

        data() {
            return {
                showSyncConfirmation: false,
                syncForm: this.$inertia.form({
                    _method: 'POST',
                    content_type: '',
                    description: ''
                }),
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
            }
        }
    })
</script>
