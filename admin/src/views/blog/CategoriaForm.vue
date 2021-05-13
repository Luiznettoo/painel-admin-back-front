<template>
    <div class="animated fadeIn">
        <loading :active="loading"></loading>
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong>Categoria de Acabamentos </strong>
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
                            <label for="name" class="col-sm-2 col-form-label">Título:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" v-model="categoria.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2">Cor:</label>
                            <div class="col-sm-10">
                                <color-selector @input="mudaCor" v-model="color"></color-selector>
                            </div>
                        </div>
                    </form>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>

<script>
	import FormErrors from '../../components/form-errors/validation-errors';
	import Loading from 'vue-loading-overlay';
	import 'vue-loading-overlay/dist/vue-loading.css';
	import ColorSelector from './ColorSelector';
	import {Chrome} from 'vue-color';

	export default {
		name: "CategoriaForm",
		components: {
			FormErrors,
			Loading,
            Chrome,
            ColorSelector,
		},
		data() {
			return {
				target: null,
				loading: false,
				errors: {},
				categoria: {
					name: '',
                    color: '#0000FF',
				},
                color: '#0000FF',
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
		},
		mounted() {
			this.target = parseInt(this.$route.params.target);

			if (this.target) {
				_axios.get(`categoriaBlog/${this.target}`)
					.then((response) => {
						for (let key of Object.keys(response.data)) {
							this.categoria[key] = response.data[key];
						}
						if(this.categoria.color){
							this.color = this.categoria.color;
                        }
					});
			}
		},
		methods: {
			mudaCor(){
			    this.categoria.color = this.color.hex;
            },
			back() {
				this.$router.go(-1);
			},
			save() {
				this.loading = true;
				if (!this.target) {
					_axios.post(`categoriaBlog`, this.categoria)
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
					_axios.put(`categoriaBlog/${this.target}`, JSON.stringify(this.categoria))
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