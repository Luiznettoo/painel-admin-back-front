<template>
    <b-row>
        <b-col cols="12" xl="12">
            <transition name="slide">
                <b-card id="body">
                    <div slot="header">
                        <strong>Orçamentos </strong>
                        <small>Informações</small>
                        <div class="pull-right actions">
                            <div class="d-inline back btn btn-primary"
                                 @click="back">
                                <i class="fa fa-reply"></i>
                            </div>
                            &nbsp;
                            <div class="d-inline back btn btn-success"
                                 @click="print">
                                <i class="fa fa-print"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nome:</label>
                        <div class="col-sm-10">
                            {{order.name}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">E-mail:</label>
                        <div class="col-sm-10">
                            {{order.email}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Telefone:</label>
                        <div class="col-sm-10">
                            {{order.tel}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Cidade/UF:</label>
                        <div class="col-sm-10">
                            {{order.cidade + '/' + order.estado}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Mensagem:</label>
                        <div class="col-sm-10">
                            {{order.msg}}
                        </div>
                    </div>
                    <b-card>
                        <div slot="header">
                            <strong>Produtos</strong>
                        </div>
                        <div v-if="order.order_products">
                            <div v-for="(item, offfset) in order.order_products">
                                <div v-if="item.products">
                                    Produto: {{item.name}}<br>
                                    Imagem:<br>
                                    <img :src="item.products.images[0].permalink" style="width:100px"><br>
                                    Quantidade:
                                    <span v-if="item.quantity">{{item.quantity}}</span>
                                    <span v-else>1</span>
                                    <br>
                                    Opções:
                                    <div v-if="item.order_options.length || Object.keys(item.order_options).length">
                                        <div v-for="(option,key) in item.order_options">
                                            <img :src="option.options[0].file.permalink" style="width:30px">
                                            <span>{{option.name}}</span>
                                        </div>
                                    </div>
                                    <span v-else>Nenhuma opção selecionado.</span>

                                    <hr>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            Nenhum produto adicionado.
                        </div>
                    </b-card>
                </b-card>
            </transition>
        </b-col>
    </b-row>
</template>

<script>
	export default {
		name: "OrderView",
		data() {
			return {
				order: null,
			}

		},
		mounted() {
			this.target = parseInt(this.$route.params.target);
			_axios.get(`order/${this.target}`)
				.then((response) => this.order = response.data);
		},
		methods: {
			back() {
				this.$router.go(-1);
			},
			print() {
				// let divContents = document.getElementById("body").innerHTML;
				// let a = window.open('', '', 'height=500, width=500');
				// a.document.write('<html>');
				// a.document.write('<body>');
				// a.document.write(divContents);
				// a.document.write('</body></html>');
				// a.document.close();
				// a.print();
                let body = document.querySelector("#body");
                window.print();
			},

		}
	}
</script>

<style  media="print">
    @media print {
        .breadcrumb {
            display: none !important;
        }
    }
</style>