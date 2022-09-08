<template>
    <div class="mt-4">
        <Timeline :data="logs" />
    </div>
</template>
<script>
    import { defineComponent } from 'vue'
    import Timeline from '@/Components/Timeline.vue'

    export default defineComponent({
        props: {
            membership: {
                type: Object,
                default: {}
            }
        },

        components: {
            Timeline
        },

        computed: {
            logs() {
                return this.membership.logs.map(log => {
                    return {
                        time: log.date,
                        content: log.description
                    }
                })
            }
        }
    })
</script>
