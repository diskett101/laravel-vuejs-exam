<template>
	<div class="well well-sm" id="user-edit-form">
		<form class="form-horizontal" method="post" @submit.prevent="onSubmit">
			<fieldset>
				<legend class="text-center">Create User</legend>

				<div class="form-group">
					<label class="col-md-3 control-label" for="username">Username</label>
					<div class="col-md-12" :class="{'has-error': errors.username}">
						<input id="username" v-model="userData.username" type="text" placeholder="Username" class="form-control">
						<span v-if="errors.username" class="help-block text-danger">{{ errors.username[0] }}</span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label" for="email">Email</label>
					<div class="col-md-12" :class="{'has-error': errors.email}">
						<input id="email" v-model="userData.email" type="text" placeholder="Email" class="form-control">
						<span v-if="errors.email" class="help-block text-danger">{{ errors.email[0] }}</span>
					</div>
				</div>


				<div class="form-group">
					<label class="col-md-3 control-label" for="password">Password</label>
					<div class="col-md-12" :class="{'has-error': errors['password']}">
						<input id="password" v-model="userData.password" type="password" placeholder="Password" class="form-control">
						<span v-if="errors['password']" class="help-block text-danger">{{ errors.password[0] }}</span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label" for="confirm_password">Confirm Password</label>
					<div class="col-md-12" :class="{'has-error': errors.confirm_password}">
						<input id="confirm_password" v-model="userData.confirm_password" type="password" placeholder="Confirm Password" class="form-control">
						<span v-if="errors.confirm_password" class="help-block text-danger">{{ errors.confirm_password[0] }}</span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label" for="firstname">Firstname</label>
					<div class="col-md-12" :class="{'has-error': errors.firstname}">
						<input id="firstname" v-model="userData.firstname" type="text" placeholder="Firstname" class="form-control">
						<span v-if="errors.firstname" class="help-block text-danger">{{ errors.firstname[0] }}</span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label" for="lastname">Lastname</label>
					<div class="col-md-12" :class="{'has-error': errors.lastname}">
						<input id="lastname" v-model="userData.lastname" type="text" placeholder="Lastname" class="form-control">
						<span v-if="errors.lastname" class="help-block text-danger">{{ errors.lastname[0] }}</span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label" for="address">Address</label>
					<div class="col-md-12" :class="{'has-error': errors.address}">
						<input id="address" v-model="userData.address" type="text" placeholder="Address" class="form-control">
						<span v-if="errors.address" class="help-block text-danger">{{ errors.address[0] }}</span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label" for="postcode">Post Code</label>
					<div class="col-md-12" :class="{'has-error': errors.postcode}">
						<input id="postcode" v-model="userData.postcode" type="text" placeholder="Post Code" class="form-control">
						<span v-if="errors.postcode" class="help-block text-danger">{{ errors.postcode[0] }}</span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label" for="phone_number">Phone Number</label>
					<div class="col-md-12" :class="{'has-error': errors.phone_number}">
						<input id="phone_number" v-model="userData.phone_number" type="text" placeholder="Phone Number" class="form-control">
						<span v-if="errors.phone_number" class="help-block text-danger">{{ errors.phone_number[0] }}</span>
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-12">
						<a href="/user-list" class="btn btn-secondary btn-lg pull-left">Back</a>
						<button type="submit" class="btn btn-primary btn-lg pull-right">Submit</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</template>name

<script>
import endpoints from '../endpoints';
import lang from '../lang';
import {getToken} from '../functions';

export default {
	mounted() {
		console.log('this.$route.params', this.$route.params);
	},
	data() {
		return {
			lang: lang,
			errors: {},
			saved: false,
			userData: {},
			apiUrlPost: endpoints.user_create,
			userId: null
		};
	},
	created() {
		if (!getToken()) {
			window.location.href = '/login';
		}
		// this.getUserData(this.$route.params.user_id);
	},
	methods: {
		// getUserData(userId) {
		// 	this.userId = userId;
		// 	axios({
		// 		method: 'get',
		// 		url: `${this.apiUrlGet}/${this.userId}`,
		// 		headers: {'Token': getToken()} 
		// 	})
		// 	.then(({data}) => {
		// 		this.userData = data.data;
		// 	});
		// },
		onSubmit() {
			this.saved = false;
			axios({
				method: 'post',
				url: this.apiUrlPost,
				headers: {'Token': getToken()},
				data: this.userData
			})
			.then(({data}) => this.setSuccessMessage(data))
			.catch(({response}) => this.setErrors(response));
		},

		setErrors(response) {
			console.log('ERROR RESPONSE', response);
			
			this.errors = response.data.error;
		},

		setSuccessMessage(data) {
			console.log('response data', data);
			this.errors = {};
			this.saved = true;
			alert('User added.');
			window.location.href = '/user-list';
		},
	}
}
	
</script>