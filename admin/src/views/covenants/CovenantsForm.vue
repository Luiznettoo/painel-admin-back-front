<template>
    <div class="animated fadeIn">
        <loading :active="loading"></loading>
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong>Convênios </strong>
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
                  <h4>Preview</h4>
                  <form class="form">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Título:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="titulo" v-model="covenant.titulo">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Imagem :</label>
                            <div class="col-sm-10">
                              <file-selector max="1" mime-filter="image/*" :value="categoriaFile"
                                             @input="changeFile">
                              </file-selector>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Link:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="link" v-model="covenant.link">
                            </div>
                        </div>
                        <hr>
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
		name: "TestimonialsForm",
		components: {
			FileSelector,
			FormErrors,
			Loading,
		},
        data() {
            return {
	            target: null,
	            loading: false,
	            errors: {},
                covenant: {
	                  titulo:'',
                    link: '',
                    file: null,
	                  file_id: null,
                },
                markDownOptions: {
                    lineNumbers: true,
                    styleActiveLine: true,
                    styleSelectedText: true,
                    lineWrapping: true,
                    indentWithTabs: true,
                    tabSize: 2,
                    indentUnit: 2
                }

            }
        },
		computed: {
			categoriaFile() {
				if (this.covenant.file) {
					return [this.covenant.file];
				}

				return [];
			},
		},
		created() {
			this.target = parseInt(this.$route.params.target);
			if (this.target) {
				_axios.get(`covenants/${this.target}`)
					.then((response) => {
						for (let key of Object.keys(response.data)) {
                            this.covenant[key] = response.data[key];
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
					_axios.post(`covenants`, this.covenant)
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
					_axios.put(`covenants/${this.target}`, JSON.stringify(this.covenant))
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
					this.covenant.file_id = files[0].id;
					this.covenant.file = files[0];
				}
				else {
					this.covenant.file_id = null;
					this.covenant.file = null;
				}
			},
		}
	}
</script>

<style scoped>

</style>
