<template>
	<li>
	  	<Link :class="classes" :href="href">
	    	<span class="inline-block mr-3" v-if="$slots.icon"><slot name="icon"></slot></span>
	    	<span><slot /></span>
	  	</Link>
	</li>
</template>
<script>
	import { defineComponent } from 'vue'
	import { Link } from '@inertiajs/inertia-vue3';

	export default defineComponent({
		components: {
			Link
		},

		props: ['href', 'active'],

		computed: {
			classes() {
				return this.active
					? 'flex items-center pl-3 py-3 pr-2 text-white bg-cyan-400 rounded transition-all'
					: 'flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-cyan-400 hover:text-white transition-all rounded'
			}
		}
	})
</script>