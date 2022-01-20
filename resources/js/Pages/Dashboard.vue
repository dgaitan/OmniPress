<template>
    <layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="pb-12">
            <Stats />
        </div>
    </layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/Layout.vue'
    import Welcome from '@/Jetstream/Welcome.vue'
    import Stats from '@/Components/Stats.vue'

    export default defineComponent({
        components: {
            Layout,
            Welcome,
            Stats
        },
    })
</script>
