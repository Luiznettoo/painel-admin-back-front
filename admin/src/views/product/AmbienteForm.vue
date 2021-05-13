<template>
    <div class="animated fadeIn">
        <loading :active="loading"></loading>
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong>Ambiente </strong>
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
                                <input type="text" class="form-control" id="order" v-model="ambiente.ordem">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Título:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" v-model="ambiente.name">
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
                        <div class="form-group row">
                            <label for="url" class="col-sm-2 col-form-label">Url Amigável:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="url" v-model="ambiente.url">
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
		name: "AmbienteForm",
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
				ambiente: {
					ordem: '',
					name: '',
					file: null,
					file_id: null,
                    url: '',
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
				secondary_colors: false
			};
		},
		watch: {
		},
		computed: {
			ambienteFile() {
				if (this.ambiente.file) {
					return [this.ambiente.file];
				}

				return [];
			},
		},
		mounted() {
			this.target = parseInt(this.$route.params.target);

			if (this.target) {
				_axios.get(`ambiente/${this.target}`)
					.then((response) => {
						for (let key of Object.keys(response.data)) {
							this.ambiente[key] = response.data[key];
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
					_axios.post(`ambiente`, this.ambiente)
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
					_axios.put(`ambiente/${this.target}`, JSON.stringify(this.ambiente))
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
					this.ambiente.file_id = files[0].id;
					this.ambiente.file = files[0];
				}
				else {
					this.ambiente.file_id = null;
					this.ambiente.file = null;
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