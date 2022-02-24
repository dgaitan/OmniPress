<template>
    <span :class="classes">
        <span :class="pointClasses"></span>
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

        pointClasses() {
            let base = 'relative inline-flex w-2.5 h-2.5 rounded-full mr-2';

            return `${base} bg-${this.getStatusColor()}-600`;
        },

        classes() {
            let base = 'inline-flex items-center pl-3 pr-4 py-1 text-sm font-semibold capitalize rounded-full bg-opacity-10';
            const color = this.getStatusColor();
            base = `${base} bg-${color}-600 text-${color}-600`;
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
