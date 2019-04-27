<template>
	<div class="container">
		<button class="btn btn-link btn-sm pull-right" v-on:click="logout()">{{ lang.logout }}</button>
		<div class="row">
			<div class="col-sm page-content">
				<h3>{{ lang.user_management }}
					<a class="btn btn-success btn-sm pull-right" href="/user/create">
						{{lang.add}}
					</a>
				</h3>
				<table class="table">
					<thead>
						<tr>
							<th>
								<input type="checkbox" v-on:click="toggleSelectAll($event)">
							</th>
							<th>{{ lang.id }}</th>
							<th>{{ lang.username }}</th>
							<th>{{ lang.email }}</th>
							<th>{{ lang.fullname }}</th>
							<th>{{ lang.actions }}</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="user in userData">
							<td>
								<input type="checkbox" :value="user.id" v-on:click="modifyListToDelete(user.id, $event)" :checked="allSelected">
							</td>
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
				<button class="btn btn-danger btn-sm" v-on:click="removeItems()" :disabled="!hasItemsToDelete">
					{{ lang.remove_selected }}
				</button>
			</div>
		</div>
	</div>
</template>

<script>
import endpoints from '../endpoints';
import lang from '../lang';
import {getToken, removeToken} from '../functions';

export default {
	mounted() {
		console.log('List component mounted');
	},
	data() {
		return {
			lang: lang,
			userData: [],
			pagecount: 1,
			apiUrl: endpoints.user_list,
			apiUrlDelete: endpoints.user_delete,
			apiUrlDeleteMultiple: endpoints.user_delete_multiple,
			apiUrlLogout: endpoints.logout_post,
			idsToDelete: [],
			allSelected: false,
			hasItemsToDelete: false
		}
	},
	created() {
		this.getListData();
	},
	beforeCreate() {
		if (!getToken()) {
			window.location.href = '/login';
		}
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
				// this.pageCount = data.meta.last_page;
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
		},
		removeItems(){
			if (confirm("Delete selected item(s)?")) {
				let selectedUserIDsToDelete = this.idsToDelete.join(',');
				axios({
					method: 'delete',
					url: `${this.apiUrlDeleteMultiple}${selectedUserIDsToDelete}`,
					headers: {'Token': getToken()}
				})
				.then(({data}) => {
					this.getListData();
				});
			}
		},
		modifyListToDelete(userId, event) {
			if (event.target.checked) {
				this.idsToDelete.push(userId);
			} else {
				this.idsToDelete = this.idsToDelete.filter((item) => {
					return item !== userId;
				});
			}
			this.hasItemsToDelete = (this.idsToDelete.length > 0);
		},
		toggleSelectAll(event) {
			this.allSelected = event.target.checked;
			this.idsToDelete = [];
			if (event.target.checked) {
				for (let index = 0; index < this.userData.length; index++) {
					let element = this.userData[index];
					this.idsToDelete.push(element.id);
				}
			}
			this.hasItemsToDelete = (this.idsToDelete.length > 0);
		},
		logout() {
			axios({
				method: 'post',
				url: this.apiUrlLogout,
				headers: {'Token': getToken()}
			})
			.then(({data}) => {
				removeToken();
				window.location.href = '/login';
			});
		}
	}
}
</script>