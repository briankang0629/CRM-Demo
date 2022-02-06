<template>
    <section class="content" id="user-group">
        <div class="container-fluid">
            <!-- Tool Bar-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="float-sm-right" v-if="controlPermission === 'E'">
                        <router-link :to="{ name : 'userGroupCreate' }" class="btn bg-success mr-2 mb-3">
                            <i class="fas fa-pen-nib"></i> {{ $t('common.create') }}
                        </router-link>
                        <a class="btn bg-danger mr-2 mb-3" @click="remove()" v-if="selected.length">
                            <i class="fas fa-trash"></i> {{ $t('common.delete') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <b-overlay :show="isLoading" rounded="sm">
                <div class="card card-table">
                    <div class="card-header">
                        <label class="badge bg-success">{{ $t('common.searchBar') }}</label>
                        <b-row class="justify-content-md-left">

                            <!-- 名稱 -->
                            <b-col lg="2" md="6" sm="12" class="mt-3">
                                <label class="mr-sm-2 ml-2">{{ $t('userGroup.name') }}：</label>
                                <b-form-input class="mr-sm-2" v-model="sentData.name"
                                              :placeholder="$t('userGroup.name')"></b-form-input>
                            </b-col>

                            <!-- 搜尋 -->
                            <b-col lg="2" md="6" sm="12" class="mt-3">
                                <label class="mr-sm-2 ml-2">{{ $t('user.operate') }} ：</label>
                                <div class="col-sm-12">
                                    <b-button variant="outline-success" class="my-2 my-sm-0 ml-2" @click="getUser()"><i class="fas fa-search"></i> {{ $t('message.search') }}
                                    </b-button>
                                </div>
                            </b-col>
                        </b-row>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-striped text-nowrap table-bordered">
                            <thead>
                            <tr>
                                <th v-if="controlPermission === 'E'">{{ $t('common.select') }}</th>
                                <th>{{ $t('userGroup.userGroupId') }}</th>
                                <th>{{ $t('userGroup.name') }}</th>
                                <th>{{ $t('userGroup.count') }}</th>
                                <th>{{ $t('userGroup.operate') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(data, index) in userGroups" :key="index">
                                <td v-if="controlPermission === 'E'"><input type="checkbox" :value="data.user_group_id"
                                                                            v-model="selected"/></td>
                                <td>{{data.user_group_id}}</td>
                                <td>{{data.name}}</td>
                                <td>{{data.count}}</td>
                                <td>{{data.created_at}}</td>
                                <td>
                                    <router-link :to="{ name: 'userGroupEdit', params: { id: data.user_group_id }}" class="btn btn-primary">
                                        <span v-if="controlPermission === 'E'">
                                            <i class="fas fa-pencil-alt"></i> {{ $t('common.edit') }}
                                        </span>
                                        <span v-if="controlPermission === 'V'">
                                            <i class="fas fa-eye"></i> {{ $t('common.view') }}
                                        </span>
                                    </router-link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div v-if="!isLoading && !userGroups.length" class="text-notfound text-center">
                        <span class="text-danger">
                            {{ $t('common.notFound') }}
                        </span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <ul class="float-right">
                            <b-pagination
                                v-model="pagination.current_page"
                                :total-rows="pagination.total"
                                :per-page="pagination.per_page"
                                aria-controls="my-table"
                                @input="getUserGroup()"
                            ></b-pagination>
                        </ul>
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
            pagination: {
                current_page: 1,
                per_page: 10,
                total: 0,
                total_pages: 0,
            },
            userGroups: {},
            userGroup: {
                name : null,
            },
            method: '',
            classStatus: (status) => [
                {'bg-success': status === 'Y'},
                {'bg-danger': status === 'N'},
                {'bg-warning': status === 'U'}
            ],
            //請求參數
            sentData: {
                name: ''
            },
            selected: [],
            isLoading: false,
            controlPermission: ''
        }
    },
    computed: {},
    mounted() {
        //permission
        this.controlPermission = this.$store.state.permission.user.userGroup

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
            return Promise.all([
                this.getUserGroup()
            ])
        },

        /**
         * getUserGroup 取使用者群組
         *
         * @since 0.0.1
         * @version 0.0.1
         * @return void
         */
        getUserGroup() {
            this.isLoading = true;
            const request = {
                method: 'get',
                url: '/api/user/group',
                params: {
                    page: this.pagination.current_page,
                    per_page: this.pagination.per_page
                }
            }

            if(this.sentData.name !== '') request.params.name = this.sentData.name

            this.$store.dispatch('authRequest', request).then((response) => {
                this.userGroups = response.data;
                this.pagination = response.meta.pagination
                this.isLoading = false;
            }).catch((error) => {
                this.$root.errorNotify(error)
                this.isLoading = false;
            })
        },

        /**
         * remove 刪除
         *
         * @since 0.0.1
         * @version 0.0.1
         */
        remove() {
            let askSetting = {
                title: this.$t('message.askDelete'),
                text: this.$t('message.askDeleteMessage'),
                confirmText: this.$t('common.confirm'),
                cancelText: this.$t('common.cancel'),
            }

            //詢問刪除
            this.$Swal.ask(askSetting).then((result) => {
                if (result.isConfirmed) {
                    this.isLoading = true
                    const userGroupId = this.selected.join(',')
                    //請求刪除
                    const request = {
                        method: 'delete',
                        url: '/api/user/group/' + userGroupId,
                    }

                    //請求刪除前驗證
                    this.$store.dispatch('authRequest', request).then((response) => {
                        this.$root.notify(response)
                        this.getUserGroup()
                    }).catch((error) => {
                        this.$root.errorNotify(error)
                        this.isLoading = false
                    })
                }
                //請空選擇
                this.selected = []
            })
        }
    }
}
</script>
