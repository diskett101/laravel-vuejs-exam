<template>
	<div class="well well-sm" id="signature-form">
		<form class="form-horizontal" method="post" @submit.prevent="onSubmit">
			<fieldset>
				<legend class="text-center">Edit User info</legend>

				<div class="form-group">
					<label class="col-md-3 control-label" for="name">Name</label>
					<div class="col-md-12" :class="{'has-error': errors.name}">
						<input id="name" v-model="userData.username" type="text" placeholder="Your name" class="form-control">
						<span v-if="errors.name" class="help-block text-danger">{{ errors.name[0] }}</span>
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
			errors: [],
			saved: false,
			userData: {},
			apiUrlGet: endpoints.user_data,
			apiUrlPut: endpoints.user_update,
			userId: null
		};
	},
	created() {
		this.getUserData(this.$route.params.user_id);
	},
	methods: {
		getUserData(userId) {
			this.userId = userId;
			console.log('API URL', `${this.apiUrlGet}/${userId}`);
			axios.get(`${this.apiUrlGet}/${userId}`)
			.then(({data}) => {
				console.log('data', data);			
				this.userData = data.data;
				console.log('this.userData', this.userData);			
			});
		},
		onSubmit() {
			this.saved = false;

			axios.put(this.apiUrlPut, this.signature)
				.then(({data}) => this.setSuccessMessage())
				.catch(({response}) => this.setErrors(response));
		},

		setErrors(response) {
			this.errors = response.data.errors;
		},

		setSuccessMessage() {
			// this.reset();
			this.saved = true;
		},

		// reset() {
		// 	this.errors = [];
		// 	this.signature = {name: null, email: null, body: null};
		// }

	}
}
	
</script>