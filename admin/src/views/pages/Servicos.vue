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
            <div class="form-group row">
              <label for="ambtitulo" class="col-sm-2 col-form-label">Título:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="ambtitulo"
                       v-model="texts['about.pageServico.title']">
              </div>
            </div>
            <div class="form-group row">
              <label for="ambtexto" class="col-sm-2 col-form-label">Texto:</label>
              <div class="col-sm-10">
                <markdown-editor id="ambtexto"
                                 :options="markDownOptions"
                                 v-model="texts['about.pageServico.text']">
                </markdown-editor>
              </div>
            </div>
            <div class="form-group row">
              <label for="iconeApr" class="col-sm-2 col-form-label">Icones:</label>
              <div class="col-sm-10">
                <file-selector id="iconeApr" max="1" mime-filter="image/*" v-model="images['servico']">
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
    import VueMultiselect from "vue-multiselect/src/Multiselect";

    export default {
        name: "PaginaServiços",
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
                    'about.pageServico.titulo': '',
                    'about.pageServico.texto': '',
                },
                images: {
                    // 'banner': [],
                    'servico': [],
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
