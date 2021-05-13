<template>
    <b-row>
        <b-col cols="12" xl="12">
            <transition name="slide">
                <b-card>
                    <div slot="header">
                        <strong>Newsletter </strong>
                        <small>LISTA</small>
                    </div>
                    <table class="table table-striped table-default">
                        <thead>
                        <tr>
                            <th class="w-5">#</th>
                            <th>Nome</th>
                            <th>E-mail</th>
<!--
                            <th class="w-5 text-center">Ações</th>
-->
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(data, offset) in items">
                            <td>{{ data.id }}</td>
                            <td>{{ data.nome }}</td>
                            <td>{{ data.email }}</td>
                            <td>{{ data.assunto }}</td>
<!--
                            <td class="text-center">
                                <router-link class="fa fa-edit fa-lg"
                                             :to="{name: 'Cadastrar Instagram', params: {target: data.id}}">
                                </router-link>
                                <a class="fa fa-trash fa-lg"
                                   :data-offset="offset"
                                   @click.stop.prevent="deleteSingle"></a>
                            </td>
-->
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
		name: "Newsletter",
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
		computed: {},
		methods: {
			fetch(page = 1) {
				_axios.get(`newsletter?page=${page}&per-page=${this.perPage}`)
					.then((response) => {
						this.items = response.data.data;
						this.totalRows = response.data.total;
					});
			},
		}
	}
</script>

<style scoped>

</style>
