<template>
    <b-row>
        <b-col cols="12" xl="12">
            <transition name="slide">
                <b-card>
                    <div slot="header">
                        <strong>Categorias de Blog </strong>
                        <small>LISTA</small>

                        <div class="pull-right actions">
                            <router-link class="d-inline save btn btn-success"
                                         :to="{name: 'Cadastrar Categorias de Blog'}">
                                <i class="fa fa-plus"></i>
                            </router-link>
                        </div>
                    </div>
                    <table class="table table-striped table-default">
                        <thead>
                        <tr>
                            <th class="w-5">#</th>
                            <th class="w-85">Nome</th>
                            <th class="w-5 text-center">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(data, offset) in items">
                            <td>{{ data.id }}</td>
                            <td>{{ data.name }}</td>
                            <td class="text-center">
                                <router-link class="fa fa-edit fa-lg"
                                             :to="{name: 'Cadastrar Categorias de Blog', params: {target: data.id}}">
                                </router-link>
                                <a class="fa fa-trash fa-lg"
                                   :data-offset="offset"
                                   @click.stop.prevent="deleteSingle"></a>
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
		name: "Categoria",
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
				_axios.get(`categoriaBlog?page=${page}&per-page=${this.perPage}`)
					.then((response) => {
						this.items = response.data.data;
						this.totalRows = response.data.total;
					});
			},
			deleteSingle(e) {
				let offset = parseInt(e.target.dataset.offset);
				let self = this;

				$.confirm({
					title: 'Atenção',
					content: `Deseja realmente deletar a categoria do blog <b>${self.items[offset].name}</b>?`,
					type: 'red',
					closeIcon: true,
					useBootstrap: true,
					columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3',
					buttons: {
						confirmar() {
							_axios.delete(`categoriaBlog/${self.items[offset].id}`)
								.then(() => {
									self.items.splice(offset, 1);
								});

							return true;
						},
						cancelar() {
						}
					}
				});
			}
		}
	}
</script>

<style scoped>
    .card-body >>> table > tbody > tr > td {
        cursor: pointer;
    }

    >>> .id-col {
        max-width: 100px;
    }

    table a {
        text-decoration: none !important;
        margin-right: 5px;
    }

    .green {
        color: #02c700;
    }

    .red {
        color: #c70200;
    }

    .w-5 {
        width: 5% !important;
    }

    .w-10 {
        width: 10% !important;
    }
</style>