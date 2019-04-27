<template>
	<div class="container">
		<div class="row">
			<div class="col-md-3">&nbsp;</div>
			<div class="col-md-6 page-content">
				<form class="form-horizontal" method="post" @submit.prevent="onSubmit">
					<fieldset>
						<legend class="text-center">Login</legend>

						<div class="form-group">
							<label class="col-md-3 control-label" for="email">Email</label>
							<div class="col-md-12">
								<input id="email" v-model="email" type="email" placeholder="Email" class="form-control">

							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label" for="password">Password</label>
							<div class="col-md-12" :class="{'has-error': error}">
								<input id="password" v-model="password" type="password" placeholder="Password" class="form-control">
								<span v-if="error" class="help-block text-danger">{{ error }}</span>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary btn-sm">Login</button>
						</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div class="col-md-3">&nbsp;</div>
		</div>
	</div>
</template>

<script>
import endpoints from '../endpoints';
import lang from '../lang';
import {getToken} from '../functions';


export default {
	mounted() {
		console.log('Login form mounted');
	},
	data() {
		return {
			lang: lang,
			error: null,
			saved: false,
            userData: {},
            apiUrlLogin: endpoints.login_post,
			apiUrlGet: endpoints.user_data,
			apiUrlPut: endpoints.user_update,
            userId: null,
            email: '',
            password: ''
		};
	},
	created() {
		if (getToken()) {
			window.location.href = '/user-list';
		}
	},
	methods: {

		onSubmit() {
			this.saved = false;
			axios.post(this.apiUrlLogin, {
				email: this.email,
				password: this.password
			})
			.then(({data}) => this.loginSuccess(data))
			.catch(({response}) => this.setErrors(response));
		},

		setErrors(response) {
            console.log('ERROR response', response);

			this.error = response.data.error;
		},

		loginSuccess(data) {
            console.log('login response', data);
            this.error = null;
            let token = data.token;
            sessionStorage.setItem('token', token);
            window.location.href = '/user-list';
		}
	}
}

</script>