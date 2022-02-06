<template>
    <section class="content" id="product-category-edit">
        <div class="container-fluid">
            <!-- Tool Bar-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="float-sm-right" v-if="controlPermission === 'E'">
                        <router-link :to="{ name: 'productCategory' }" class="btn bg-danger mr-2 mb-3">
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
                                <span class="card-title"> <i class="fas fa-edit"></i> {{ $t('productCategory.' + method) }}</span>
                            </div>
                            <div class="card-body">
                                <b-tabs content-class="mt-3" pills card>
                                    <!--基本設定-->
                                    <b-tab :title="$t('productCategory.basic')" active>
                                        <div class="d-block text-center">
                                            <form class="form-horizontal mt-3">
                                                <div class="card-body">
                                                    <div class="form-group row text-left">
                                                        <label class="col-sm-2 col-form-label"> {{ $t('productCategory.parent') }} : </label>
                                                        <div class="col-sm-10">
                                                            <vue-multi-select
                                                                v-model="category.parent_id"
                                                                :placeholder="$t('productCategory.selectMsg')"
                                                                :selectLabel="$t('common.select')"
                                                                :deselectLabel="$t('common.remove')"
                                                                :options="categories.map(item => item.product_category_id)"
                                                                :custom-label="value => categories.find(item => parseInt(item.product_category_id) === parseInt(value)).name"
                                                                :multiple="false"
                                                                :disabled="controlPermission !== 'E'"
                                                            ></vue-multi-select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row text-left">
                                                        <label for="sortOrder" class="col-sm-2 col-form-label">{{ $t('productCategory.sortOrder') }} : </label>
                                                        <div class="col-sm-10">
                                                            <input type="number" class="form-control" id="sortOrder" v-model="category.sort_order"
                                                                   :placeholder="$t('productCategory.sortOrder')" :disabled="controlPermission !== 'E'">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row text-left">
                                                        <label class="col-sm-2 col-form-label"> {{ $t('productCategory.status') }} : （ {{ $t('status.' + category.status) }} ）</label>
                                                        <div class="col-sm-10 pt-3">
                                                            <vue-switch
                                                                v-model="status"
                                                                theme="bootstrap"
                                                                color="success"
                                                                type-bold="true"
                                                            >
                                                            </vue-switch>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </b-tab>
                                    <!--語系設定-->
                                    <b-tab :title="$t('productCategory.description')">
                                        <div class="d-block text-center">
                                            <form class="form-horizontal">
                                                <div class="text-left mb-3">
                                                    <label class="badge bg-success">{{ $t('productCategory.language') }}</label>
                                                </div>
                                                <b-card no-body>
                                                    <b-tabs pills card>
                                                        <b-tab :title="$t('language.' + data.language)" v-for="(data, index) in category.details" :key="index">
                                                            <div class="card-body">
                                                                <div class="form-group row text-left">
                                                                    <label for="name" class="col-sm-2 col-form-label">{{ $t('productCategory.name') }} : </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="name" v-model="category.details[index].name"
                                                                               :placeholder="$t('productCategory.name')" :disabled="controlPermission !== 'E'">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row text-left">
                                                                    <label class="col-sm-2 col-form-label">{{ $t('productCategory.description') }} : </label>
                                                                    <div class="col-sm-10">
                                                                        <ckeditor :editor="ckeditor.editor" v-model="category.details[index].description" :config="ckeditor.config"></ckeditor>
                                                                    </div>
                                                                </div>
                                                                <!-- SEO -->
                                                                <div class="text-left mb-3">
                                                                    <label class="badge bg-success">{{ $t('productCategory.seo') }}</label>
                                                                </div>
                                                                <div class="form-group row text-left">
                                                                    <label for="meta_title" class="col-sm-2 col-form-label">{{ $t('productCategory.metaTitle') }} : </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="meta_title" v-model="category.details[index].meta_title"
                                                                               :placeholder="$t('productCategory.metaTitle')" :disabled="controlPermission !== 'E'">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row text-left">
                                                                    <label for="meta_keyword" class="col-sm-2 col-form-label">{{ $t('productCategory.metaKeyword') }} : </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="meta_keyword" v-model="category.details[index].meta_keyword"
                                                                               :placeholder="$t('productCategory.metaKeyword')" :disabled="controlPermission !== 'E'">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row text-left">
                                                                    <label for="meta_description" class="col-sm-2 col-form-label">{{ $t('productCategory.metaDescription') }} : </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="meta_description" v-model="category.details[index].meta_description"
                                                                               :placeholder="$t('productCategory.metaDescription')" :disabled="controlPermission !== 'E'">
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
                                    <router-link :to="{ name: 'productCategory'}" class="btn bg-danger mr-3">
                                        <i class="fas fa-reply"></i> {{ $t('common.back') }}
                                    </router-link>
                                    <b-button type="button" class="btn btn-success" @click="save()" v-if="controlPermission === 'E'">
                                        <i class="fas fa-pen-nib"></i> {{ $t('common.' + method) }}
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
import ckeditor from '@/plugins/ckeditor'

