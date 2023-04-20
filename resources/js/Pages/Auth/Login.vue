<script setup>
	import { ref, computed } from 'vue'
	import { useForm, Head, usePage } from '@inertiajs/vue3'
	import Alert from '../../components/Alert.vue'
	import PasswordInput from '../../components/PasswordInput.vue'
	import '../../../css/login.css'

	const alert = computed(() => usePage().props.flash.alert)

	const form = useForm({
		username: null,
		password: null,
		remember_me: false,
	})
</script>
<template>
	<Head title="Login" />
	<div id="login">
		<div class="login-img">
			<img src="img/data_blue.png" alt="">
		</div>
		<form action="" method="post" class="login-form" @submit.prevent="form.post('/login')">
			<h1>Login</h1>
			<Alert :type="alert[0]" v-if="alert">{{ alert[1] }}</Alert>
			<div class="mb-3">
				<label for="text" class="form-label">Username / NIS</label>
				<input type="text" id="username" name="username" class="form-input" placeholder="Masukkan Username / NIS" v-model="form.username">
				<div class="input-error" v-if="form.errors.username">{{ form.errors.username }}</div>
			</div>
			<PasswordInput v-model="form.password" :error="form.errors.password" />
			<!-- <div class="form-check">
				<input type="checkbox" id="remember_me" name="remember_me" class="form-check-input" v-model="form.remember_me">
				<label for="remember_me" class="form-check-label inline-label">Ingat Saya</label>
			</div> -->
			<button class="btn btn-blue" name="login" :disabled="form.processing">Login</button>
		</form>
	</div>
</template>