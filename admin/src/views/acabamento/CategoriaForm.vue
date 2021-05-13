<template>
    <div class="animated fadeIn">
        <loading :active="loading"/>
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong>Categoria de Acabamentos </strong>
                        <small>CRIAR</small>
                        <div class="pull-right actions">
                            <div class="d-inline back btn btn-primary"
                                 @click="back">
                                <i class="fa fa-reply"/>
                            </div>
                            <div class="d-inline save btn btn-success"
                                 @click="save">
                                <i class="fa fa-save"/>
                            </div>
                        </div>
                    </div>
                    <form-errors :errors="errors"/>
                    <form class="form">
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Título:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" v-model="categoria.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ordem" class="col-sm-2 col-form-label">Ordem:</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="ordem" v-model="categoria.ordem">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2">Imagem:</label>
                            <div class="col-sm-10">
                                <file-selector max="1" mime-filter="image/*" :value="ambienteFile"
                                               @input="changeFile">
                                </file-selector>
                            </div>
                        </div>
                    </form>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>

<script>
	import FileSelector from '../Ftp/FileSelector';
	import FormErrors from '../../components/form-errors/validation-errors';
	import Loading from 'vue-loading-overlay';
	import 'vue-loading-overlay/dist/vue-loading.css';

	export default {
		name: "CategoriaForm",
		components: {
			FileSelector,
			FormErrors,
			Loading
		},
		data() {
			return {
				target: null,
				loading: false,
				errors: {},
				categoria: {
					name: '',
                    ordem: null,
					file: null,
					file_id: null
				},
				markDownOptions: {
					lineNumbers: true,
					styleActiveLine: true,
					styleSelectedText: true,
					lineWrapping: true,
					indentWithTabs: true,
					tabSize: 2,
					indentUnit: 2
				},
			};
		},
		watch: {
		},
		computed: {
			ambienteFile() {
				if (this.categoria.file) {
					return [this.categoria.file];
				}

				return [];
			},
		},
		mounted() {
			this.target = parseInt(this.$route.params.target);

			// console.log(this.target);
			if (this.target) {
				_axios.get(`categoriaAcabament/${this.target}`)
					.then((response) => {
						for (let key of Object.keys(response.data)) {
							this.categoria[key] = response.data[key];
						}
					});
			}
		},
		methods: {
			back() {
				this.$router.go(-1);
			},
			save() {
				// console.log(this.target);
				this.loading = true;
				if (!this.target) {
					_axios.post(`categoriaAcabament`, this.categoria)
						.then(() => {
							this.$router.go(-1);
						})
						.catch((e) => {
							this.errors = e.response.data;
						})
						.finally(() => {
							this.loading = false;
						});
				} else {
					_axios.put(`categoriaAcabament/${this.target}`, JSON.stringify(this.categoria))
						.then(() => {
							this.$router.go(-1);
						})
						.catch((e) => {
							this.errors = e.response.data;
						})
						.finally(() => {
							this.loading = false;
						});
				}
			},
			changeFile(files) {
				if (files.length) {
					this.categoria.file_id = files[0].id;
					this.categoria.file = files[0];
				}
				else {
					this.categoria.file_id = null;
					this.categoria.file = null;
				}
			},
		}
	}
</script>
<style scoped>
    .animated {
        margin-bottom: 170px;
    }

    >>> label {
        text-align: right;
    }

    .actions > div {
        font-size: 14px;
        margin-left: 12px;

        cursor: pointer;
    }

    >>> .add-color {
        display: inline-block;
        color: #049e00;
    }

    >>> .add-color, .remove-option {
        cursor: pointer;
    }

    .remove-option {
        display: flex;
        color: #9e0400;
        height: 100%;
        align-items: center;
    }

    .custom-checkbox {
        padding-left: 40px;
    }

    .opt-fields input {
        margin-bottom: 4px;
    }
</style>
