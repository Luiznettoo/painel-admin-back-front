<template>
    <div class="animated fadeIn">
        <loading :active="loading"/>
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong>Opcionais </strong>
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
                            <label for="category" class="col-sm-2">Categoria:</label>
                            <div class="col-sm-10">
                                <select id="category" v-if="categorys" v-model="option.category_id">
                                    <option v-for="item in categorys" :value="item.id" :key="item.id">{{item.name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Título:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" v-model="option.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ordem" class="col-sm-2 col-form-label">Ordem:</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="ordem" v-model="option.ordem">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2">Imagem:</label>
                            <div class="col-sm-10">
                                <file-selector max="1" mime-filter="image/*" :value="optionFile"
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
		name: "OptionForm",
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
				option: {
					name: '',
                    ordem: null,
                    file: null,
					file_id: null,
					category_id: null,
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
				categorys: [],
			};
		},
		watch: {
		},
		computed: {
			optionFile() {
				if (this.option.file) {
					return [this.option.file];
				}

				return [];
			},
			groupArr() {
				if (!this.option.category_id) {
					return [];
				}
				return [this.option.category_id];
			},
		},
		mounted() {
			this.target = parseInt(this.$route.params.target);

			_axios.get('categoriaOption')
				.then((response) => {
					this.categorys = response.data;
				});


			if (this.target) {
				_axios.get(`option/${this.target}`)
					.then((response) => {
						for (let key of Object.keys(response.data)) {
							this.option[key] = response.data[key];
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
					_axios.post(`option`, this.option)
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
					_axios.put(`option/${this.target}`, JSON.stringify(this.option))
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
			changeGroup(groups) {
				if (groups.length) {
					this.option.category_id =  groups[0];
				}else {
					this.option.category_id = null;
				}
			},
			changeFile(files) {
				if (files.length) {
					this.option.file_id = files[0].id;
					this.option.file = files[0];
				}
				else {
					this.option.file_id = null;
					this.option.file = null;
				}
			},
		}
	}
</script>

<style scoped>

</style>