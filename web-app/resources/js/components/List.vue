<template>
	<div class="container">
		<div class="row">
			<div class="col-sm">
				<h3>User Management
					<a class="btn btn-success btn-sm pull-right" href="/user/create">
						<i class="fa fa-plus"></i>
					</a>
				</h3>
				<table class="table">
					<thead>
						<tr>
							<th> <input type="checkbox"> </th>
							<th>{{ lang.id }}</th>
							<th>{{ lang.username }}</th>
							<th>{{ lang.email }}</th>
							<th>{{ lang.fullname }}</th>
							<th>{{ lang.actions }}</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="user in userData">
							<td><input type="checkbox" :value="user.id"></td>
							<td>{{ user.id }}</td>
							<td>{{ user.username }}</td>
							<td>{{ user.email }}</td>
							<td>{{ user.fullname }}</td>
							<td>
								<a class="btn btn-warning btn-sm" :href="'/user/edit/' + user.id">
									<i class="fa fa-pencil"></i>
								</a>
								&nbsp;
								<button class="btn btn-danger btn-sm" v-on:click="removeItem(user.id)">
									<i class="fa fa-trash"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>

<script>
import endpoints from '../endpoints';
import lang from '../lang';
import {getToken} from '../functions';

export default {
	mounted() {
		console.log('MAIN page');
	},
	data() {
		return {
			lang: lang,
			userData: [],
			pagecount: 1,
			apiUrl: endpoints.user_list,
			apiUrlDelete: endpoints.user_delete
		}
	},
	created() {
		if (!getToken()) {
			window.location.href = '/login';
		}
		this.getListData();
	},
	methods: {
		getListData(page = 1) {
			axios({
				method: 'get',
				url: `${this.apiUrl}${page}`,
				headers: {'Token': getToken()} 
			})
			.then(({data}) => {
				this.userData = data.data;
				this.pageCount = data.meta.last_page;
			});
		},
		removeItem(userId) {
			if (confirm("Delete item?")) {
				axios({
					method: 'delete',
					url: `${this.apiUrlDelete}/${userId}`,
					headers: {'Token': getToken()} 
				})
				.then(({data}) => {
					this.getListData();
				});				
			}
			console.log('userId', userId);
		}
	}
}
</script>