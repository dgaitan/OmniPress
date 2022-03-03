<template>
	<Link :class="classes" href="#"><slot></slot></Link>
</template>

<script>
	import { defineComponent } from 'vue';
	import { Link } from '@inertiajs/inertia-vue3';

	export default defineComponent({
		props: {
			active: Boolean
		},

		components: {
			Link
		},

		computed: {
			classes() {
				if (this.active) {
					return 'inline-block px-4 pb-2 text-sm font-medium text-cyan-500 border-b-2 border-cyan-500';
				} else {
					return 'inline-block px-4 pb-2 text-sm font-medium text-gray-500 border-b-2 border-transparent';
				}
			}
		}
	})
</script>
