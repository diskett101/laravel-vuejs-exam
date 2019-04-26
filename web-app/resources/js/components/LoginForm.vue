<template>
	<div class="well well-sm" id="signature-form">
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
					<button type="submit" class="btn btn-primary btn-lg">Submit</button>
				</div>
				</div>
			</fieldset>
		</form>
	</div>
</template>

<script>
import endpoints from '../endpoints';
import lang from '../lang';

export default {
	mounted() {
		console.log('this.$route.params', this.$route.params);
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
	},
	methods: {

		onSubmit() {
			this.saved = false;
            console.log('email', this.email);
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