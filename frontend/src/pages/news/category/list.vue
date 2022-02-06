<template>
    <section class="content" id="permission-list">
        <div class="container-fluid">
            <!-- Tool Bar-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="float-sm-right" v-if="controlPermission === 'E'">
                        <router-link :to="{ name : 'newsCategoryCreate' }" class="btn bg-success mr-2 mb-3" @click="create()">
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
                                <label class="mr-sm-2 ml-2">{{ $t('newsCategory.name') }}：</label>
                                <b-form-input class="mr-sm-2" v-model="sentData.name" :placeholder="$t('newsCategory.name')"></b-form-input>
                            </b-col>

                            <!-- 狀態 -->
                            <b-col lg="2" md="6" sm="12" class="mt-3">
                                <label class="mr-sm-2 ml-2">{{ $t('newsCategory.status') }}：</label>
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

                            <!-- 語系 -->
                            <b-col lg="2" md="6" sm="12" class="mt-3">
                                <label class="mr-sm-2 ml-2">{{ $t('newsCategory.language') }}：</label>
                                <b-form-select v-model="sentData.language">
                                    <option v-for="(item, index) in $t('language')" :value="index" :key="index">
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
                                <label class="mr-sm-2 ml-2">{{ $t('newsCategory.operate') }} ：</label>
                                <div class="col-sm-12">
                                    <b-button variant="outline-success" class="my-2 my-sm-0 ml-2" @click="getNewsCategory()"><i class="fas fa-search"></i> {{ $t('message.search') }}</b-button>
                                </div>
                            </b-col>
                        </b-row>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-striped text-nowrap table-bordered">
                            <thead>
                            <tr>
                                <th v-if="controlPermission === 'E'">{{ $t('common.select') }}</th>
                                <th>{{ $t('newsCategory.newsCategoryId') }}</th>
                                <th>{{ $t('newsCategory.name') }}</th>
                                <th>{{ $t('newsCategory.count') }}</th>
                                <th>{{ $t('newsCategory.sortOrder') }}</th>
                                <th>{{ $t('newsCategory.createdAt') }}</th>
                                <th>{{ $t('newsCategory.status') }}</th>
                                <th>{{ $t('newsCategory.operate') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(data, index) in newsCategoryList" :key="index">
                                <td v-if="controlPermission === 'E'"><input type="checkbox" :value="data.news_category_id" v-model="selected" /></td>
                                <td>{{data.news_category_id}}</td>
                                <td>{{data.name}}</td>
                                <td>{{data.count}}</td>
                                <td>{{data.sort_order}}</td>
                                <td>{{data.created_at}}</td>
                                <td>
                                    <span class="badge " :class="classStatus(data.status)">{{$t('status.' + data.status)}}</span>
                                </td>
                                <td>
                                    <router-link :to="{ name: 'newsCategoryEdit', params: { id: data.news_category_id }}" class="btn btn-primary">
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
                        <div v-if="!isLoading && !newsCategoryList.length" class="text-notfound text-center">
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
                                    @input="getNewsCategory()"
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
                newsCategoryList: {},
                method: '',
                classStatus : (status) => [
                    {'bg-success': status === 'Y'},
                    {'bg-danger': status === 'N'},
                ],
                selected: [],
                //請求參數
                sentData:{
                    name:'',
                    status:null,
                    language:null,
                },
                isLoading: false,
                controlPermission: ''
            }
        },
        computed: {
        },
        mounted() {
	        //permission
	        this.controlPermission = this.$store.state.permission.news.newsCategory

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
                    this.getNewsCategory(),
                ])
            },

            /**
             * getNewsCategory 取消息分類列表
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            getNewsCategory() {
                this.isLoading = true
                const request = {
                    method: 'get',
                    url: '/api/news/category',
                    params: {
                        page: this.pagination.current_page,
                        per_page: this.pagination.per_page
                    }
                }

                if(this.sentData.name !== '') request.params.name = this.sentData.name
                if(this.sentData.language !== '') request.params.language = this.sentData.language
                if(this.sentData.status !== '') request.params.status = this.sentData.status

                this.$store.dispatch('authRequest', request).then((response) => {
                    this.newsCategoryList = response.data
                    this.pagination = response.meta.pagination
                }).catch((error) => {
	                this.$root.errorNotify(error)
                }).finally(() => {
                    this.isLoading = false
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
                        let newsCategoryId = this.selected.join(',')
                        //請求刪除
                        const request = {
                            method: 'delete',
                            url: '/api/news/category/' + newsCategoryId,
                        }

                        //請求刪除前驗證
                        this.$store.dispatch('authRequest', request).then((response) => {
	                        this.$root.notify(response)
                            this.getNewsCategory()
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
