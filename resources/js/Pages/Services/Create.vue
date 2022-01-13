<template>
    <app-layout title="Services">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Service
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetButton from '@/Jetstream/Button.vue'


    export default defineComponent({
        props: ['sessions'],

        components: {
            AppLayout,
            JetButton
        },
    })
</script>
