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
                    1: 'col-1',
                    2: 'col-2',
                    3: 'col-3',
                    4: 'col-4',
                    5: 'col-5',
                    6: 'col-6',
                    7: 'col-7',
                    8: 'col-8',
                    9: 'col-9',
                    10: 'col-10',
                    11: 'col-11',
                    12: 'col-12'
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
