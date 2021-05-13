<template>
    <div class="animated fadeIn">
        <loading :active="loading"></loading>
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong>Grupo de Opcionais </strong>
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
                            <label for="name" class="col-sm-2 col-form-label">Título:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" v-model="groupoption.name">
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
		name: "GroupOptionForm",
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
				groupoption: {
					name: '',
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

			if (this.target) {
				_axios.get(`group_option/${this.target}`)
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
					_axios.post(`group_option`, this.groupoption)
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
		}
	}
</script>

<style scoped>

</style>