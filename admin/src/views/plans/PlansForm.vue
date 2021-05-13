<template>
    <div class="animated fadeIn">
        <loading :active="loading"></loading>
        <b-row>
            <b-col md="12">
                <b-card>
                    <div slot="header">
                        <strong>Planos</strong>
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
                                <input type="text" class="form-control" id="name" v-model="plan.title">
                            </div>
                        </div>
                        <div class="form-group row">
                          <label for="text" class="col-sm-2 col-form-label">Descrição do Plano::</label>
                          <div class="col-sm-10">
                            <markdown-editor
                              id="text"
                              :options="markDownOptions"
                              v-model="plan.description">
                            </markdown-editor>
                          </div>
                        </div>
                        <div class="form-group row">
                            <label for="price" class="col-sm-2 col-form-label">Preço</label>
                          <div class="col-sm-10">
                            <input type="number" step="0.01" ref="ref" class="form-control" id="price" v-model="plan.price">
                          </div>
                        </div>
                        <div class="form-group row"
                             v-for="(_, offset) in plan.topicos"
                             :key="offset">
                            <label class="col-sm-2 col-form-label">Tópicos {{offset + 1}} :</label>
                            <div class=" col-sm-10 input-topico">
                              <input style="margin-bottom: 5px;"
                                     @keyup="handleInput"
                                     id="metas"
                                     class="form-control"
                                     v-model="plan.topicos[offset].topico"/>
                              <div @click="removeMeta(offset)" :class="{'icon': true, 'hidden': offset === plan.topicos.length - 1}">
                                <i class="fas fa-trash"/>
                              </div>
                            </div>


                        </div>
                    </form>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>

<script>
	import FormErrors from '../../components/form-errors/validation-errors';
	import Loading from 'vue-loading-overlay';
	import 'vue-loading-overlay/dist/vue-loading.css';

	export default {
		name: "Plans",
		components: {
			FormErrors,
			Loading,
		},
        data() {
            return {
	            target: null,
	            loading: false,
	            errors: {},
                plan: {
                    title: '',
                    description:'',
                    price: null,
                    topicos: [
                        { topico: '' }
                    ],
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
		},
		created() {
			this.target = parseInt(this.$route.params.target);
			if (this.target) {
				_axios.get(`plans/${this.target}`)
					.then((response) => {
						for (let key of Object.keys(response.data)) {
                            this.plan[key] = response.data[key];
						}
					});
			}

		},
		methods: {
        handleInput(event) {
            const ENTERKEY = 13;

            if (event.keyCode === ENTERKEY) {
                this.plan.topicos.push({ id: -1, topico: ''});
            }
        },
        removeMeta(offset) {
            if(this.plan.topicos.length != 1){
                this.plan.topicos.splice(offset, 1);
            }
        },
			back() {
				this.$router.go(-1);
			},
			save() {
				this.loading = true;
				if (!this.target) {
					_axios.post(`plans`, this.plan)
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
					_axios.put(`plans/${this.target}`, JSON.stringify(this.plan))
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

  .input-topico {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 15px;
  }

  .input-topico input {
     margin-right: 15px;
  }
  .input-topico .icon {
    margin-bottom: 5px;
    cursor: pointer;
  }

  .input-topico .icon:hover {
    color: red;
  }

</style>
