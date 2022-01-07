<template>
    <app-layout title="Services">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Services
            </h2>
        </template>

        <div>
            
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'


    export default defineComponent({
        props: ['sessions'],

        components: {
            AppLayout,
        },
    })
</script>
