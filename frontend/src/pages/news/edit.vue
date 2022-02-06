<template>
    <section class="content" id="news-edit">
        <div class="container-fluid">
            <!-- Tool Bar-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="float-sm-right" v-if="controlPermission === 'E'">
                        <router-link :to="{ name: 'newsList' }" class="btn bg-danger mr-2 mb-3">
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
                                <h3 class="card-title"> <i class="fas fa-edit"></i>{{ $t('news.' + method) }}</h3>
                            </div>
                            <form class="form-horizontal mt-3">
                                <div class="card-body">
                                    <b-tabs content-class="mt-3"  pills card>
                                        <!--基本設定-->
                                        <b-tab :title="$t('news.basic')" active>
                                            <div class="d-block text-center">
                                                <form class="form-horizontal">
                                                    <div class="form-group row text-left">
                                                        <label class="col-sm-2 col-form-label"> {{ $t('news.category') }} :</label>
                                                        <div class="col-sm-10">
                                                            <vue-multi-select
                                                                    v-model="news.category"
                                                                    :placeholder="$t('news.selectMsg')"
                                                                    :selectLabel="$t('common.select')"
                                                                    :deselectLabel="$t('common.remove')"
                                                                    :options="newsCategory.map(item => item.newsCategoryId)"
                                                                    :custom-label="value => newsCategory.find(item => item.newsCategoryId === value).name"
                                                                    :multiple="true"
                                                                    :disabled="controlPermission !== 'E'"
                                                            ></vue-multi-select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row text-left">
                                                        <label class="col-sm-2 col-form-label">{{ $t('news.sortOrder') }} : </label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="sortOrder" v-model="news.sortOrder"
                                                                   :placeholder="$t('news.sortOrder')" :disabled="controlPermission !== 'E'">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row text-left">
                                                        <label class="col-sm-2 col-form-label"> {{ $t('news.status') }} :
                                                            <span id="edit-status">( {{ $t('status.' + news.status) }} )</span>
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
                                                    <div class="form-group row text-left">
                                                        <label class="col-sm-2 col-form-label"> {{ $t('news.top') }} :
                                                            <span id="edit-top">( {{ $t('status.' + news.top) }} )</span>
                                                        </label>
                                                        <div class="col-sm-10">
                                                            <vue-switch
                                                                    v-model="top"
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
                                        <b-tab :title="$t('news.language')">
                                            <div class="d-block text-center">
                                                <form class="form-horizontal">
                                                    <div class="text-left mb-3">
                                                        <label class="badge bg-success" >{{ $t('news.language') }}</label>
                                                    </div>
                                                    <b-card no-body>
                                                        <b-tabs pills card>
                                                            <b-tab :title="$t('language.' + data.language)" v-for="(data, index) in news.details" :key="index">
                                                                <div class="card-body">
                                                                    <div class="form-group row text-left">
                                                                        <label for="name" class="col-sm-2 col-form-label">{{ $t('news.name') }} : </label>
                                                                        <div class="col-sm-10">
                                                                            <input type="text" class="form-control" id="name" v-model="news.details[index].name"
                                                                                   :placeholder="$t('news.name')" :disabled="controlPermission !== 'E'">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row text-left">
                                                                        <label class="col-sm-2 col-form-label">{{ $t('news.description') }} : </label>
                                                                        <div class="col-sm-10">
                                                                            <ckeditor :editor="ckeditor.editor" v-model="news.details[index].description" :config="ckeditor.config"></ckeditor>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row text-left">
                                                                        <label for="intro" class="col-sm-2 col-form-label">{{ $t('news.intro') }} : </label>
                                                                        <div class="col-sm-10">
                                                                            <input type="text" class="form-control" id="intro" v-model="news.details[index].intro"
                                                                                   :placeholder="$t('news.intro')" :disabled="controlPermission !== 'E'">
                                                                        </div>
                                                                    </div>

                                                                    <!-- SEO -->
                                                                    <div class="text-left mb-3">
                                                                        <label class="badge bg-success" >{{ $t('product.seo') }}</label>
                                                                    </div>

                                                                    <div class="form-group row text-left">
                                                                        <label for="meta_title" class="col-sm-2 col-form-label">{{ $t('news.metaTitle') }} : </label>
                                                                        <div class="col-sm-10">
                                                                            <input type="text" class="form-control" id="meta_title" v-model="news.details[index].meta_title"
                                                                                   :placeholder="$t('news.metaTitle')" :disabled="controlPermission !== 'E'">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row text-left">
                                                                        <label for="meta_keyword" class="col-sm-2 col-form-label">{{ $t('news.metaKeyword') }} : </label>
                                                                        <div class="col-sm-10">
                                                                            <input type="text" class="form-control" id="meta_keyword" v-model="news.details[index].meta_keyword"
                                                                                   :placeholder="$t('news.metaKeyword')" :disabled="controlPermission !== 'E'">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row text-left">
                                                                        <label for="meta_description" class="col-sm-2 col-form-label">{{ $t('news.metaDescription') }} : </label>
                                                                        <div class="col-sm-10">
                                                                            <input type="text" class="form-control" id="meta_description" v-model="news.details[index].meta_description"
                                                                                   :placeholder="$t('news.metaDescription')" :disabled="controlPermission !== 'E'">
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
                                        <router-link :to="{ name: 'newsList' }" class="btn bg-danger mr-3">
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
    import ckeditor from '@/plugins/ckeditor'
    export default {
        layout: 'admin',
        data() {
            return {
                ckeditor,
                news: {
                    name: '',
                    detail: [],
                    category: [],
                    status: 'N',
                    top: 'N',
                },
                newsCategory: [],
                method: '',
                status: false,
                top: false,
                selected: [],
                //請求參數
                sentData:{
                    name:'',
                    status:null,
                    top:null,
                },
                isLoading: false,
                controlPermission: ''
            }
        },
        watch: {
            status : function (status) {
                this.news.status = status ? 'Y' : 'N'
            },
            top : function (top) {
                this.news.top = top ? 'Y' : 'N'
            },
        },
        mounted() {
	        //permission
	        this.controlPermission = this.$store.state.permission.news.newsList

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
                        this.getNewsCategory(),
                    ])
                } else {
                    return Promise.all([
                        this.setNewsDetail(),
                        this.getNewsCategory()
                    ])
                }
            },

            /**
             * edit 修改指定一筆權限資料
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            edit( newsId ) {
                this.isLoading = true
                //請求參數
                const request = {
                    method: 'get',
                    url: '/api/news/' + newsId,
                }

                this.$store.dispatch('authRequest', request).then((response) => {
                    this.news = response
                    this.status = (this.news.status === 'Y');
                    this.top = (this.news.top === 'Y');
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
                const request = {
                    method: (this.method === 'create') ? 'post' : 'put',
                    url: '/api/news/' + (this.method === 'create' ? '' : '' + this.$route.params.id),
                    data: this.qs.stringify(this.news)
                }

                this.$store.dispatch('authRequest', request).then((response) => {
                    this.$router.push({ name : 'newsList'}).then(() => { this.$root.notify(response) })
                }).catch((error) => {
	                this.$root.errorNotify(error)
                })
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
                }

                this.$store.dispatch('authRequest', request).then((response) => {
                    this.newsCategory = response.data.map((item) => {
                        return {'newsCategoryId': item.news_category_id , 'name': item.name}
                    })

                    this.isLoading = false
                }).catch((error) => {
                    this.$root.errorNotify(error)
                })
            },

            /**
             * setNewsDetail 依照語系組成Detail
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            setNewsDetail() {
                Object.keys(this.$t('language')).forEach((language) =>{
                    this.news.details.push({'language' : language})
                })
            },
        }
    }
</script>
