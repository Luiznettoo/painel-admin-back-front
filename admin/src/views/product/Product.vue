<template>
    <b-row>
        <b-col cols="12" xl="12">
            <transition name="slide">
                <b-card>
                    <div slot="header">
                        <strong>Publicações </strong>
                        <small>LISTA</small>

                        <div class="pull-right actions">
                            <router-link class="d-inline save btn btn-success"
                                         :to="{name: 'Cadastrar Produto'}">
                                <i class="fa fa-plus"></i>
                            </router-link>
                        </div>
                    </div>

                    <b-input-group prepend="" class="mt-3" style="margin-bottom: 10px;">
                        <b-form-input class="quevocequiser" @keyup.enter="fetch(page = 1)" v-model="busca" placeholder="Busque aqui..."></b-form-input>
                        <b-input-group-append>
                            <b-button variant="info" @click="fetch(page = 1)">Buscar</b-button>
                        </b-input-group-append>
                    </b-input-group>

                    <table class="table table-striped table-default">
                        <thead>
                        <tr>
                            <th class="w-5">#</th>
                            <th>Nome</th>
                            <th>Ordem</th>
                            <th>Ref</th>
                            <th class="w-5 text-center">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(data, offset) in items">
                            <td>{{ data.id }}</td>
                            <td>{{ data.name }}</td>
                            <td>{{ data.ordem }}</td>
                            <td>{{ data.ref }}</td>
                            <td class="text-center">
                                <router-link class="fa fa-edit fa-lg"
                                             :to="{name: 'Cadastrar Produto', params: {target: data.id}}">
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
		name: "Product",
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
				totalRows: 0,
                busca: '',
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
			fetch(page) {
				_axios.get(`product`,{
					params: {
						page: page,
                        'per-page': this.perPage,
                        busca: this.busca,
                        ativo: 1,
                        'skip-order': 1,
                    }
                })
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
					content: `Deseja realmente deletar o produto <b>${self.items[offset].name}</b>?`,
					type: 'red',
					closeIcon: true,
					useBootstrap: true,
					columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3',
					buttons: {
						confirmar() {
							_axios.delete(`product/${self.items[offset].id}`)
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
    .quevocequiser {
    }

    .quevocequiser::placeholder {
        color: rgba(0, 0, 0, .3);
        font-size: 0.875rem;
    }

    table {
        margin-top: 30px;
    }
</style>