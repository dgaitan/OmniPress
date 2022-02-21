<template>
    <div class="py-5">
        <h2>Membership #{{ membership.id }}</h2>
    </div>
</template>
<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: {
        membership: Object
    }
})
</script>
