<template>
    <section class="content" id="permission-list">
        <div class="container-fluid">
            <!-- Tool Bar-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="float-sm-right" v-if="controlPermission === 'E'">
                        <router-link :to="{ name : 'newsCreate' }" class="btn bg-success mr-2 mb-3" @click="create()">
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
                                <label class="mr-sm-2 ml-2">{{ $t('news.name') }}：</label>
                                <b-form-input class="mr-sm-2" v-model="sentData.name" :placeholder="$t('news.name')"></b-form-input>
                            </b-col>

                            <!-- 狀態 -->
                            <b-col lg="2" md="6" sm="12" class="mt-3">
                                <label class="mr-sm-2 ml-2">{{ $t('news.status') }}：</label>
                                <b-form-select v-model="sentData.status">
                                    <option v-for="(item, index) in $t('status')" :value="index" :key="index">
                                        {{item}}
                                    </option>
                                    <template #first>
                                        <b-form-select-option :value="null">-- {{ $t('common.choose') }} --
                                        </b-form-select-option>
                                    </template>
                                </b-form-select>
                            </b-col>

                            <!-- 搜尋 -->
                            <b-col lg="2" md="6" sm="12" class="mt-3">
                                <label class="mr-sm-2 ml-2">{{ $t('news.operate') }} ：</label>
                                <div class="col-sm-12">
                                    <b-button variant="outline-success" class="my-2 my-sm-0 ml-2" @click="getNews()"><i class="fas fa-search"></i> {{ $t('message.search') }}</b-button>
                                </div>
                            </b-col>
                        </b-row>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-striped text-nowrap table-bordered">
                            <thead>
                            <tr>
                                <th v-if="controlPermission === 'E'">{{ $t('common.select') }}</th>
                                <th>{{ $t('news.newsId') }}</th>
                                <th>{{ $t('news.name') }}</th>
                                <th>{{ $t('news.createdAt') }}</th>
                                <th>{{ $t('news.status') }}</th>
                                <th>{{ $t('news.operate') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(data, index) in newsList" :key="index">
                                <td v-if="controlPermission === 'E'"><input type="checkbox" :value="data.newsId" v-model="selected" /></td>
                                <td>{{data.news_id}}</td>
                                <td>{{data.name}}</td>
                                <td>{{data.created_at}}</td>
                                <td>
                                    <span class="badge " :class="classStatus(data.status)">{{$t('status.' + data.status)}}</span>
                                </td>
                                <td>
                                    <router-link :to="{ name: 'newsEdit', params: { id: data.news_id }}" class="btn btn-primary">
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
                        <div v-if="!isLoading && !newsList.length" class="text-notfound text-center">
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
                                    @input="getNews()"
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
                newsList: {},
                status: {
                    'E': this.$t('newsStatus.E'),
                    'V': this.$t('newsStatus.V'),
                    'N': this.$t('newsStatus.N')
                },
                method: '',
                classStatus : (status) => [
                    {'bg-success': status === 'Y'},
                    {'bg-danger': status === 'N'},
                    {'bg-warning': status === 'U'}
                ],
                selected: [],
                //請求參數
                sentData:{
                    name:'',
                    status:null,
                },
                isLoading: false,
                controlPermission: ''
            }
        },
        computed: {
        },
        mounted() {
	        //permission
	        this.controlPermission = this.$store.state.permission.news.newsList

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
                    this.getNews(),
                ])
            },

            /**
             * getNews 取最新消息
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            getNews() {
                this.isLoading = true
                const request = {
                    method: 'get',
                    url: '/api/news',
                    params: {
                        page: this.pagination.current_page,
                        per_page: this.pagination.per_page
                    }
                }

                if(this.sentData.name !== '') request.params.name = this.sentData.name
                if(this.sentData.status !== '') request.params.status = this.sentData.status

                this.$store.dispatch('authRequest', request).then((response) => {
                    this.newsList = response.data
                    this.pagination = response.meta.pagination
                    this.isLoading = false
                }).catch((error) => {
	                this.$root.errorNotify(error)
                })
            },

            /**
             * remove 刪除指定一筆權限資料
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
                        let newsId = this.selected.join(',')
                        //請求刪除
                        const request = {
                            method: 'delete',
                            url: '/api/news/' + newsId,
                        }

                        //請求刪除前驗證
                        this.$store.dispatch('authRequest', request).then((response) => {
	                        this.$root.notify(response)
                            this.getNews()
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
