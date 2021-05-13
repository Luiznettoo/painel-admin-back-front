<template>
  <div class="animated fadeIn">
    <loading :active="loading"></loading>
    <b-row>
      <b-col md="12">
        <b-card>
          <div slot="header">
            <strong>Blog</strong>
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
              <label for="title" class="col-sm-2 col-form-label">Titulo:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="title" v-model="blog.title">
              </div>
            </div>
            <div class="form-group row">
              <label for="atendimento" class="col-sm-2 col-form-label">Autor:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="atendimento" v-model="blog.autor">
              </div>
            </div>
            <div class="form-group row">
              <label for="url" class="col-sm-2 col-form-label">Url Amigável:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="url" v-model="blog.url">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2">Categoria:</label>
              <div class="col-sm-10">
                <select v-if="categs" v-model="blog.category_id">
                  <option v-for="(item, offset) in categs" :value="item.id">{{item.name}}</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2">Imagem do Autor:</label>
              <div class="col-sm-10">
                <file-selector max="1" mime-filter="image/*" :value="blogFile"
                               @input="changeFile">
                </file-selector>
              </div>
            </div>
            <div class="form-group row">
              <label for="texto1" class="col-sm-2 col-form-label">Descrição:</label>
              <div class="col-sm-10">
                <markdown-editor id="texto1"
                                 :options="markDownOptions"
                                 v-model="blog.text">
                </markdown-editor>
              </div>
            </div>
            <div class="form-group row">
              <label for="texto2" class="col-sm-2 col-form-label">Corpo:</label>
              <div class="col-sm-10">
                <markdown-editor id="texto2"
                                 :options="markDownOptions"
                                 v-model="blog.body">
                </markdown-editor>
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
              <label class="col-sm-2">Imagem de Capa:</label>
              <div class="col-sm-10">
                <file-selector max="1" mime-filter="image/*" :value="capaFile"
                               @input="capaBanner">
                </file-selector>
              </div>
            </div>

            <hr>
            <h4>SEO Publicação</h4>
            <div class="form-group row">
              <label for="metaTitulo" class="col-sm-2 col-form-label">Meta Título::</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="metaTitulo" v-model="blog.meta_title">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Meta Descrição:</label>
              <div class="col-sm-10">
                <textarea class="form-control" id="metaDescricao" v-model="blog.meta_description"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Meta Palavras-chave:</label>
              <div class="col-sm-10">
                <textarea class="form-control" id="metaKeywords" v-model="blog.meta_keywords"></textarea>
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
        name: "ServicoForm",
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
                blog: {
                    category_id: null,
                    text: '',
                    body: '',
                    autor:'',
                    meta_title:'',
                    meta_description:'',
                    meta_keywords:'',
                    files: [],
                    file_id: null,
                    file: null,
                    banner_id: null,
                    banner: null,
                    capa_id: null,
                    capa: null,
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
                categs: null,
                secondary_colors: false
            };
        },
        watch: {
        },
        computed: {
            blogFile() {
                if (this.blog.file) {
                    return [this.blog.file];
                }

                return [];
            },
            bannerFile() {
                if (this.blog.banner) {
                    return [this.blog.banner];
                }

                return [];
            },
            capaFile() {
                if (this.blog.capa) {
                    return [this.blog.capa];
                }

                return [];
            },
            categoryArr() {
                if (!this.blog.category_id) {
                    return [];
                }

                return [this.blog.category_id]
            },
        },
        mounted() {
            this.target = parseInt(this.$route.params.target);

            _axios.get('categoriaBlog')
                .then((response) => {
                    this.categs = response.data;
                });

            if (this.target) {
                _axios.get(`blog/${this.target}`)
                    .then((response) => {
                        for (let key of Object.keys(response.data)) {
                            this.blog[key] = response.data[key];
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
                    _axios.post(`blog`, this.blog)
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
                    _axios.put(`blog/${this.target}`, JSON.stringify(this.blog))
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
                    this.blog.file_id = files[0].id;
                    this.blog.file = files[0];
                }
                else {
                    this.blog.file_id = null;
                    this.blog.file = null;
                }
            },
            changeBanner(files) {
                if (files.length) {
                    this.blog.banner_id = files[0].id;
                    this.blog.banner = files[0];
                }
                else {
                    this.blog.banner_id = null;
                    this.blog.banner = null;
                }
            },
            capaBanner(files) {
                if (files.length) {
                    this.blog.capa_id = files[0].id;
                    this.blog.capa = files[0];
                }
                else {
                    this.blog.capa_id = null;
                    this.blog.capa = null;
                }
            },
        }

    }
</script>

<style scoped>

</style>
