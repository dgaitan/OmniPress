<template>
	<div>
		<h3 v-if="$slots.title" class="mb-2 pl-7 text-sm text-gray-600 opacity-50 font-medium"><slot name="title"></slot></h3>
		<ul class="mb-8 text-sm font-medium">
			<slot/>
		</ul>
	</div>
</template>
