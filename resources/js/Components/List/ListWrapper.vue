<template>
	<section class="pt-4 pb-8">
		<div class="container px-4 mx-auto">
			<div class="pt-6 bg-white shadow rounded">
				<div class="px-6 border-b">
					<div class="flex flex-wrap items-center mb-6">
						<h3 class="text-xl font-medium">{{ title }}</h3>
                        <Button v-if="exportButton" type="button" class="ml-auto flex items-center" @click="$emit('export')">
				            <DownloadIcon class="w-5 h-5 mr-1" />
				            <span class="inline-block ml-2">Export</span>
                        </Button>
					</div>
					<div class="mb-5">
						<slot name="actions"></slot>
					</div>
					<div>
						<slot name="filters"></slot>
					</div>
				</div>
				<div class="">
					<slot name="table"></slot>
				</div>

			</div>
		</div>
		<slot name="pagination"></slot>
	</section>
</template>

<script>
	import { defineComponent } from 'vue'
    import JetButton from '@/Jetstream/Button.vue'
    import Button from '@/Components/Button.vue'
    import { DownloadIcon } from '@heroicons/vue/outline'

	export default defineComponent({
        emits: ['export'],

        props: {
			title: String,
            exportButton: {
                type: Boolean,
                default: false
            }
		},

        components: {
            Button,
            DownloadIcon
        }
	})
</script>
