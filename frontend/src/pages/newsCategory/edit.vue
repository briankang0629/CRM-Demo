<template>
    <section class="content" id="newsCategory-edit">
        <div class="container-fluid">
            <!-- Tool Bar-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="float-sm-right">
                        <router-link :to="{ name: 'newsCategory' }" class="btn bg-danger mr-2 mb-3">
                            <i class="fas fa-reply"></i> {{ $t('common.back') }}
                        </router-link>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <b-overlay :show="isLoading" rounded="sm">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <span class="card-title"> <i class="fas fa-edit"></i> {{ $t('newsCategory.' + method) }}</span>
                            </div>
                            <div class="card-body">
                                <b-tabs content-class="mt-3"  pills card>
                                    <!--基本設定-->
                                    <b-tab :title="$t('newsCategory.basic')" active>
                                        <div class="d-block text-center">
                                            <form class="form-horizontal">
                                                <div class="form-group row text-left">
                                                    <label class="col-sm-2 col-form-label">{{ $t('newsCategory.sortOrder') }} : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="sortOrder" v-model="newsCategory.sort_order"
                                                               :placeholder="$t('newsCategory.sortOrder')" :disabled="controlPermission !== 'E'">
                                                    </div>
                                                </div>
                                                <div class="form-group row text-left">
                                                    <label class="col-sm-2 col-form-label"> {{ $t('newsCategory.status') }} :
                                                        <span id="edit-status">( {{ $t('status.' + newsCategory.status) }} )</span>
                                                    </label>
                                                    <div class="col-sm-10">
                                                        <vue-switch
                                                                v-model="status"
                                                                theme="bootstrap"
                                                                color="success"
                                                                type-bold="true"
                                                        >
                                                        </vue-switch>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </b-tab>
                                    <!--語系設定-->
                                    <b-tab :title="$t('newsCategory.language')">
                                        <div class="d-block text-center">
                                            <form class="form-horizontal">
                                                <div class="text-left mb-3">
                                                    <label class="badge bg-success" >{{ $t('newsCategory.language') }}</label>
                                                </div>
                                                <b-card no-body>
                                                    <b-tabs pills card>
                                                        <b-tab :title="$t('language.' + data.language)" v-for="(data, index) in newsCategory.details" :key="index">
                                                            <div class="card-body">
                                                                <div class="form-group row text-left">
                                                                    <label for="name" class="col-sm-2 col-form-label">{{ $t('newsCategory.name') }} : </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="name" v-model="newsCategory.details[index].name"
                                                                               :placeholder="$t('newsCategory.name')" :disabled="controlPermission !== 'E'">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row text-left">
                                                                    <label class="col-sm-2 col-form-label">{{ $t('newsCategory.description') }} : </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="description" v-model="newsCategory.details[index].description"
                                                                               :placeholder="$t('newsCategory.description')" :disabled="controlPermission !== 'E'">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row text-left">
                                                                    <label for="meta_title" class="col-sm-2 col-form-label">{{ $t('newsCategory.metaTitle') }} : </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="meta_title" v-model="newsCategory.details[index].meta_title"
                                                                               :placeholder="$t('newsCategory.metaTitle')" :disabled="controlPermission !== 'E'">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row text-left">
                                                                    <label for="meta_keyword" class="col-sm-2 col-form-label">{{ $t('newsCategory.metaKeyword') }} : </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="meta_keyword" v-model="newsCategory.details[index].meta_keyword"
                                                                               :placeholder="$t('newsCategory.metaKeyword')" :disabled="controlPermission !== 'E'">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row text-left">
                                                                    <label for="meta_description" class="col-sm-2 col-form-label">{{ $t('newsCategory.metaDescription') }} : </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="meta_description" v-model="newsCategory.details[index].meta_description"
                                                                               :placeholder="$t('newsCategory.metaDescription')" :disabled="controlPermission !== 'E'">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </b-tab>
                                                    </b-tabs>
                                                </b-card>
                                            </form>
                                        </div>
                                    </b-tab>
                                </b-tabs>
                            </div>
                            <div class="card-footer">
                                <div class="text-right">
                                    <router-link :to="{ name: 'newsCategoryList'}" class="btn bg-danger mr-3">
                                        <i class="fas fa-reply"></i> {{ $t('common.back') }}
                                    </router-link>
                                    <b-button type="button" class="btn btn-success" @click="save()" v-if="controlPermission === 'E'">
                                        <i class="fas fa-pen-nib"></i>  {{ $t('common.' + method) }}
                                    </b-button>
                                </div>
                            </div>
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
                newsCategory: {
                    details: [],
                    sortOrder: 0,
                    status: 'N',
                },
                status: false,
                method: '',
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
        watch: {
            status : function (status) {
                this.newsCategory.status = status ? 'Y' : 'N'
            },
        },
        mounted() {
	        //permission
	        this.controlPermission = this.$store.state.permission.news.newsCategory

            //方法
            this.method = !this.$route.params.id ? 'create' : 'edit'

            //語系
            this.language = localStorage.getItem('language') || 'zh-tw'

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
                    ])
                } else {
                    return Promise.all([
                        this.setNewsCategoryDetail()
                    ])
                }
            },

            /**
             * edit 修改指定一筆分類資料
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            edit( newsCategoryId ) {
                this.isLoading = true
                //請求參數
                const request = {
                    method: 'get',
                    url: '/api/news/category/' + newsCategoryId,
                }

                this.$store.dispatch('authRequest', request).then((response) => {
                    this.newsCategory = response
                    this.status = (this.newsCategory.status === 'Y');
                    this.isLoading = false
                }).catch((error) => {
	                this.$root.errorNotify(error)
                    this.isLoading = false
                })
            },

            /**
             * save 儲存送出
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            save() {
                this.isLoading = true
                const request = {
                    method: (this.method === 'create') ? 'post' : 'put',
                    url: '/api/news/category/' + (this.method === 'create' ? '' : '' + this.$route.params.id),
                    data: this.qs.stringify(this.newsCategory)
                }

                this.$store.dispatch('authRequest', request).then((response) => {
                    this.$router.push({ name : 'newsCategory'}).then(() => { this.$root.notify(response) })
                }).catch((error) => {
                    this.isLoading = false
	                this.$root.errorNotify(error)
                })
            },

            /**
             * setNewsCategoryDetail 依照語系組成Detail
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            setNewsCategoryDetail() {
                Object.keys(this.$t('language')).forEach((language) =>{
                    this.newsCategory.details.push({'language' : language})
                })
            }
        }
    }
</script>
