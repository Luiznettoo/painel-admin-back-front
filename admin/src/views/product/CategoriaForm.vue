<template>
    <div class="animated fadeIn">
        <loading :active="loading"></loading>
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong>Categoria de Produto </strong>
                        <small>CRIAR</small>
                        <div class="pull-right actions">
                            <div class="d-inline back btn btn-primary"
                                 @click="back">
                                <i class="fa fa-reply"></i>
                            </div>
                            <div class="d-inline save btn btn-success"
                                 @click="save">
                                <i class="fa fa-save"></i>
                            </div>
                        </div>
                    </div>
                    <form-errors :errors="errors"></form-errors>
                    <form class="form">
                        <div class="form-group row">
                            <label for="order" class="col-sm-2 col-form-label">Ordem:</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="order" v-model="categoria.ordem">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Ativo:</label>
                            <div class="col-sm-10">
                                <input type="checkbox" v-model="categoria.ativo" :checked="categoria.ativo">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Nome:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" v-model="categoria.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2">Imagem:</label>
                            <div class="col-sm-10">
                                <file-selector max="1" mime-filter="image/*" :value="categoriaFile"
                                               @input="changeFile">
                                </file-selector>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2">Banner:</label>
                            <div class="col-sm-10">
                                <file-selector max="1" mime-filter="image/*" :value="bannerFile"
                                               @input="changeBanner">
                                </file-selector>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="url" class="col-sm-2 col-form-label">Url Amigável:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="url" v-model="categoria.url">
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
					ordem: '',
					name: '',
					files: [],
					file_id: null,
                    file: null,
					banner_id: null,
                    banner: null,
                    url: '',
                    ativo: 1,
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
				categs: [],
				secondary_colors: false
			};
		},
		watch: {
		},
		computed: {
			categoriaFile() {
				if (this.categoria.file) {
					return [this.categoria.file];
				}

				return [];
			},
			bannerFile() {
				if (this.categoria.banner) {
					return [this.categoria.banner];
				}

				return [];
			},
		},
		mounted() {
			this.target = parseInt(this.$route.params.target);

			if (this.target) {
				_axios.get(`categories/${this.target}`)
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
				this.loading = true;
				if (!this.target) {
					_axios.post(`categories`, this.categoria)
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
					_axios.put(`categories/${this.target}`, JSON.stringify(this.categoria))
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
			changeBanner(files) {
				if (files.length) {
					this.categoria.banner_id = files[0].id;
					this.categoria.banner = files[0];
				}
				else {
					this.categoria.banner_id = null;
					this.categoria.banner = null;
				}
			},
		}

	}
</script>

<style scoped>

</style>