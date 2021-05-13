<template>
    <div class="animated fadeIn">
        <loading :active="loading"></loading>
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong>Equipe </strong>
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
                                <input type="text" class="form-control" id="name" v-model="team.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">CRO:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="cro" v-model="team.cro">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="texto" class="col-sm-2 col-form-label">Texto:</label>
                            <div class="col-sm-10">
                              <markdown-editor
                                id="texto"
                                :options="markDownOptions"
                                v-model="team.texto">
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
		name: "TeamsForm",
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
                team: {
	                  name: '',
                    texto: '',
                    cro: '',
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
				if (this.team.file) {
					return [this.team.file];
				}

				return [];
			},
		},
		created() {
			this.target = parseInt(this.$route.params.target);
			if (this.target) {
				_axios.get(`teams/${this.target}`)
					.then((response) => {
						for (let key of Object.keys(response.data)) {
                            this.team[key] = response.data[key];
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
					_axios.post(`teams`, this.team)
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
					_axios.put(`teams/${this.target}`, JSON.stringify(this.team))
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
					this.team.file_id = files[0].id;
					this.team.file = files[0];
				}
				else {
					this.team.file_id = null;
					this.team.file = null;
				}
			},
		}
	}
</script>

<style scoped>

</style>
