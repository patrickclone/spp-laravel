<script setup>
	import { Link } from '@inertiajs/vue3'
	defineProps({links: Array})
</script>
<template>
	<ul class="pagination">
		<li class="" v-for="link in links" :class="{'active': link.active}">
			<Link :href="link.url ?? ''" v-html="link.label"></Link>
		</li>
	</ul>
</template>