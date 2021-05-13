<template>
    <div class="animated fadeIn">
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong>Textos do site </strong>
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
                            <label for="meta-title" class="col-sm-2 col-form-label">Meta Title:</label>
                            <div class="col-sm-10">
                                <input id="meta-title"
                                       v-model="texts['title']"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="meta-title" class="col-sm-2 col-form-label">Meta Keywords:</label>
                            <div class="col-sm-10">
                                <input id="meta-keywords"
                                       v-model="texts['keywords']"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="meta-description" class="col-sm-2 col-form-label">Meta Description:</label>
                            <div class="col-sm-10">
                                <input id="meta-description"
                                       v-model="texts['description']"
                                       class="form-control"/>
                            </div>
                        </div>
                        <hr>
                        <h3>Home</h3>
                        <div class="form-group row">
                            <label for="htitle" class="col-sm-2 col-form-label">Home - Titulo:</label>
                            <div class="col-sm-10">
                                <input id="htitle"
                                       v-model="texts['htitle']"
                                       class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hstitle" class="col-sm-2 col-form-label">Home - SubTitulo:</label>
                            <div class="col-sm-10">
                                <input id="hstitle"
                                       v-model="texts['hstitle']"
                                       class="form-control"
                                />
                            </div>
                        </div>
                        <hr>
                        <h3>Textos principais abaixo 1º  banner</h3>
                        <div class="form-group row">
                            <label for="hqcard1title" class="col-sm-2 col-form-label">Home - Titulo Quality Card 1:</label>
                            <div class="col-sm-10">
                                <input id="hqcard1title"
                                       v-model="texts['hqcard1title']"
                                       class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hqcard1text" class="col-sm-2 col-form-label">Home - Texto Quality Card 1:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="hqcard1text"
                                                 :options="markDownOptions"
                                                 v-model="texts['hqcard1text']">
                                </markdown-editor>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hqcard2title" class="col-sm-2 col-form-label">Home - Titulo Quality Card 2:</label>
                            <div class="col-sm-10">
                                <input id="hqcard2title"
                                       v-model="texts['hqcard2title']"
                                       class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hqcard2text" class="col-sm-2 col-form-label">Home - Texto Quality Card 2:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="hqcard2text"
                                                 :options="markDownOptions"
                                                 v-model="texts['hqcard2text']">
                                </markdown-editor>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hqcard3title" class="col-sm-2 col-form-label">Home - Titulo Quality Card 3:</label>
                            <div class="col-sm-10">
                                <input id="hqcard3title"
                                       v-model="texts['hqcard3title']"
                                       class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hqcard3text" class="col-sm-2 col-form-label">Home - Texto Quality Card 3:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="hqcard3text"
                                                 :options="markDownOptions"
                                                 v-model="texts['hqcard3text']">
                                </markdown-editor>
                            </div>
                        </div>
                        <hr>
                        <h3>Textos abaixo do vídeo</h3>
                        <div class="form-group row">
                            <label for="htitle" class="col-sm-2 col-form-label">Home - Titulo Video Card 1:</label>
                            <div class="col-sm-10">
                                <input id="hvcard1title"
                                       v-model="texts['hvcard1title']"
                                       class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hvcard1text" class="col-sm-2 col-form-label">Home - Texto Video Card 1:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="hvcard1text"
                                                 :options="markDownOptions"
                                                 v-model="texts['hvcard1text']">
                                </markdown-editor>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hvcard2title" class="col-sm-2 col-form-label">Home - Titulo Video Card 2:</label>
                            <div class="col-sm-10">
                                <input id="hvcard2title"
                                       v-model="texts['hvcard2title']"
                                       class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hvcard2text" class="col-sm-2 col-form-label">Home - Texto Video Card 2:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="hvcard2text"
                                                 :options="markDownOptions"
                                                 v-model="texts['hvcard2text']">
                                </markdown-editor>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hvcard3title" class="col-sm-2 col-form-label">Home - Titulo Video Card 3:</label>
                            <div class="col-sm-10">
                                <input id="hvcard3title"
                                       v-model="texts['hvcard3title']"
                                       class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hvcard3text" class="col-sm-2 col-form-label">Home - Texto Video Card 3:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="hvcard3text"
                                                 :options="markDownOptions"
                                                 v-model="texts['hvcard3text']">
                                </markdown-editor>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row">
                            <label for="htitleproduct" class="col-sm-2 col-form-label">Home - Titulo Produto:</label>
                            <div class="col-sm-10">
                                <input id="htitleproduct"
                                       v-model="texts['htitleproduct']"
                                       class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="htextproduct" class="col-sm-2 col-form-label">Home - Texto Produto:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="htextproduct"
                                                 :options="markDownOptions"
                                                 v-model="texts['htextproduct']">
                                </markdown-editor>
                            </div>
                        </div>
                        <hr>
                        <h3>Textos da caixa perto do rodapé</h3>
                        <div class="form-group row">
                            <label for="hccard1text" class="col-sm-2 col-form-label">Home - Banners Card 1 Texto:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="hccard1text"
                                                 :options="markDownOptions"
                                                 v-model="texts['hccard1text']">
                                </markdown-editor>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hccard2text" class="col-sm-2 col-form-label">Home - Banners Card 2 Texto:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="hccard2text"
                                                 :options="markDownOptions"
                                                 v-model="texts['hccard2text']">
                                </markdown-editor>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hccard3text" class="col-sm-2 col-form-label">Home - Banners Card 3 Texto:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="hccard3text"
                                                 :options="markDownOptions"
                                                 v-model="texts['hccard3text']">
                                </markdown-editor>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row">
                            <label for="htitlecontact" class="col-sm-2 col-form-label">Home - Titulo Contato:</label>
                            <div class="col-sm-10">
                                <input id="htitlecontact"
                                       v-model="texts['htitlecontact']"
                                       class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="htextcontact" class="col-sm-2 col-form-label">Home - Texto Contato:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="htextcontact"
                                                 :options="markDownOptions"
                                                 v-model="texts['htextcontact']">
                                </markdown-editor>
                            </div>
                        </div>
                        <hr>
                        <h3>Quem Somos</h3>
                        <div class="form-group row">
                            <label for="qtitle" class="col-sm-2 col-form-label">Quem Somos - Titulo:</label>
                            <div class="col-sm-10">
                                <input id="qtitle"
                                       v-model="texts['qtitle']"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="qtitle1" class="col-sm-2 col-form-label">Quem Somos - Titulo 1:</label>
                            <div class="col-sm-10">
                                <input id="qtitle1"
                                       v-model="texts['qtitle1']"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="qtext1" class="col-sm-2 col-form-label">Quem Somos - Texto 1:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="qtext1"
                                                 :options="markDownOptions"
                                                 v-model="texts['qtext1']">
                                </markdown-editor>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="qtitle2" class="col-sm-2 col-form-label">Quem Somos - Titulo 2:</label>
                            <div class="col-sm-10">
                                <input id="qtitle2"
                                       v-model="texts['qtitle2']"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="qtext2" class="col-sm-2 col-form-label">Quem Somos - Texto 2:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="qtext2"
                                                 :options="markDownOptions"
                                                 v-model="texts['qtext2']">
                                </markdown-editor>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="qtitle3" class="col-sm-2 col-form-label">Quem Somos - Titulo 3:</label>
                            <div class="col-sm-10">
                                <input id="qtitle3"
                                       v-model="texts['qtitle3']"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="qtext3" class="col-sm-2 col-form-label">Quem Somos - Texto 3:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="qtext3"
                                                 :options="markDownOptions"
                                                 v-model="texts['qtext3']">
                                </markdown-editor>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label for="qcard1" class="col-sm-2 col-form-label">Quem Somos - Card 1:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="qcard1"
                                                 :options="markDownOptions"
                                                 v-model="texts['qcard1']">
                                </markdown-editor>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="qcard2" class="col-sm-2 col-form-label">Quem Somos - Card 2:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="qcard2"
                                                 :options="markDownOptions"
                                                 v-model="texts['qcard2']">
                                </markdown-editor>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="qcard3" class="col-sm-2 col-form-label">Quem Somos - Card 3:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="qcard3"
                                                 :options="markDownOptions"
                                                 v-model="texts['qcard3']">
                                </markdown-editor>
                            </div>
                        </div>
                        <hr>
                        <h3>Produtos</h3>
                        <div class="form-group row">
                            <label for="ptitle" class="col-sm-2 col-form-label">Produtos - Titulo (Background):</label>
                            <div class="col-sm-10">
                                <input id="ptitle"
                                       v-model="texts['ptitle']"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pstitle" class="col-sm-2 col-form-label">Produtos - Título ( Cards de produtos ) :</label>
                            <div class="col-sm-10">
                                <input id="pstitle"
                                       v-model="texts['pstitle']"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ptext" class="col-sm-2 col-form-label">Produtos - Texto:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="ptext"
                                                 :options="markDownOptions"
                                                 v-model="texts['ptext']">
                                </markdown-editor>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="poutitle" class="col-sm-2 col-form-label">Titulo dentro dos produtos:</label>
                            <div class="col-sm-10">
                                <input id="poutitle"
                                       v-model="texts['poutitle']"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="poutext" class="col-sm-2 col-form-label">Texto dentro dos produtos:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="poutext"
                                                 :options="markDownOptions"
                                                 v-model="texts['poutext']">
                                </markdown-editor>
                            </div>
                        </div>
                        <hr>
                        <h3>Contato</h3>
                        <div class="form-group row">
                            <label for="contitle" class="col-sm-2 col-form-label">Contato - Titulo:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="contitle"
                                                 :options="markDownOptions"
                                                 v-model="texts['contitle']">
                                </markdown-editor>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="consubtitle" class="col-sm-2 col-form-label">Contato - SubTitulo:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="consubtitle"
                                                 :options="markDownOptions"
                                                 v-model="texts['consubtitle']">
                                </markdown-editor>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="context" class="col-sm-2 col-form-label">Contato - Texto:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="context"
                                                 :options="markDownOptions"
                                                 v-model="texts['context']">
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
    import ValidationErrors from '../widgets/ValidationErrors';
    import {cloneObject} from "../../shared/utils";

    export default {
        name: 'Forms',
        components: {
            FileSelector,
            ValidationErrors
        },
        data() {
            return {
                selected: [], // Must be an array reference!
                show: true,
                errors: {},
                texts: {
                    'title' : '',
                    'keywords' : '',
                    'description' : '',
                    'htitle' : '',
                    'hstitle' : '',
                    'hqcard1title' : '',
                    'hqcard1text' : '',
                    'hqcard2title' : '',
                    'hqcard2text' : '',
                    'hqcard3title' : '',
                    'hqcard3text' : '',
                    'hvcard1title' : '',
                    'hvcard1text' : '',
                    'hvcard2title' : '',
                    'hvcard2text' : '',
                    'hvcard3title' : '',
                    'hvcard3text' : '',
                    'htitleproduct' : '',
                    'htextproduct' : '',
                    'hccard1text' : '',
                    'hccard2text' : '',
                    'hccard3text' : '',
                    'htitlecontact' : '',
                    'htextcontact' : '',
                    'qtitle' : '',
                    'qtitle1' : '',
                    'qtext1' : '',
                    'qtitle2' : '',
                    'qtext2' : '',
                    'qtitle3' : '',
                    'qtext3' : '',
                    'qcard1' : '',
                    'qcard2' : '',
                    'qcard3' : '',
                    'ptitle' : '',
                    'pstitle' : '',
                    'ptext' : '',
                    'poutitle' : '',
                    'poutext' : '',
                    'contitle' : '',
                    'consubtitle' : '',
                    'context' : '',
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
            };
        },
        mounted() {
            _axios.get('common-texts')
                  .then((response) => {
                      for (let texts of response.data) {
                          this.texts[texts.identifier] = texts.content;
                      }
                  });
        },
        methods: {
            back() {
                this.$route.push('/');
            },
            save() {
                let texts = {};

                for (let identifier of Object.keys(this.texts)) {
                    texts[identifier] = this.texts[identifier];
                }

                _axios.patch('common-texts', texts)
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
