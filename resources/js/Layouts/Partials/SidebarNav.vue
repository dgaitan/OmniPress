<template>
	<div>
		<h3 v-if="$slots.title" class="mb-2 text-xs uppercase text-gray-500 opacity-40 font-medium"><slot name="title"></slot></h3>
		<ul class="mb-8 text-sm font-medium">
			<slot/>
		</ul>
	</div>
</template>