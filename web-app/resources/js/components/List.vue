<template>
	<div class="container">
		<div class="row">
			<div class="col-sm">
				<h3>User Management
					<button class="btn btn-success btn-sm pull-right">
						<i class="fa fa-plus"></i>
					</button>
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
								<button class="btn btn-danger btn-sm">
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

export default {
	mounted() {
		console.log('MAIN page');
		console.log('ENDPOINTS', endpoints);
	},
	data() {
		return {
			lang: lang,
			userData: [],
			pagecount: 1,
			apiUrl: endpoints.user_list
		}
	},
	created() {
		this.getListData();
	},
	methods: {
		getListData(page = 1) {
			axios.get(`${this.apiUrl}${page}`)
				.then(({data}) => {
					this.userData = data.data;
					this.pageCount = data.meta.last_page;
				});
		}
	}
}
</script>