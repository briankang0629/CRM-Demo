<template>
    <section class="content" id="user-edit">
        <div class="container-fluid">
            <!-- Tool Bar-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="float-sm-right" v-if="controlPermission === 'E'">
                        <router-link :to="{ name: 'userList' }" class="btn bg-danger mr-2 mb-3">
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
                                <h3 class="card-title"> <i class="fas fa-edit"></i>{{ $t('user.' + method) }}</h3>
                            </div>
                            <form class="form-horizontal">
                                <div class="card-body">
                                    <div class="form-group row text-left">
                                        <label for="Account" class="col-sm-3 col-form-label">{{ $t('user.account') }} : </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="Account" v-model="user.account"
                                                   :placeholder="$t('user.account')" :disabled="method !== 'create'">
                                        </div>
                                    </div>
                                    <div class="form-group row text-left">
                                        <label class="col-sm-3 col-form-label"> {{ $t('user.userGroup') }} : </label>
                                        <div class="col-sm-9">
                                            <b-form-select v-model="user.user_group_id" :disabled="controlPermission !== 'E'">
                                                <option v-for="(item, index) in userGroup" :value="item.user_group_id" :key="index">
                                                    {{item.name}}
                                                </option>
                                                <template #first>
                                                    <b-form-select-option :value="0" disabled>-- {{ $t('common.choose') }} --
                                                    </b-form-select-option>
                                                </template>
                                            </b-form-select>
                                        </div>
                                    </div>
                                    <div class="form-group row text-left">
                                        <label for="name" class="col-sm-3 col-form-label">{{ $t('user.name') }} : </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="name" v-model="user.name"
                                                   :placeholder="$t('user.name')" :disabled="controlPermission !== 'E'">
                                        </div>
                                    </div>
                                    <div class="form-group row text-left">
                                        <label for="password" class="col-sm-3 col-form-label">{{ $t('user.password') }} : </label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="password" v-model="user.password"
                                                   :placeholder="$t('user.password')" :disabled="controlPermission !== 'E'">
                                        </div>
                                    </div>
                                    <div class="form-group row text-left">
                                        <label for="password" class="col-sm-3 col-form-label">{{ $t('user.confirm') }} : </label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="confirm"
                                                   v-model="user.confirm" :placeholder="$t('user.confirm')" :disabled="controlPermission !== 'E'">
                                        </div>
                                    </div>
                                    <div class="form-group row text-left">
                                        <label for="email" class="col-sm-3 col-form-label">{{ $t('user.email') }} : </label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" id="email" v-model="user.email"
                                                   :placeholder="$t('user.email')" :disabled="method !== 'create'">
                                        </div>
                                    </div>
                                    <div class="form-group row text-left">
                                        <label for="mobile" class="col-sm-3 col-form-label">{{ $t('user.mobile') }} : </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="mobile" v-model="user.mobile"
                                                   :placeholder="$t('user.mobile')" :disabled="controlPermission !== 'E'">
                                        </div>
                                    </div>
                                    <div class="form-group row text-left">
                                        <label class="col-sm-3 col-form-label"> {{ $t('user.status') }} : </label>
                                        <div class="col-sm-9">
                                            <b-form-select v-model="user.status" :options="$t('status')" :disabled="controlPermission !== 'E'">
                                                <template #first>
                                                    <b-form-select-option :value="null" disabled>-- {{ $t('common.choose') }} --
                                                    </b-form-select-option>
                                                </template>
                                            </b-form-select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-right">
                                        <router-link :to="{ name: 'userList'}" class="btn bg-danger mr-3">
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
            //permission
            this.controlPermission = this.$store.state.permission.user.userList

            //方法
            this.method = !this.$route.params.id ? 'create' : 'edit'

            //init
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
                        this.edit(this.$route.params.id),
                        this.getUserGroup()
                    ])
                } else {
                    return Promise.all([
                        this.getUserGroup()
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
                    this.userGroup = response.data;
                }).catch((error) => {
	                this.$root.errorNotify(error)
                })
            },

            /**
             * edit 修改使用者
             *
             * @param userId
             * @since 0.0.1
             * @version 0.0.1
             */
            edit(userId) {
                this.isLoading = true
                //傳送參數
                const request = {
                    method: 'get',
                    url: '/api/user/' + userId,
                }

                this.$store.dispatch('authRequest', request).then((response) => {
                    this.user = response;
                    this.user.password = '';

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
                let params = this.user;

                // 沒有更改密碼則移除
                if (!params.password) {
                    delete params.password;
                }

                const request = {
                    method: (this.method === 'create') ? 'post' : 'put',
                    url: '/api/user' + (this.method === 'create' ? '' : '/' + this.user.user_id),
                    data: this.qs.stringify(params)
                }

                this.$store.dispatch('authRequest', request).then((response) => {
                    this.$router.push({ name : 'userList'}).then(() => { this.$root.notify(response) })
                }).catch((error) => {
                    this.$root.errorNotify(error)
                })
            },
        }
    }
</script>
