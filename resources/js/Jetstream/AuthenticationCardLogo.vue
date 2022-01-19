<template>
    <Link :href="'/'">
        <img src="https://kindhumans.com/wp-content/uploads/2019/04/logo-color.svg" class="mb-4">
    </Link>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Link,
        },
    })
</script>
