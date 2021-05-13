<template>
  <div class="animated fadeIn">
    <loading :active="loading"></loading>
    <b-row>
      <b-col md="12">
        <b-card>
          <div slot="header">
            <strong>Serviços </strong>
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
                <input type="number" class="form-control" id="order" v-model="service.ordem">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Ativo:</label>
              <div class="col-sm-10">
                <input type="checkbox" v-model="service.ativo" :checked="service.ativo">
              </div>
            </div>
            <div class="form-group row">
              <label for="title" class="col-sm-2 col-form-label">Titulo:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="title" v-model="service.title">
              </div>
            </div>
<!--            <div class="form-group row">-->
<!--              <label for="atendimento" class="col-sm-2 col-form-label">Dias de Atendimento::</label>-->
<!--              <div class="col-sm-10">-->
<!--                <input type="text" class="form-control" id="atendimento" v-model="service.atendimento">-->
<!--              </div>-->
<!--            </div>-->
<!--            <div class="form-group row">-->
<!--              <label for="horario" class="col-sm-2 col-form-label">Horário de Atendimento::</label>-->
<!--              <div class="col-sm-10">-->
<!--                <input type="text" class="form-control" id="horario" v-model="service.horario">-->
<!--              </div>-->
<!--            </div>-->
<!--            <div class="form-group row">-->
<!--              <label for="promocao" class="col-sm-2 col-form-label">Promoção::</label>-->
<!--              <div class="col-sm-10">-->
<!--                <input type="text" class="form-control" id="promocao" v-model="service.promocao">-->
<!--              </div>-->
<!--            </div>-->
            <div class="form-group row">
              <label for="texto1" class="col-sm-2 col-form-label">Descrição:</label>
              <div class="col-sm-10">
                <markdown-editor id="texto1"
                                 :options="markDownOptions"
                                 v-model="service.descricao">
                </markdown-editor>
              </div>
            </div>
             <div class="form-group row">
              <label for="texto2" class="col-sm-2 col-form-label">Texto:</label>
              <div class="col-sm-10">
                <markdown-editor id="texto2"
                                 :options="markDownOptions"
                                 v-model="service.texto">
                </markdown-editor>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2">Imagem:</label>
              <div class="col-sm-10">
                <file-selector max="1" mime-filter="image/*" :value="serviceFile"
                               @input="changeFile">
                </file-selector>
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
              <label for="url" class="col-sm-2 col-form-label">Url Amigável:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="url" v-model="service.url">
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
                service: {
                    ordem: '',
                    title: '',
                    // atendimento: '',
                    // promocao: '',
                    // horario: '',
                    descricao: '',
                    texto: '',
                    files: [],
                    file_id: null,
                    file: null,
                    banner_id: null,
                    banner: null,
                    url: '',
                    ativo: 1,
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
        watch: {
        },
        computed: {
            serviceFile() {
                if (this.service.file) {
                    return [this.service.file];
                }

                return [];
            },
            bannerFile() {
                if (this.service.banner) {
                    return [this.service.banner];
                }

                return [];
            },
        },
        mounted() {
            this.target = parseInt(this.$route.params.target);

            if (this.target) {
                _axios.get(`services/${this.target}`)
                    .then((response) => {
                        for (let key of Object.keys(response.data)) {
                            this.service[key] = response.data[key];
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
                    _axios.post(`services`, this.service)
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
                    _axios.put(`services/${this.target}`, JSON.stringify(this.service))
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
                    this.service.file_id = files[0].id;
                    this.service.file = files[0];
                }
                else {
                    this.service.file_id = null;
                    this.service.file = null;
                }
            },
            changeBanner(files) {
                if (files.length) {
                    this.service.banner_id = files[0].id;
                    this.service.banner = files[0];
                }
                else {
                    this.service.banner_id = null;
                    this.service.banner = null;
                }
            },
        }

    }
</script>

<style scoped>

</style>
