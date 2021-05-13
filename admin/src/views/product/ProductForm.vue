<template>
    <div class="animated fadeIn">
        <loading :active="loading"/>
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong>Produtos </strong>
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
                            <label class="col-sm-2 col-form-label">Categorias:</label>
                            <div class="col-sm-10">
                                <category-selector :data="dataCategorys" :value="product.categorys"
                                                   @input="changeCategory"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Destaque categoria:</label>
                            <div class="col-sm-10">
                                <input type="checkbox" v-model="product.destc" :checked="product.destc">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Ambientes:</label>
                            <div class="col-sm-10">
                                <ambiente-selector :data="dataAmbiente" :value="product.ambientes"
                                                   @input="changeAmbiente"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Tendencia:</label>
                            <div class="col-sm-10">
                                <input type="checkbox" v-model="product.tendencia" :checked="product.tendencia">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Destaque Ambiente:</label>
                            <div class="col-sm-10">
                                <input type="checkbox" v-model="product.desta" :checked="product.desta">
                            </div>
                        </div>

                        <div class="form-group row" v-if="product.ambientes.length">
                            <label class="col-sm-2">Produtos associados:</label>
                            <div class="col-sm-10">
                                <multiselect v-model="product.products" :value="product.products" :showNoOptions="false"
                                             :options="dataProduct" :multiple="true" :taggable="true" label="name"
                                             track-by="id" placeholder="Selecione aqui o produto"/>
                                <!--
                                                                <ambiente-selector :data="dataProduct" :value="product.products"
                                                                                  @input="changeProduct"></ambiente-selector>
                                -->
                            </div>
                        </div>

                        <hr>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Ativo:</label>
                            <div class="col-sm-10">
                                <input type="checkbox" v-model="product.ativo" :checked="product.ativo">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label for="ordem" class="col-sm-2 col-form-label">Ordem:</label>
                            <div class="col-sm-10">
                                <input type="number" ref="ordem" :class="{toma: ordemError}" class="form-control"
                                       id="ordem" v-model="product.ordem">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Nome:</label>
                            <div class="col-sm-10">
                                <input type="text" ref="name" class="form-control" id="name" v-model="product.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ref" class="col-sm-2 col-form-label">Ref:</label>
                            <div class="col-sm-10">
                                <input type="text" ref="ref" class="form-control" id="ref" v-model="product.ref">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-sm-2 col-form-label">Titulo:</label>
                            <div class="col-sm-10">
                                <input type="text" ref="title" class="form-control" id="title" v-model="product.title">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="url" class="col-sm-2 col-form-label">Url Amigável:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="url" v-model="product.url">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="price" class="col-sm-2 col-form-label">Preço (apenas promoções)</label>
                            <div class="col-sm-10">
                                <input type="number" step="0.01" ref="ref" class="form-control" id="price" v-model="product.price">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="promotion" class="col-sm-2 col-form-label">Preço promocional (apenas promoções)</label>
                            <div class="col-sm-10">
                                <input type="number" step="0.01" ref="ref" class="form-control" id="promotion" v-model="product.promotion">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-sm-2 col-form-label">Descrição:</label>
                            <div class="col-sm-10">
                                <markdown-editor ref="description" id="description"
                                                 :options="markDownOptions"
                                                 v-model="product.description">
                                </markdown-editor>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2">Imagens:</label>
                            <div class="col-sm-10">
                                <file-selector max="5" mime-filter="image/*" v-model="product.images">
                                </file-selector>
                            </div>
                        </div>
                        <!--
                                                <hr>
                                                <fieldset>
                                                    <legend>Grupo:
                                                        <small class="add-color" @click="addGroup"><i class="fa fa-plus"></i></small>
                                                    </legend>
                                                    <div class="col-sm-12">
                                                        <group v-for="(_, index) in product.groups"
                                                               v-model="product.groups[index]"
                                                               @remove="removeGroup"
                                                               :key="index"
                                                               :offset="index">
                                                        </group>
                                                    </div>
                                                </fieldset>
                        -->
                        <div class="form-group row">
                            <label class="col-sm-2">Opções:</label>
                            <div class="col-sm-10">
                                <ambiente-selector :data="dataOptions" :value="product.categoria_option"
                                                   @input="changeCategoriaOption"/>
                            </div>
                        </div>
                    </form>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>

