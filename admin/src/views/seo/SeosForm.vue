<template>
    <div class="animated fadeIn">
        <loading :active="loading"></loading>
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong>SEO </strong>
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
                            <label class="col-sm-2 col-form-label">Titulo:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" v-model="seo.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="metaTitulo" class="col-sm-2 col-form-label">Meta Título::</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="metaTitulo" v-model="seo.meta_title">
                            </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label">Meta Descrição:</label>
                          <div class="col-sm-10">
                                <textarea class="form-control" id="metaDescricao"
                                          v-model="seo.meta_description"></textarea>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label">Meta Palavras-chave:</label>
                          <div class="col-sm-10">
                            <textarea class="form-control" id="metaKeywords" v-model="seo.meta_keywords"></textarea>
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

	export default {
		name: "Seos",
		components: {
			FormErrors,
			Loading,
		},
        data() {
            return {
	            target: null,
	            loading: false,
                inputsArray: [
                    { value: '' }
                ],
	            errors: {},
                seo: {
	                  name: '',
                    meta_title:'',
                    meta_description:'',
                    meta_keywords:'',
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
		},
		created() {
			this.target = parseInt(this.$route.params.target);
			if (this.target) {
				_axios.get(`seos/${this.target}`)
					.then((response) => {
						for (let key of Object.keys(response.data)) {
                            this.seo[key] = response.data[key];
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
					_axios.post(`seos`, this.seo)
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
					_axios.put(`seos/${this.target}`, JSON.stringify(this.seo))
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

</style>
