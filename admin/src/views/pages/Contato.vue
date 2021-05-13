<template>
    <div class="animated fadeIn">
        <loading :active="loading"></loading>
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong></strong>
                        <div class="pull-right actions">
                            <div class="d-inline save btn btn-success"
                                 @click="save">
                                <i class="fa fa-save"></i>
                            </div>
                        </div>
                    </div>
                    <form-errors :errors="errors"></form-errors>
                    <form class="form">
<!--                        <div class="form-group row">-->
<!--                            <label for="tedtitulo" class="col-sm-2 col-form-label">Título:</label>-->
<!--                            <div class="col-sm-10">-->
<!--                                <input type="text" class="form-control" id="tedtitulo" v-model="texts['about.pageContato.title']">-->
<!--                            </div>-->
<!--                        </div>-->
                        <div class="form-group row">
                            <label for="tentexto" class="col-sm-2 col-form-label">Texto:</label>
                            <div class="col-sm-10">
                                <markdown-editor id="tentexto"
                                                 :options="markDownOptions"
                                                 v-model="texts['about.pageContato.text']">
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

	export default {
		name: "PaginaContato",
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
					// 'about.pageContato.title': '',
					'about.pageContato.text': '',
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
			};
		},
		watch: {
		},
		computed: {
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
				let texts = {};

				for (let identifier of Object.keys(this.texts)) {
					texts[identifier] = this.texts[identifier];
				}

				_axios.patch('common-texts', texts)
					.then(() => {
						alert('salvo com sucesso');
					})
			},
		}
	}
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
