<template>
	<div class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<a><b>後台管理系統</b></a>
			</div>
			<!-- /.login-logo -->
			<div class="card">
				<div class="card-body login-card-body">
					<p class="login-box-msg">管理系統登入</p>

					<form @submit.prevent="login">
						<div class="input-group mb-3">
							<input type="text" class="form-control" :placeholder="$t('website.hostcode')" v-model="user.hostcode">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-sitemap"></span>
								</div>
							</div>
						</div>
						<div class="input-group mb-3">
							<input type="text" class="form-control" :placeholder="$t('user.account')" v-model="user.account">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-envelope"></span>
								</div>
							</div>
						</div>
						<div class="input-group mb-3">
							<input type="password" class="form-control" :placeholder="$t('user.password')" v-model="user.password">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-lock"></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-8">
								<div class="icheck-primary">
									<input type="checkbox" v-model="rememberAccount" @click="remember()">
									<label for="remember">
										記住帳號
									</label>
								</div>
							</div>
							<div class="col-12">
								<button class="btn btn-info btn-block">
                                    <i class="fas fa-sign-in-alt mr-2"></i>登入
                                </button>
							</div>
						</div>

						<div class="social-auth-links text-center mb-3">
							<a href="#" class="btn btn-block btn-danger">
								<i class="fa fa-key mr-2"></i>忘記密碼
							</a>
						</div>
					</form>
				</div>
				<!-- /.login-card-body -->
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				user: {
					account: '',
					password: '',
					hostcode: 'brian-stage',
				},
				rememberAccount: false
			}
		},
		mounted() {
			//驗證當前登入狀況
			this.$store.dispatch('checkAuth').then(() => {
				this.$router.push('/')
			}).catch(() => {
				localStorage.removeItem('auth');
				// //取前一頁網址 判訂第三方登入失敗
				if (this.$router.history._startLocation.substring(0, 12) === '/auth/google') {
                    this.$notify({
                        type:'error',
                        title: this.$t('common.failed'),
                        text: 'Google 登入失敗',
                    })
				} else if (this.$router.history._startLocation.substring(0, 13) === '/auth/facebook') {
                    this.$notify({
                        type:'error',
                        title: this.$t('common.failed'),
                        text: 'Facebook 登入失敗',
                    })
				}
			})

			//記憶帳號
			if (localStorage.getItem('account')) {
				this.rememberAccount = true
				this.user.account = localStorage.getItem('account')
			}
		},
		methods: {

			/**
			 * remember 記憶帳號
			 *
			 * @since 0.0.1
			 * @version 0.0.1
			 */
			remember() {
				this.rememberAccount = !this.rememberAccount
			},

			/**
			 * login 登入
			 *
			 * @since 0.0.1
			 * @version 0.0.1
			 */
			login() {
				const request = {
					method:  'post',
					url: '/api/login',
					data: this.qs.stringify(this.user),
					headers: {
						'hostcode': this.user.hostcode,
						'X-FORWARDED-HOST': process.env.VUE_APP_ADMIN_DOMAIN
					}
				}

				//請求
				this.$store.dispatch('request', request).then((response) => {
					if (response.token) {
						//set localStorage
						localStorage.setItem('auth', JSON.stringify({
							token: response.token,
							account: this.user.account,
							name: this.user.name
						}))
						//預設繁體中文
						localStorage.setItem('language', 'zh-tw')

						//若要記住帳號
						if (this.rememberAccount) {
							localStorage.setItem('account', this.user.account)
						} else {
							localStorage.removeItem('account')
						}

                        localStorage.setItem('hostcode', this.user.hostcode);

						//轉跳首頁
						this.$router.push('/').then(() => { this.$root.notify({
							message: this.$t('message.loginSuccess')
						}) })

					}
				}).catch(error => {
					this.$root.errorNotify(error)

					this.account = '';
					this.password = '';
				})

			},

			/**
			 * loginGoogle Google登入
			 *
			 * @since 0.0.1
			 * @version 0.0.1
			 */
			loginGoogle() {
				const request = {
					method:  'post',
					url: '/api/login/google',
				}

				//請求
				this.$store.dispatch('request', request).then((response) => {
					if (response.status === 'success') {
						//預設繁體中文
						localStorage.setItem('language', 'zh-tw')
						location.href = response.url
					}
				}).catch((error) => {
					this.$root.errorNotify(error)
				})
			},
		}
	}
</script>
<style>
	.login-page {
		background-color: #343a40;
	}
	.login-card-body {
		border-radius: 5px;
		background-color: #ececec;
	}

	.login-logo a b {
		color: white;
	}
</style>
