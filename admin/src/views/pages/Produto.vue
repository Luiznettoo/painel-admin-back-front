<template>
    <div class="animated fadeIn">
        <loading :active="loading"></loading>
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong>Home </strong>
                        <div class="pull-right actions">
                            <div class="d-inline save btn btn-success"
                                 @click="save">
                                <i class="fa fa-save"></i>
                            </div>
                        </div>
                    </div>
                    <form-errors :errors="errors"></form-errors>
                    <form class="form">
                        <h4>Tendencias</h4>
                        <div class="form-group row">
                            <label for="prodtitulo" class="col-sm-2 col-form-label">Título:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="prodtitulo"
                                       v-model="texts['produto.titulo']">
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
		name: "Produto",
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
				texts: {
					'produto.titulo': '',
				}
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
	                    this.loading = false;
	                    alert('salvo com sucesso');
                    });


			}
		}
	}
</script>

<style scoped>

</style>