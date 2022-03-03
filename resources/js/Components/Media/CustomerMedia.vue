<template>
    <div class="flex items-center">
        <img :src="avatarSrc" width="60" height="60" class="rounded-full" />
        <div class="pl-5">
            <h4 class="text-xl font-medium">{{ headline }}</h4>
            <p class="text-sm text-gray-600">{{ legend }}</p>
        </div>
    </div>
</template>
<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: {
        headline: {
            type: String,
            default: ''
        },
        legend: {
            type: String,
            default: ''
        },
        avatar: {
            type: String,
            default: null
        }
    },

    computed: {
        avatarSrc() {
            if (this.avatar) return this.avatar

            return 'https://picsum.photos/seed/picsum/60/60'
        }
    }
})
</script>
