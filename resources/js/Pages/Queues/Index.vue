<template>
    <layout title="Queues">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Syncronization
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Queues run -->
            <section class="py-8 max-w-7xl mx-auto py-10 ">
                <div class="">
                    <div class="py-4 bg-white rounded">
                        <div class="flex px-6 pb-4 border-b">
                            <h3 class="text-xl font-bold">Queues</h3>
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
                                        <th class="font-medium">UUID</th>
                                        <th class="font-medium">Connection</th>
                                        <th class="font-medium">Queue</th>
                                        <th class="font-medium">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-xs bg-gray-50" v-for="queue in queues" :key="queue.uuid">
                                        <td class="flex px-4 py-3">
                                            {{ queue.uuid }}
                                        </td>
                                        <td class="font-medium">{{ queue.connection }}</td>
                                        <td class="font-medium">{{ queue.queue }}</td>
                                        <td>
                                            <jet-button @click="showDetail(queue)">
                                                Show Detail
                                            </jet-button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <!-- Membership Detail Modal -->
        <jet-modal :show="showDetailModal" @close="showDetailModal = false" maxWidth="7xl">
            <div class="p-4">
                <div class="mb-5">
                    <h4>Payload</h4>
                    <div class="p-4 bg-gray-200">
                        {{ JSON.parse(queue.payload) }}
                    </div>
                </div>
                <div class="mb-">
                    <h4>Exception</h4>
                    <div class="p-4 bg-gray-200">
                        {{ queue.exception }}
                    </div>
                </div>
                <jet-button @click="showSyncConfirmation = false">
                    Close
                </jet-button>
            </div>
        </jet-modal>
    </layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/Layout.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetActionSection from '@/Jetstream/ActionSection.vue'
    import JetModal from '@/Jetstream/Modal.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import Button from '@/Components/Button.vue';

    export default defineComponent({
        props: ['sessions', 'queues'],

        components: {
            Layout,
            JetActionSection,
            JetButton,
            JetModal,
            JetSectionBorder,
            Button,
        },

        data() {
            return {
                showDetailModal: false,
                queue: {}
            }
        },

        methods: {
            showDetail(queue = {}) {
                this.queue = queue
                this.showDetailModal = true;
            }
        }
    })
</script>
