<template>
    <section class="content" id="user-edit">
        <div class="container-fluid">
            <!-- Tool Bar-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="float-sm-right" v-if="controlPermission === 'E'">
                        <router-link :to="{ name: 'userGroup' }" class="btn bg-danger mr-2 mb-3">
                            <i class="fas fa-reply"></i> {{ $t('common.back') }}
                        </router-link>
                    </div>
                </div>
            </div>

            <b-overlay :show="isLoading" rounded="sm">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"> <i class="fas fa-edit"></i> {{ $t('userGroup.' + method) }}</h3>
                            </div>
                            <form class="form-horizontal">
                                <div class="card-body">
                                    <div class="form-group row text-left">
                                        <label for="name" class="col-sm-3 col-form-label">{{ $t('userGroup.name') }} : </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="name" v-model="userGroup.name"
                                                   :placeholder="$t('userGroup.name')" :disabled="controlPermission !== 'E'">
                                        </div>
                                    </div>
                                    <div class="form-group row text-left">
                                        <label for="description" class="col-sm-3 col-form-label">{{ $t('userGroup.description') }} : </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="description" v-model="userGroup.description"
                                                   :placeholder="$t('userGroup.description')" :disabled="controlPermission !== 'E'">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-right">
                                        <router-link :to="{ name: 'userGroup'}" class="btn bg-danger mr-3">
                                            <i class="fas fa-reply"></i> {{ $t('common.back') }}
                                        </router-link>
                                        <b-button type="button" class="btn btn-success" @click="save()" v-if="controlPermission === 'E'">
                                            <i class="fas fa-pen-nib"></i>  {{ $t('common.' + method) }}
                                        </b-button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </b-overlay>
        </div>
    </section>
</template>

<script>
    export default {
        layout: 'admin',
        data() {
            return {
                userList: {},
                user: {
                    status: null,
                    user_group_id: 0,
                    password: '',
                },
                userGroup: [],
                method: '',
                //請求參數
                sentData: {
                    account: '',
                    name: '',
                    status: null,
                    user_group_id: 0,
                    email: ''
                },
                selected: [],
                isLoading: false,
                controlPermission: ''
            }
        },
        computed: {},
        mounted() {
            // permission
            this.controlPermission = this.$store.state.permission.user.userGroup

            // 方法
            this.method = !this.$route.params.id ? 'create' : 'edit'

            // init
            this.init();
        },
        methods: {

            /**
             * init 初始化
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            init() {
                if(this.method === 'edit') {
                    return Promise.all([
                        this.edit(),
                    ])
                }
            },

            /**
             * getUserGroup 取使用者群組
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            getUserGroup() {
                const request = {
                    method: 'get',
                    url: '/api/user/group',
                }

                this.$store.dispatch('authRequest', request).then((response) => {
                    this.userGroup = response;
                }).catch((error) => {
	                this.$root.errorNotify(error)
                })
            },

            /**
             * edit 修改使用者
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            edit() {
                this.isLoading = true
                //傳送參數
                const request = {
                    method: 'get',
                    url: '/api/user/group/' + this.$route.params.id,
                }

                this.$store.dispatch('authRequest', request).then((response) => {
                    this.userGroup = response;
                    this.isLoading = false
                }).catch((error) => {
	                this.$root.errorNotify(error)
                    this.isLoading = false
                })
            },

            /**
             * save 儲存資訊
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            save() {
                let params = this.userGroup;

                const request = {
                    method: (this.method === 'create') ? 'post' : 'put',
                    url: '/api/user/group' + (this.method === 'create' ? '' : '/' + this.$route.params.id),
                    data: this.qs.stringify(params)
                }

                this.$store.dispatch('authRequest', request).then((response) => {
                    this.$router.push({ name : 'userGroup'}).then(() => { this.$root.notify(response) })
                }).catch((error) => {
                    this.$root.errorNotify(error)
                })
            },
        }
    }
</script>
