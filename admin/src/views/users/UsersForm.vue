<template>
	<div class="animated fadeIn">
		<b-row>
			<b-col md="12">
				<b-card>
					<div slot="header">
						<strong>Usuário </strong>
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
					<form class="form">
						<div class="form-group row">
							<label for="name" class="col-sm-2 col-form-label">Nome:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="name" v-model="user.name">
							</div>
						</div>
						<div class="form-group row">
							<label for="user" class="col-sm-2 col-form-label">Usuário:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="user" v-model="user.user">
							</div>
						</div>
						<div class="form-group row">
							<label for="email" class="col-sm-2 col-form-label">Email:</label>
							<div class="col-sm-10">
								<input type="email" class="form-control" id="email" v-model="user.email">
							</div>
						</div>
						<div class="form-group row">
							<label for="password" class="col-sm-2 col-form-label">Senha:</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" id="password" v-model="user.password">
							</div>
						</div>
						<div class="form-group row">
							<label for="password-confirm" class="col-sm-2 col-form-label">Confirmar Senha:</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" id="password-confirm"
								       v-model="user.password_confirmation">
							</div>
						</div>
					</form>
				</b-card>
			</b-col>
		</b-row>
	</div>
</template>

<script>
	export default {
		name: 'forms',
		data() {
			return {
				selected: [], // Must be an array reference!
				show: true,
				target: null,
				user: {
					name: '',
					user: '',
					email: '',
					password: '',
					password_confirmation: '',
				}
			};
		},
		mounted() {
			this.target = parseInt(this.$route.params.target);
			if (this.target) {
				_axios.get(`users/${this.target}`)
				      .then((response) => {
					      this.user = response.data;
				      });
			}
		},
		methods: {
			back() {
				this.$router.go(-1);
			},
			save() {
				if (!this.target) {
					_axios.post(`users`, this.user)
					      .then(() => {
						      this.$router.go(-1);
					      });
				}
				else {
					_axios.put(`users/${this.target}`, JSON.stringify(this.user))
					      .then(() => {
						      this.$router.go(-1);
					      });
				}
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
