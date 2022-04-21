<template>
	<li>
	  	<Link :class="classes" :href="href">
	    	<span class="inline-block mr-3" v-if="$slots.icon"><slot name="icon"></slot></span>
	    	<span><slot /></span>
	  	</Link>
	</li>
</template>
<style type="text/css">
	.active svg,
	.link:hover svg { transition: all .4s; fill: #FFFFFF }
</style>
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
					? 'flex items-center text-md py-2 px-7 text-cyan-500 bg-gray-400/10 transition-all rounded-r-lg border-l-4 border-cyan-400 transition-all'
					: 'flex text-md items-center py-2 px-7 text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition-all rounded'
			}
		}
	})
</script>
