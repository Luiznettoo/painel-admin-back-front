<template>
	<div class="app flex-row align-items-center">
		<div class="container">
			<b-row class="justify-content-center">
				<b-col md="6">
					<b-card-group>
						<b-card no-body class="p-3">
							<b-card-body>
								<b-form @submit.prevent="login">
									<h1>Login</h1>
									<p class="text-muted">Entrar na sua conta de Administrador...</p>
									<b-input-group class="mb-3">
										<b-input-group-prepend>
											<b-input-group-text><i class="icon-user"></i></b-input-group-text>
										</b-input-group-prepend>
										<b-form-input type="text"
										              class="form-control"
										              placeholder="Usuário"
										              autocomplete="username email"
										              v-model="user"/>
									</b-input-group>
									<b-input-group class="mb-4">
										<b-input-group-prepend>
											<b-input-group-text><i class="icon-lock"></i></b-input-group-text>
										</b-input-group-prepend>
										<b-form-input type="password"
										              class="form-control"
										              placeholder="Senha"
										              autocomplete="current-password"
										              v-model="password"/>
									</b-input-group>
									<b-row>
										<b-col cols="6">
											<b-button variant="link" class="px-0">Esqueceu sua senha?</b-button>
										</b-col>
										<b-col cols="6" class="text-right">
											<b-button type="submit" variant="primary" class="px-4">Entrar</b-button>
										</b-col>
									</b-row>
								</b-form>
							</b-card-body>
						</b-card>
					</b-card-group>
				</b-col>
			</b-row>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'Login',
		data() {
			return {
				user: '',
				password: '',
			};
		},
		methods: {
			setCookie(name, value, expiresTs) {
				let expires = "";
				if (expiresTs) {
					expires = `; expires=${(new Date(expiresTs * 1000)).toGMTString()}`;
				}
				
				document.cookie = name + "=" + (value || "") + expires + "; path=/";
			},
			login() {
				_axios.post('auth', {
					user: this.user,
					password: this.password,
				}).then((response) => {
					_axios.defaults.headers.Authorization = `Basic ${response.data.api_token}`;
					window.localStorage.setItem('authToken',response.data.api_token);
					this.$router.push({name: 'Dashboard'});
				}).catch((error) => {
					if(error.response.status === 401) {
						$.alert({
							title: 'Alerta!',
							content: 'Senha invalida!',
						});
					}else{
						$.alert({
							title: 'Alerta!',
							content: 'Erro interno!',
						});
					}
				});
			}
		}
	};
</script>
