<template>
	<section class="pt-4 pb-8">
		<div class="container px-4 mx-auto">
			<div class="pt-6 bg-white shadow rounded">
				<div class="px-6 border-b">
					<div class="flex flex-wrap items-center mb-6">
						<h3 class="text-xl font-bold">{{ title }}</h3>
                        <JetButton type="button" class="ml-auto flex items-center" @click="$emit('export')">
				            <span class="mr-1">
				              <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
				                <path d="M13 8.33337C12.6 8.33337 12.3333 8.60004 12.3333 9.00004V11.6667C12.3333 12.0667 12.0666 12.3334 11.6666 12.3334H2.33331C1.93331 12.3334 1.66665 12.0667 1.66665 11.6667V9.00004C1.66665 8.60004 1.39998 8.33337 0.99998 8.33337C0.59998 8.33337 0.333313 8.60004 0.333313 9.00004V11.6667C0.333313 12.8 1.19998 13.6667 2.33331 13.6667H11.6666C12.8 13.6667 13.6666 12.8 13.6666 11.6667V9.00004C13.6666 8.60004 13.4 8.33337 13 8.33337ZM4.79998 4.13337L6.33331 2.60004V9.00004C6.33331 9.40004 6.59998 9.66671 6.99998 9.66671C7.39998 9.66671 7.66665 9.40004 7.66665 9.00004V2.60004L9.19998 4.13337C9.46665 4.40004 9.86665 4.40004 10.1333 4.13337C10.4 3.86671 10.4 3.46671 10.1333 3.20004L7.46665 0.533374C7.19998 0.266707 6.79998 0.266707 6.53331 0.533374L3.86665 3.20004C3.59998 3.46671 3.59998 3.86671 3.86665 4.13337C4.13331 4.40004 4.53331 4.40004 4.79998 4.13337Z" fill="#fff"></path>
				              </svg>
				            </span>
				            <span class="inline-block ml-2">Export</span>
                        </JetButton>
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

	export default defineComponent({
        emits: ['export'],

        props: {
			title: String
		},

        components: {
            JetButton
        }
	})
</script>
