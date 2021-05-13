<template>
    <div class="animated fadeIn">
        <loading :active="loading"></loading>
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong>Depoimentos </strong>
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
                        <h4>Depoimento</h4>
                        <div class="form-group row">
                            <label for="nome" class="col-sm-2 col-form-label">Nome:</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="nome" v-model="testimonial.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cargo" class="col-sm-2 col-form-label">Cargo:</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="cargo" v-model="testimonial.cargo">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="text" class="col-sm-2 col-form-label">Texto:</label>
                            <div class="col-sm-10">
                              <markdown-editor
                                id="text"
                                :options="markDownOptions"
                                v-model="testimonial.texto2">
                              </markdown-editor>
                            </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label">Imagem:</label>
                          <div class="col-sm-10">
                            <file-selector max="1" mime-filter="image/*" :value="categoriaFile"
                                           @input="changeFile">
                            </file-selector>
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
                testimonial: {
	                  name: '',
                    texto2: '',
                    cargo: '',
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
				if (this.testimonial.file) {
					return [this.testimonial.file];
				}

				return [];
			},
		},
		created() {
			this.target = parseInt(this.$route.params.target);
			if (this.target) {
				_axios.get(`testimonials/${this.target}`)
					.then((response) => {
						for (let key of Object.keys(response.data)) {
                            this.testimonial[key] = response.data[key];
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
					_axios.post(`testimonials`, this.testimonial)
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
					_axios.put(`testimonials/${this.target}`, JSON.stringify(this.testimonial))
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
					this.testimonial.file_id = files[0].id;
					this.testimonial.file = files[0];
				}
				else {
					this.testimonial.file_id = null;
					this.testimonial.file = null;
				}
			},
		}
	}
</script>

<style scoped>

</style>
