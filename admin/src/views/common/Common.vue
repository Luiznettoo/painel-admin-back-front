<template>
    <div class="animated fadeIn">
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong>Geral </strong>
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
                    <form class="form">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                            <validation-errors v-if="Object.keys(errors).length"
                                               :errors="errors">
                            </validation-errors>
                        </div>
                        <div class="form-group row">
                            <label for="meta-title" class="col-sm-2 col-form-label">Email:</label>
                            <div class="col-sm-10">
                                <input id="meta-title"
                                       v-model="common['contact.email']"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="telefone" class="col-sm-2 col-form-label">Telefone:</label>
                            <div class="col-sm-10">
                                <input id="telefone"
                                       v-model="common['telefone']"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="telefone" class="col-sm-2 col-form-label">Whatsapp:</label>
                            <div class="col-sm-10">
                                <input id="whatsapp"
                                       v-model="common['whatsapp']"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cnpj" class="col-sm-2 col-form-label">CNPJ:</label>
                            <div class="col-sm-10">
                                <input id="cnpj"
                                       v-model="common['cnpj']"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="razao" class="col-sm-2 col-form-label">Razão Social:</label>
                            <div class="col-sm-10">
                                <input id="razao"
                                       v-model="common['razao']"
                                       class="form-control"/>
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
    import ValidationErrors from '../widgets/ValidationErrors';

    export default {
        name: 'Forms',
        components: {
            FileSelector,
            ValidationErrors
        },
        data() {
            return {
                show: true,
                errors: {},
                common: {
                    'contact.email': '',
                    'telefone': '',
                    'whatsapp': '',
                    'cnpj': '',
                    'razao': '',

                }
            };
        },
        mounted() {
            _axios.get('common-texts')
                  .then((response) => {
                      for (let common of response.data) {
                          this.common[common.identifier] = common.content;
                      }
                  });
        },
        methods: {
            back() {
                this.$route.push('/');
            },
            save() {
                let common = {};

                for (let identifier of Object.keys(this.common)) {
                    common[identifier] = this.common[identifier];
                }

                _axios.patch('common-texts', common)
                      .then(() => {
                          alert('salvo com sucesso');
                      })
            }
        }
    };
</script>

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