<script>
	import OptionSelector from './OptionSelector';
	import FileSelector from '../Ftp/FileSelector';
	import FormErrors from '../../components/form-errors/validation-errors';
	import Loading from 'vue-loading-overlay';
	import AmbienteSelector from './AmbienteSelector';
	import CategorySelector from './CategorySelector';
	import 'vue-loading-overlay/dist/vue-loading.css';
	import ProductSelector from "./ProductSelector";
	import Multiselect from 'vue-multiselect';

	export default {
		name: "ProductForm",
		components: {
			Multiselect,
			ProductSelector,
			FileSelector,
			OptionSelector,
			AmbienteSelector,
			CategorySelector,
			FormErrors,
			Loading,
		},
		data() {
			return {
				target: null,
				outLine: '',
				ids: [],
				loading: false,
				errors: {},

				alert: '',
				ordemError: true,

				product: {
					category_id: null,
					name: '',
					ref: '',
					description: '',
                    title: '',
                    price: null,
                    promotion: null,
					ordem: '',
					url: '',
					desta: '',
					destc: '',
					body: '',
					images: [],
					file_id: null,
					file: null,
					categoria_option: [],
					categorys: [],
					ambientes: [],
					products: [],
					tendencia: false,
					ativo: true,
				},
				carregador: 'Carregado dados...',
				desta: false,
				destc: false,
				dataCategorys: [],
				dataAmbiente: [],
				dataOptions: [],
				dataProduct: [],
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
			dataProduct() {
				this.$forceUpdate()
			}
		},
		computed: {
			productFile() {
				if (this.product.images && this.product.images.length) {
					return this.product.images;
				}
				return [];
			},
		},
		created() {
			this.target = parseInt(this.$route.params.target);

			_axios.get('categories?ativo=1')
				.then((response) => {
					this.dataCategorys = response.data;
				});
			_axios.get('ambiente')
				.then((response) => {
					this.dataAmbiente = response.data;
				});
			_axios.get('product?per-page=9999')
				.then((response) => {
					this.dataProduct = response.data.data;
				});
			_axios.get('categoriaOption')
				.then((response) => {
					this.dataOptions = response.data;
				});

			if (this.target) {
				_axios.get(`product/${this.target}`)
					.then((response) => {
						for (let key of Object.keys(response.data)) {
							if (key === 'ambientes') {
								response.data[key].forEach((ambiente) => {
									this.product[key].push(ambiente.id);
								})
							} else if (key === 'categorys') {
								response.data[key].forEach((category) => {
									this.product[key].push(category.id);
								})
							} else if (key === 'categoria_option') {
								response.data[key].forEach((category) => {
									this.product[key].push(category.id);
								})
							} else {
								this.product[key] = response.data[key];
							}
						}
						if (this.product.desta === '1') {
							this.desta = true;
						} else {
							this.desta = false;
						}
						if (this.product.destc === '1') {
							this.destc = true;
						} else {
							this.destc = false;
						}
					});
			}
		},
		methods: {
			back() {
				this.$router.go(-1);
			},
			save() {
				if (!this.verificaForm()) {
					return;
				}
				this.product.products.forEach((item) => {
					this.ids.push(item.id);
				});

				this.loading = true;
				if (this.product.desta) {
					this.product.desta = 1;
				} else {
					this.product.desta = 0;
				}
				if (this.product.destc) {
					this.product.destc = 1;
				} else {
					this.product.destc = 0;
				}

				this.product.products = this.ids;

				if (!this.target) {
					_axios.post(`product`, this.product)
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
					_axios.put(`product/${this.target}`, JSON.stringify(this.product))
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
			verificaForm() {
				const refs = [];
				if (this.product.ordem === '') {
					this.alert += 'Campo Ordem não preenchido.\n';
					this.ordemError = false;
					refs.push('ordem');
				}
				if (this.product.name === '') {
					this.alert += 'Campo Nome não preenchido.\n';
					this.ordemError = false;
					refs.push('name');
				}
				if (this.product.ref === '') {
					this.alert += 'Campo Referencia não preenchido.\n';
					this.ordemError = false;
					refs.push('ref');
				}
				if (this.product.title === '') {
					this.alert += 'Campo Titulo não preenchido.\n';
					this.ordemError = false;
					refs.push('title');
				}
				if (this.product.description === '') {
					this.alert += 'Campo Descrição não preenchido.\n';
					this.ordemError = false;
					refs.push('description');
				}
				refs.forEach(this.mark);
				if (this.alert) {
					alert(this.alert);
					this.alert = '';
				}
				return this.ordemError;
			},
			mark(item, index) {
				const desc = document.querySelector('#' + item);
				if (index === 0) {
					let height = desc.getBoundingClientRect().top;
					height = height - 110;

					window.scrollTo({
						top: height,
						behavior: 'smooth',
					});
				}
				desc.style.border = '1px solid red';
			},
			addGroup() {
				this.product.groups.push({
					name: '',
					options: [],
				});
			},
			removeGroup(offset) {
				this.product.groups.splice(offset, 1);
			},

			changeCategoriaOption(options) {
				if (options.length) {
					this.product.categoria_option = options;
				} else {
					this.product.categoria_option = [];
				}
			},
			changeAmbiente(ambientes) {
				if (ambientes.length) {
					this.product.ambientes = ambientes;
				} else {
					this.product.ambientes = [];
				}
			},
			changeProduct(products) {
				if (products.length) {
					this.product.products = products;
				} else {
					this.product.products = [];
				}
			},
			changeCategory(categorys) {
				if (categorys.length) {
					this.product.categorys = categorys;
				} else {
					this.product.categorys = [];
				}
			},
			changeFile(files) {
				if (files.length) {
					this.product.file_id = files[0].id;
					this.product.images = files;
				} else {
					this.product.file_id = null;
					this.product.images = [];
				}
			},
			toggleSelection(check) {
				if (check === true) {

				}
			}
		}
	}
</script>

<!--<style>-->
<!--    .multiselect__option {-->
<!--        content: 'Vadia' !important;-->
<!--    }-->
<!--</style>-->
<style scoped>
    .toma:focus {
        border: 1px solid red;
        box-shadow: none;
    }

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