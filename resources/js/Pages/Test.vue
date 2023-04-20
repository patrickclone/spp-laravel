<script setup>
	import { Head } from '@inertiajs/vue3'
	import Layout from '../Layout.vue'
	defineProps({title: String});
</script>
<template>
	<Head title="Test" />
	<Layout>
		<h1>{{ title }}</h1>
	</Layout>
</template>