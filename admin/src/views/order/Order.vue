<template>
    <b-row>
        <b-col cols="12" xl="12">
            <transition name="slide">
                <b-card>
                    <div slot="header">
                        <strong>Orçamentos </strong>
                        <small>LISTA</small>

                    </div>
                    <table class="table table-striped table-default">
                        <thead>
                        <tr>
                            <th class="w-5">#</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Data</th>
                            <th class="w-5 text-center">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(data, offset) in items">
                            <td>{{ data.id }}</td>
                            <td>{{ data.name }}</td>
                            <td>{{ data.email }}</td>
                            <td>{{ data.created_at | formatDate }}</td>
                            <td class="text-center">
                                <router-link class="fas fa-eye fa-lg"
                                             :to="{name: 'Visualizar Orçamento', params: {target: data.id}}">
                                </router-link>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <nav class="pull-right">
                        <b-pagination size="sm"
                                      :total-rows="totalRows"
                                      :per-page="perPage"
                                      v-model="currentPage"
                                      prev-text="Prev"
                                      next-text="Next" hide-goto-end-buttons/>
                    </nav>
                </b-card>
            </transition>
        </b-col>
    </b-row>
</template>

<script>
	export default {
		name: "Order",
		props: {
			caption: {
				type: String,
				default: 'Blog'
			},
		},
		data() {
			return {
				items: [],
				currentPage: 1,
				perPage: 20,
				totalRows: 0
			};
		},
		watch: {
			currentPage: {
				handler(page) {
					this.fetch(page);
				},
				immediate: true
			}
		},
        filters: {
		formatDate(val) {
			let date = new Date(val);
			// let novaData = `${date.getDate()}/${date.getMonth()+1}/${date.getUTCFullYear()}`;
            let novaData = date.toLocaleString();
			return novaData;
        }
        },
		computed: {},
		methods: {
			fetch(page = 1) {
				_axios.get(`order?page=${page}&per-page=${this.perPage}`)
					.then((response) => {
						this.items = response.data.data;
						this.totalRows = response.data.total;
					});
			}
		}
	}
</script>

<style scoped>

</style>