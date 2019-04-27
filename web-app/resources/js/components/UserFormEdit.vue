<template>
	<div class="container page-content">
		<div class="well well-sm" id="user-edit-form">
			<form class="form-horizontal" method="post" @submit.prevent="onSubmit">
				<fieldset>
					<legend class="text-center">{{ lang.edit_user_info }}</legend>

					<div class="form-group">
						<label class="col-md-3 control-label" for="username">{{ lang.username }}</label>
						<div class="col-md-12" :class="{'has-error': errors.username}">
							<input id="username" v-model="userData.username" type="text" :placeholder="lang.username" class="form-control">
							<span v-if="errors.username" class="help-block text-danger">{{ errors.username[0] }}</span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label" for="email">{{ lang.email }}</label>
						<div class="col-md-12" :class="{'has-error': errors.email}">
							<input id="email" v-model="userData.email" type="text" :placeholder="lang.email" class="form-control">
							<span v-if="errors.email" class="help-block text-danger">{{ errors.email[0] }}</span>
						</div>
					</div>


					<div class="form-group">
						<label class="col-md-3 control-label" for="password">{{ lang.password }}</label>
						<div class="col-md-12" :class="{'has-error': errors['password']}">
							<input id="password" v-model="userData.password" type="password" :placeholder="lang.password" class="form-control">
							<span v-if="errors['password']" class="help-block text-danger">{{ errors.password[0] }}</span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label" for="confirm_password">{{ lang.confirm_password }}</label>
						<div class="col-md-12" :class="{'has-error': errors.password}">
							<input id="confirm_password" v-model="userData.confirm_password" type="password" :placeholder="lang.confirm_password" class="form-control">
							<span v-if="errors.password" class="help-block text-danger">{{ errors.password[0] }}</span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label" for="firstname">{{ lang.firstname }}</label>
						<div class="col-md-12" :class="{'has-error': errors.firstname}">
							<input id="firstname" v-model="userData.firstname" type="text" :placeholder="lang.firstname" class="form-control">
							<span v-if="errors.firstname" class="help-block text-danger">{{ errors.firstname[0] }}</span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label" for="lastname">{{ lang.lastname }}</label>
						<div class="col-md-12" :class="{'has-error': errors.lastname}">
							<input id="lastname" v-model="userData.lastname" type="text" :placeholder="lang.lastname" class="form-control">
							<span v-if="errors.lastname" class="help-block text-danger">{{ errors.lastname[0] }}</span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label" for="address">{{ lang.address }}</label>
						<div class="col-md-12" :class="{'has-error': errors.address}">
							<input id="address" v-model="userData.address" type="text" :placeholder="lang.address" class="form-control">
							<span v-if="errors.address" class="help-block text-danger">{{ errors.address[0] }}</span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label" for="postcode">{{ lang.post_code }}</label>
						<div class="col-md-12" :class="{'has-error': errors.postcode}">
							<input id="postcode" v-model="userData.postcode" type="text" :placeholder="lang.post_code" class="form-control">
							<span v-if="errors.postcode" class="help-block text-danger">{{ errors.postcode[0] }}</span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label" for="phone_number">{{ lang.contact_phone_number }}</label>
						<div class="col-md-12" :class="{'has-error': errors.phone_number}">
							<input id="phone_number" v-model="userData.phone_number" type="text" :placeholder="lang.contact_phone_number" class="form-control">
							<span v-if="errors.phone_number" class="help-block text-danger">{{ errors.phone_number[0] }}</span>
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-12">
							<a href="/user-list" class="btn btn-secondary btn-sm pull-left">{{ lang.back }}</a>
							<button type="submit" class="btn btn-primary btn-sm pull-right">{{ lang.submit }}</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</template>

<script>
import endpoints from '../endpoints';
import lang from '../lang';
import {getToken} from '../functions';

export default {
	mounted() {
	},
	data() {
		return {
			lang: lang,
			errors: {},
			saved: false,
			userData: {},
			apiUrlGet: endpoints.user_data,
			apiUrlPut: endpoints.user_update,
			userId: null
		};
	},
	beforeCreate() {
		if (!getToken()) {
			window.location.href = '/login';
		}
	},
	created() {
		this.getUserData(this.$route.params.user_id);
	},
	methods: {
		getUserData(userId) {
			this.userId = userId;
			axios({
				method: 'get',
				url: `${this.apiUrlGet}/${this.userId}`,
				headers: {'Token': getToken()}
			})
			.then(({data}) => {
				this.userData = data.data;
			});
		},
		onSubmit() {
			this.saved = false;
			axios({
				method: 'put',
				url: `${this.apiUrlPut}/${this.userId}`,
				headers: {'Token': getToken()},
				data: this.userData
			})
			.then(({data}) => this.setSuccessMessage(data))
			.catch(({response}) => this.setErrors(response));
		},

		setErrors(response) {
			this.errors = response.data.error;
		},

		setSuccessMessage(data) {
			this.errors = {};
			this.saved = true;
			alert('User info updated.');
			window.location.href = '/user-list';
		},
	}
}

</script>