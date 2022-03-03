<template>
    <span :class="classes">
        <span class="status-dot"></span>
        {{ label }}
    </span>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: {
        status: String,
        bordered: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        label() {
            return this.status ? this.status.split('_').join(' ') : ''
        },

        classes() {
            let base = 'status';
            const color = this.getStatusColor();
            base = `${base} ${color}`;
            if (this.bordered) base = `${base} border border-${color}-600`;

            return base;
        },
    },

    methods: {
        getStatusColor() {
            if (['active', 'processing', 'subscriber', 'publish'].includes(this.status)) {
                return 'green';
            }

            if (['completed', 'shipped', 'kh_affiliate'].includes(this.status)) {
                return 'sky';
            }

            if (['pending', 'in_renewal', 'administrator'].includes(this.status)) {
                return 'cyan'
            }

            if (['expired', 'cancelled', 'error'].includes(this.status)) {
                return 'red';
            }

            return 'gray';
        },
    }
})
</script>
