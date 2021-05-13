<template>
    <div class="animated fadeIn">
        <loading :active="loading"></loading>
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong>Home</strong>
                        <div class="pull-right actions">
                            <div class="d-inline save btn btn-success"
                                 @click="save">
                                <i class="fa fa-save"></i>
                            </div>
                        </div>
                    </div>
                    <form-errors :errors="errors"></form-errors>
                    <form class="form">
<!--                      <h4>Banners</h4>-->
<!--                      <div class="form-group row">-->
<!--                          <label for="galeriaBanner" class="col-sm-2 col-form-label">Galeria:</label>-->
<!--                          <div class="col-sm-10">-->
<!--                              <file-selector id="galeriaBanner" max="4" mime-filter="image/*" v-model="images['banner']">-->
<!--                              </file-selector>-->
<!--                          </div>-->
<!--                      </div>-->
<!--                      <hr>-->
                      <h4>Apresentação:</h4>
                      <div class="form-group row">
                          <label for="ambtitulo" class="col-sm-2 col-form-label">Título:</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control" id="ambtitulo"
                                     v-model="texts['home.apresentacao1.titulo']">
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="ambtexto" class="col-sm-2 col-form-label">Texto:</label>
                          <div class="col-sm-10">
                              <markdown-editor id="ambtexto"
                                               :options="markDownOptions"
                                               v-model="texts['home.apresentacao1.texto']">
                              </markdown-editor>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="ambtitulo1" class="col-sm-2 col-form-label">Título:</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="ambtitulo1"
                                   v-model="texts['home.apresentacao2.titulo']">
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="ambtexto1" class="col-sm-2 col-form-label">Texto:</label>
                          <div class="col-sm-10">
                            <markdown-editor id="ambtexto1"
                                             :options="markDownOptions"
                                             v-model="texts['home.apresentacao2.texto']">
                            </markdown-editor>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="ambtitulo2" class="col-sm-2 col-form-label">Título:</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="ambtitulo2"
                                   v-model="texts['home.apresentacao3.titulo']">
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="ambtexto2" class="col-sm-2 col-form-label">Texto:</label>
                          <div class="col-sm-10">
                            <markdown-editor id="ambtexto2"
                                             :options="markDownOptions"
                                             v-model="texts['home.apresentacao3.texto']">
                            </markdown-editor>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="iconeApr" class="col-sm-2 col-form-label">Icones:</label>
                          <div class="col-sm-10">
                              <file-selector id="iconeApr" max="3" mime-filter="image/*" v-model="images['apresentacao']">
                              </file-selector>
                          </div>
                      </div>
                      <hr>
                      <h4>Estrutura:</h4>
                      <div class="form-group row">
                          <label for="estruTitulo" class="col-sm-2 col-form-label">Título:</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="estruTitulo"
                                   v-model="texts['home.estrutura.titulo']">
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="estrutexto" class="col-sm-2 col-form-label">Texto:</label>
                          <div class="col-sm-10">
                            <markdown-editor id="estrutexto"
                                             :options="markDownOptions"
                                             v-model="texts['home.estrutura.texto']">
                            </markdown-editor>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="estruGaleria" class="col-sm-2 col-form-label">Galeria:</label>
                          <div class="col-sm-10">
                            <file-selector id="estruGaleria" max="3" mime-filter="image/*" v-model="images['estrutura']">
                            </file-selector>
                          </div>
                      </div>
                      <hr>
                      <h4>A Clinica:</h4>
                      <div class="form-group row">
                        <label for="sobreTitulo" class="col-sm-2 col-form-label">Título:</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="sobreTitulo"
                                 v-model="texts['home.sobre.titulo']">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="sobreText" class="col-sm-2 col-form-label">Texto:</label>
                        <div class="col-sm-10">
                          <markdown-editor
                            id="sobreText"
                            :options="markDownOptions"
                            v-model="texts['home.sobre.texto']">
                          </markdown-editor>
                        </div>
                      </div>
                      <hr>
                      <h4>Planos:</h4>
                      <div class="form-group row">
                          <label for="planosTitulo" class="col-sm-2 col-form-label">Título:</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="planosTitulo"
                                   v-model="texts['home.planos.titulo']">
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="planosText" class="col-sm-2 col-form-label">Texto:</label>
                          <div class="col-sm-10">
                            <markdown-editor
                              id="planosText"
                              :options="markDownOptions"
                              v-model="texts['home.planos.texto']">
                            </markdown-editor>
                          </div>
                      </div>
                      <hr>
                      <h4>Últimos Posts:</h4>
                      <div class="form-group row">
                          <label for="uposttitulo" class="col-sm-2 col-form-label">Título:</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="uposttitulo"
                                   v-model="texts['home.upost.titulo']">
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="uposttext" class="col-sm-2 col-form-label">Texto:</label>
                          <div class="col-sm-10">
                            <markdown-editor
                              id="uposttext"
                              :options="markDownOptions"
                              v-model="texts['home.upost.texto']">
                            </markdown-editor>
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
	import VueMultiselect from "vue-multiselect/src/Multiselect";

	export default {
		name: "Home",
		components: {
			VueMultiselect,
			FileSelector,
			FormErrors,
			Loading
		},
		data() {
			return {
				target: null,
				loading: false,
				errors: {},
				texts: {
					'home.apresentacao1.titulo': '',
					'home.apresentacao1.texto': '',
					'home.apresentacao2.titulo': '',
					'home.apresentacao2.texto': '',
					'home.apresentacao3.titulo': '',
					'home.apresentacao3.texto': '',
					'home.depoimento.titulo': '',
					'home.depoimento.texto': '',
					'home.ambiente.titulo': '',
					'home.ambiente.texto': '',
          'home.planos.titulo': '',
          'home.planos.texto': '',
					'home.upost.titulo': '',
					'home.upost.texto': '',
					'home.estrutura.titulo': '',
					'home.estrutura.texto': '',
					'home.sobre.titulo': '',
					'home.sobre.texto': '',
				},
				images: {
					// 'banner': [],
					'apresentacao': [],
					'estrutura': [],
					'homecard3': [],
					'homecard4': [],
					'homecarda1': [],
					'homecarda2': [],
					'homecarda3': [],
					'homecarda4': [],
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
		watch: {},
		computed: {
			categoryArr() {
				if (!this.text.category_id) {
					return [];
				}

				return [this.text.category_id]
			}
		},
		mounted() {
			this.target = parseInt(this.$route.params.target);

			_axios.get('common-texts')
				.then((response) => {
					for (let texts of response.data) {
						this.texts[texts.identifier] = texts.content;
					}
				});
			_axios.get('banners')
				.then((response) => {
					for (let banner of response.data) {
						this.images[banner.identifier] = banner.images;
					}
				});
		},
		methods: {
			back() {
				this.$router.go(-1);
			},
			save() {
				this.loading = true;

				// salva os textos
				let texts = {};
				for (let identifier of Object.keys(this.texts)) {
					texts[identifier] = this.texts[identifier];
				}

				_axios.patch('common-texts', texts)
					.then(() => {

					});


				//salva as images
				let images = {};

				for (let identifier of Object.keys(this.images)) {
					images[identifier] = [];

					for (let banner of this.images[identifier]) {
						images[identifier].push(banner.id);
					}
				}

				_axios.patch('banners', images)
					.then(() => {
						this.loading = false;
						alert('salvo com sucesso');
					})

			},
		}
	}
</script>

<!--<style>-->
<!--    .multiselect__option {-->
<!--        content: 'Vadia' !important;-->
<!--    }-->
<!--</style>-->

<style scoped>
    label {
        text-align: right;
    }

    .actions > div {
        font-size: 14px;
        margin-left: 12px;

        cursor: pointer;
    }
</style>