export default {
    layout: 'admin',
    data() {
        return {
            // loading
            isLoading: false,
            //ckeditor
            ckeditor,
            // 當前語系
            language: 'zh-tw',
            // 商品分類資料
            category: {
                parent_id: null,
                status: 'N',
                name: '',
                sort_order: '',
                details: [],
            },
            // 商品分類集合
            categories: [
                {
                    product_category_id: 0,
                    name: this.$t('productCategory.selectMsg'),
                }
            ],
            //狀態
            status: false,
            // 方法
            method: '',
            // class對應狀態
            classStatus: (status) => [
                {'bg-success': status === 'Y'},
                {'bg-danger': status === 'N'},
            ],
            //操作的權限
            controlPermission: '',
        }
    },
    computed: {},
    watch: {
        status: function (status) {
            this.category.status = status ? 'Y' : 'N'
        },
    },
    mounted() {
        //permission
        this.controlPermission = this.$store.state.permission.product.productCategory

        //方法
        this.method = !this.$route.params.id ? 'create' : 'edit'

        //語系
        this.language = localStorage.getItem('language') || 'zh-tw'

        // 事先取得全部分類內容，在取單一分類，避免下拉內容不同步
        this.isLoading = true
        this.$store.dispatch('authRequest', {
            method: 'get',
            url: '/api/product/category',
        }).then((response) => {
            response.data.forEach((item) => {
                this.categories.push(
                    {
                        'product_category_id': item.product_category_id ,
                        'name': item.name
                    }
                );
            });

            this.isLoading = false;

            //init
            this.init();
        }).catch((error) => {
            this.$root.errorNotify(error);
            this.isLoading = false;
        })
    },
    methods: {

        //=== API 接口 ==========//
        /**
         * init 初始化
         *
         * @since 0.0.1
         * @version 0.0.1
         */
        init() {
            if (this.method === 'edit') {
                return Promise.all([
                    this.edit(),
                ])
            } else {
                return Promise.all([
                    this.setDetail(),
                ])
            }
        },

        /**
         * getProductCategories 取商品分類
         *
         * @since 0.0.1
         * @version 0.0.1
         */
        getProductCategories() {

        },

        /**
         * edit 修改商品分類
         *
         * @since 0.0.1
         * @version 0.0.1
         */
        edit() {
            this.isLoading = true
            //請求參數
            const request = {
                method: 'get',
                url: '/api/product/category/' + this.$route.params.id,
            }

            this.$store.dispatch('authRequest', request).then((response) => {
                this.category = response;
                this.status = (this.category.status === 'Y');
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
            this.isLoading = true;

            //請求參數設定
            const request = {
                method: (this.method === 'create') ? 'post' : 'put',
                url: '/api/product/category' + (this.method === 'create' ? '' : '/' + this.$route.params.id),
                data: this.qs.stringify(this.category)
            }

            //請求
            this.$store.dispatch('authRequest', request).then((response) => {
                this.$router.push({name: 'productCategory'}).then(() => {
                    this.$root.notify(response)
                })
            }).catch((error) => {
                this.$root.errorNotify(error);
                this.isLoading = false;
            })
        },

        /**
         * setDetail 依照語系組成Detail
         *
         * @since 0.0.1
         * @version 0.0.1
         */
        setDetail() {
            Object.keys(this.$t('language')).forEach((language) => {
                this.category.details.push({'language': language})
            })
        },

        //=== API 接口 ==========//
    }
}
</script>

<style>
.table thead > tr > td, .table tbody > tr > td {
    vertical-align: middle;
}

.table .tab-content {
    padding-top: 19px;
}

.required-block {
    display: inline-block;
}
</style>
