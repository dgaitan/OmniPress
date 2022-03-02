<template>
    <div :class="`w-full ${classes} px-4`">
        <slot></slot>
    </div>
</template>
<script>
    import { defineComponent } from 'vue'

    export default defineComponent({
        props: {
            smSize: {
                type: Number,
                default: null
            },
            mdSize: {
                type: Number,
                default: null
            },
            lgSize: {
                type: Number,
                default: null
            }
        },

        computed: {
            classes() {
                let sizes = []

                if (this.smSize) {
                    sizes.push(`sm:${this.getColumnClass(this.smSize)}`)
                }

                if (this.mdSize) {
                    sizes.push(`md:${this.getColumnClass(this.mdSize)}`)
                }

                if (this.lgSize) {
                    sizes.push(`lg:${this.getColumnClass(this.lgSize)}`)
                }

                return sizes.join(' ')
            }
        },

        data() {
            return {
                sizeMapping: {
                    1: 'w-1/6',
                    2: 'w-1/5',
                    3: 'w-1/4',
                    4: 'w-1/3',
                    5: 'w-2/5',
                    6: 'w-1/2',
                    7: 'w-3/5',
                    8: 'w-2/3',
                    9: 'w-3/4',
                    10: 'w-4/5',
                    11: 'w-5/6',
                    12: 'w-full'
                }
            }
        },

        methods: {
            /**
             * Get column class related to size sent via param
             *
             * @param int size
             * @return string
             */
            getColumnClass(size) {
                if (Object.keys(this.sizeMapping).includes(size.toString())) {
                    return this.sizeMapping[size]
                }

                return 'w-full'
            }
        }
    })
</script>
