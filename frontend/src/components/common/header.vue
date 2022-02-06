<template>
    <div>
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <!--<li class="nav-item d-none d-sm-inline-block">-->
                <!--<a href="index3.html" class="nav-link">Home</a>-->
                <!--</li>-->
                <!--<li class="nav-item d-none d-sm-inline-block">-->
                <!--<a href="#" class="nav-link">Contact</a>-->
                <!--</li>-->
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" @click="redirect()" data-slide="true" href="#"
                       role="button" v-b-tooltip.hover title="前往網站">
                        <i class="fas fa-globe"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"
                       role="button" v-b-tooltip.hover title="個人設定">
                        <i class="fa fa-user-circle"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" @click="logout()" data-slide="true" href="#"
                       role="button" v-b-tooltip.hover title="登出">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
    </div>
</template>

<script>
    export default {
        data() {
            return {}
        },
        mounted() {
            //解決ipad寬度 768 選單出現問題
            if(screen.width < 992) {
                let el = document.querySelector('body');
                el.setAttribute('class','sidebar-closed sidebar-collapse');
            }
        },
        methods: {

            /**
             * logout 登出
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            logout() {
                //請求參數設定
                const request = {
                    method: 'post',
                    url: '/api/logout',
                }

                //請求
                this.$store.dispatch('authRequest', request).then((response) => {
                    //unset localStorage
                    localStorage.removeItem('auth');

                    //轉跳登入頁
                    this.$router.push('/login').then(() => { this.$root.notify(response) })
                }).catch((error) => {
                    this.$root.errorNotify(error)
                })
            },

            /**
             * redirect 重新導向指定位置
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            redirect () {
                window.location = process.env.VUE_APP_WEBSITE_URL;
            },
        }

    }
</script>
