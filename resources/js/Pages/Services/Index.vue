<template>
    <app-layout title="Services">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Services
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div v-if="services.length > 0">
                
            </div>
            <div v-else class="max-w-3xl mx-auto px-20 py-20 bg-white sm:p-20 shadow sm:rounded-tl-md sm:rounded-tr-md text-center">
                <h4 class="text-center text-3xl mb-10">Services not found</h4>
                <a :href="route('services.create')" method="get" as="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">Create Service</a>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'

    export default defineComponent({
        props: ['sessions', 'services'],

        components: {
            AppLayout,
        },
    })
</script>
