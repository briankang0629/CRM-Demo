<template>
    <section class="content" id="product-edit">
        <div class="container-fluid">
            <!-- Tool Bar-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="float-sm-right">
                        <router-link :to="{ name: 'productList' }" class="btn bg-danger mr-2 mb-3">
                            <i class="fas fa-reply"></i> {{ $t('common.back') }}
                        </router-link>
                        <button class="btn bg-primary mr-2 mb-3" @click="save(false)">
                            <i class="fas fa-save"></i> {{ $t('common.syncUpdate') }} (更新刷新選項)
                        </button>
                    </div>
                </div>
            </div>
            <b-overlay :show="isLoading" rounded="sm">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <span class="card-title"> <i class="fas fa-edit"></i> {{ $t('product.' + method) }}</span>
                            </div>
                            <div class="card-body">
                                <b-tabs content-class="mt-3"  pills card>
                                    <!--基本設定-->
                                    <b-tab :title="$t('product.basic')" active>
                                        <div class="d-block text-center">
                                            <form class="form-horizontal mt-3">
                                                <div class="card-body">
                                                    <div class="form-group row text-left">
                                                        <label for="model" class="col-sm-2 col-form-label">{{ $t('product.model') }} : </label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="model" v-model="product.model"
                                                                   :placeholder="$t('product.model')" :disabled="controlPermission !== 'E'">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row text-left">
                                                        <label class="col-sm-2 col-form-label"> {{ $t('product.productCategory') }} : </label>
                                                        <div class="col-sm-10">
                                                            <vue-multi-select
                                                                    v-model="product.categories"
                                                                    :placeholder="$t('product.selectMsg')"
                                                                    :selectLabel="$t('common.select')"
                                                                    :deselectLabel="$t('common.remove')"
                                                                    :options="productCategory.map(item => item.productCategoryId)"
                                                                    :custom-label="value => productCategory.find(item => parseInt(item.productCategoryId) === parseInt(value)).name"
                                                                    :multiple="true"
                                                                    :disabled="controlPermission !== 'E'"
                                                            ></vue-multi-select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row text-left">
                                                        <label for="costPrice" class="col-sm-2 col-form-label">{{ $t('product.costPrice') }} : </label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="costPrice" v-model="product.cost_price"
                                                                   :placeholder="$t('product.costPrice')" :disabled="controlPermission !== 'E'">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row text-left">
                                                        <label for="price" class="col-sm-2 col-form-label">{{ $t('product.price') }} : </label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="price"
                                                                   v-model="product.price" :placeholder="$t('product.price')" :disabled="controlPermission !== 'E'">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row text-left">
                                                        <label for="sortOrder" class="col-sm-2 col-form-label">{{ $t('product.sortOrder') }} : </label>
                                                        <div class="col-sm-10">
                                                            <input type="number" class="form-control" id="sortOrder" v-model="product.sort_order"
                                                                   :placeholder="$t('product.sortOrder')" :disabled="controlPermission !== 'E'">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row text-left">
                                                        <label class="col-sm-2 col-form-label"> {{ $t('product.status') }} :
                                                            <span id="edit-status">( {{ $t('status.' + product.status) }} )</span>
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
                                                </div>
                                            </form>
                                        </div>
                                    </b-tab>
                                    <!--語系設定-->
                                    <b-tab :title="$t('product.description')">
                                        <div class="d-block text-center">
                                            <form class="form-horizontal">
                                                <div class="text-left mb-3">
                                                    <label class="badge bg-success" >{{ $t('product.language') }}</label>
                                                </div>
                                                <b-card no-body>
                                                    <b-tabs pills card>
                                                        <b-tab :title="$t('language.' + data.language)" v-for="(data, index) in product.details" :key="index">
                                                            <div class="card-body">
                                                                <div class="form-group row text-left">
                                                                    <label for="name" class="col-sm-2 col-form-label">{{ $t('product.name') }} : </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="name" v-model="product.details[index].name"
                                                                               :placeholder="$t('product.name')" :disabled="controlPermission !== 'E'">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row text-left">
                                                                    <label class="col-sm-2 col-form-label">{{ $t('product.description') }} : </label>
                                                                    <div class="col-sm-10">
                                                                        <ckeditor :editor="ckeditor.editor" v-model="product.details[index].description" :config="ckeditor.config"></ckeditor>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row text-left">
                                                                    <label for="meta_title" class="col-sm-2 col-form-label">{{ $t('product.intro') }} : </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="intro" v-model="product.details[index].intro"
                                                                               :placeholder="$t('product.intro')" :disabled="controlPermission !== 'E'">
                                                                    </div>
                                                                </div>
                                                                <!-- SEO -->
                                                                <div class="text-left mb-3">
                                                                    <label class="badge bg-success" >{{ $t('product.seo') }}</label>
                                                                </div>
                                                                <div class="form-group row text-left">
                                                                    <label for="meta_title" class="col-sm-2 col-form-label">{{ $t('product.metaTitle') }} : </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="meta_title" v-model="product.details[index].meta_title"
                                                                               :placeholder="$t('product.metaTitle')" :disabled="controlPermission !== 'E'">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row text-left">
                                                                    <label for="meta_keyword" class="col-sm-2 col-form-label">{{ $t('product.metaKeyword') }} : </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="meta_keyword" v-model="product.details[index].meta_keyword"
                                                                               :placeholder="$t('product.metaKeyword')" :disabled="controlPermission !== 'E'">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row text-left">
                                                                    <label for="meta_description" class="col-sm-2 col-form-label">{{ $t('product.metaDescription') }} : </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="meta_description" v-model="product.details[index].meta_description"
                                                                               :placeholder="$t('product.metaDescription')" :disabled="controlPermission !== 'E'">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </b-tab>
                                                    </b-tabs>
                                                </b-card>
                                            </form>
                                        </div>
                                    </b-tab>
                                    <!--圖片設定-->
                                    <b-tab :title="$t('product.imageSetting')">
                                        <component :is="imageUploadComponent" :imageConfig="imageConfig" @getImageConfig="imageConfig = $event"></component>
                                        <component :is="imageBatchUploadComponent" :imageBatchConfig="imageBatchConfig" @getImageBatchConfig="imageBatchConfig = $event"></component>
                                    </b-tab>
                                    <!--商品選項-->
                                    <b-tab :title="$t('product.productOption')">
                                        <div class="text-left mb-3">
                                            <label class="badge bg-success">{{ $t('product.productOption') }}</label>
	                                        <div class="text-right">
		                                        <b-button class="btn btn-success" v-b-modal.createOption>
			                                        <i class="fa fa-plus-circle mr-2"></i>
			                                        <span>{{ $t('productOption.manageOption') }}</span>
		                                        </b-button>
	                                        </div>
                                        </div>
                                        <b-card no-body>
                                            <b-tabs pills card>
                                                <b-tab :title="getNameByLanguage(option.details)" v-for="(option, sort) in product.options" :key="sort">
                                                    <!--<div class="mt-2 mb-3">-->
                                                        <!--<div class="col-12">-->
                                                            <!--<div class="custom-control custom-checkbox">-->
                                                                <!--<input v-model="option.required" type="checkbox" autocomplete="off" class="custom-control-input" true-value="Y" false-value="N" id="required">-->
                                                                <!--<label class="custom-control-label" for="required"> {{ $t('productOption.optionRequired') }}</label>-->
                                                            <!--</div>-->
                                                        <!--</div>-->
                                                    <!--</div>-->

                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-striped table-bordered text-nowrap">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{ $t('productOption.optionValueName') }}</th>
                                                                    <th>{{ $t('productOption.quantity') }}</th>
                                                                    <th>{{ $t('productOption.isStock') }}</th>
                                                                    <th>{{ $t('productOption.optionPrice') }}</th>
                                                                    <th>{{ $t('productOption.returnPoint') }}</th>
                                                                    <th>{{ $t('productOption.weight') }}({{ $t('productOption.gram') }})</th>
                                                                    <th v-if="controlPermission === 'E'">{{ $t('productOption.operate') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="(value, key) in option.values" :key="key">
                                                                    <td width="25%" style="min-width: 200px;">
                                                                        <b-tabs content-class="" pills vertical>
                                                                            <b-tab :title="$t('language.' + valueDetail.language)" v-for="(valueDetail, index) in value.details" :key="index">
                                                                                <input class="form-control" type="text" v-model="valueDetail.name" />
                                                                            </b-tab>
                                                                        </b-tabs>
                                                                    </td>
                                                                    <td width="10%">
                                                                        <input class="form-control" type="number" v-model="value.quantity"/>
                                                                    </td>
                                                                    <td width="10%">
                                                                        <b-form-select v-model="value.is_stock">
                                                                            <option v-for="(item, index) in $t('enable')" :value="index" :key="index">
                                                                                {{ item }}
                                                                            </option>
                                                                            <template #first>
                                                                                <b-form-select-option :value="null">-- {{ $t('common.choose') }} --
                                                                                </b-form-select-option>
                                                                            </template>
                                                                        </b-form-select>
                                                                    </td>
                                                                    <td width="10%">
                                                                        <input class="form-control" type="number" v-model="value.price" />
                                                                    </td>
                                                                    <td width="10%">
                                                                        <input class="form-control" type="number" v-model="value.point"/>
                                                                    </td>
                                                                    <td width="10%">
                                                                        <input class="form-control" type="number" v-model="value.weight"/>
                                                                    </td>
                                                                    <td width="10%" class="text-center" v-if="controlPermission === 'E'">
                                                                        <b-button class="btn btn-danger" @click="removeOption('optionValue', key, option.values )"><i class="fa fa-trash-alt"></i></b-button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot v-if="controlPermission === 'E'">
	                                                            <tr>
	                                                                <td colspan="6"></td>
	                                                                <td>
	                                                                    <b-button class="btn btn-success" @click="addOptionValue(option.values, option.product_option_id)"><i class="fa fa-plus-circle"></i></b-button>
	                                                                </td>
	                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </b-tab>
                                            </b-tabs>
                                        </b-card>

                                    </b-tab>
                                    <!--商品規格-->
                                    <b-tab :title="$t('product.productSpecification')">
                                    </b-tab>
                                </b-tabs>
                            </div>
                            <div class="card-footer">
                                <div class="text-right">
                                    <router-link :to="{ name: 'productList'}" class="btn bg-danger mr-3">
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
            <!-- 新增選項區 -->
            <b-modal id="createOption" ref="createOption" :ok-title-html="$t('common.confirm')" :title="$t('productOption.manageOption')" :hide-footer="controlPermission !== 'E'" ok-only>
                <div class="d-block text-center">
                    <form class="form-horizontal">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered text-nowrap">
                                <thead>
                                    <tr>
                                        <th>{{ $t('productOption.optionName') }}</th>
                                        <th>{{ $t('productOption.type') }}</th>
                                        <th v-if="controlPermission === 'E'">{{ $t('productOption.operate') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(option, index) in product.options" :key="index">
                                        <td width="60%" style="min-width: 150px;">
                                            <b-tabs content-class="" pills vertical>
	                                            <b-tab :title="$t('language.' + data.language)" v-for="(data, index) in option.details" :key="index">
		                                            <input class="form-control" type="text" v-model="data.name" />
	                                            </b-tab>
                                            </b-tabs>
                                        </td>

                                        <td class="text-center">
                                            <b-form-select v-model="option.type">
                                                <option v-for="(label, type) in $t('productOption.types')" :value="type" :key="type">
                                                    {{ label }}
                                                </option>
                                            </b-form-select>
                                        </td>

                                        <td class="text-center" v-if="controlPermission === 'E'">
                                            <b-button class="btn btn-danger" @click="removeOption('option' , index)"><i class="fa fa-trash-alt"></i></b-button>
                                        </td>
                                    </tr>
                                </tbody>
	                            <tfoot v-if="controlPermission === 'E'">
		                            <tr>
			                            <td colspan="2"></td>
			                            <td>
				                            <b-button class="btn btn-success" @click="addOption()"><i class="fa fa-plus-circle"></i></b-button>
			                            </td>
		                            </tr>
	                            </tfoot>
                            </table>
                        </div>
                    </form>
                </div>
            </b-modal>
        </div>
    </section>
</template>

<script>
    import ckeditor from '@/plugins/ckeditor'
    export default {
        layout: 'admin',
        data() {
            return {
                //loading
                isLoading: false,
                //ckeditor
                ckeditor,
                //當前語系
                language: 'zh-tw',
                //商品資料
                product: {
                    status: 'N',
                    categories: [],
                    model: '',
                    name: '',
                    costPrice: '',
                    price: '',
                    sortOrder: '',
                    details: [],
                    product_option: [],
                    productSpecification: [],
                },
                //狀態
                status: false,
                //商品分類
                productCategory: [],
                //方法
                method: '',
                //class對應狀態
                classStatus: (status) => [
                    {'bg-success': status === 'Y'},
                    {'bg-danger': status === 'N'},
                ],
                //操作的權限
                controlPermission: '',
                //圖片component
	            imageUploadComponent: null,
	            //要傳送給component的資料
	            imageConfig: {
		            url: '',
	            },
	            //圖片批量上傳 component
	            imageBatchUploadComponent: null,
	            //要傳送給component的資料
	            imageBatchConfig: [],
            }
        },
        computed: {},
        watch: {
        	status : function (status) {
                this.product.status = status ? 'Y' : 'N'
	        },
        },
        mounted() {
        	//permission
            this.controlPermission = this.$store.state.permission.product.productList

            //方法
            this.method = !this.$route.params.id ? 'create' : 'edit'

            //語系
            this.language = localStorage.getItem('language') || 'zh-tw'

            //init
            this.init();
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
                if(this.method === 'edit') {
                    return Promise.all([
                        this.edit(this.$route.params.id),
                        this.getProductCategory()
                    ])
                } else {
                    return Promise.all([
                        this.setProductDetail(),
                        this.getProductCategory()
                    ])
                }
            },

            /**
             * getProductCategory 取商品分類
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            getProductCategory() {
                const request = {
                    method: 'get',
                    url: '/api/product/category',
                    params: {
                        isFamilyName: true
                    }
                }

                this.$store.dispatch('authRequest', request).then((response) => {
                    this.productCategory = response.data.map((item) => {
                        return {'productCategoryId': item. product_category_id , 'name': item. name};
                    })
                }).catch((error) => {
	                this.$root.errorNotify(error)
                })
            },

            /**
             * edit 修改管理者
             *
             * @param productId
             * @since 0.0.1
             * @version 0.0.1
             */
            edit(productId) {
                this.isLoading = true
                //請求參數
                const request = {
                    method: 'get',
                    url: '/api/product/' + productId,
                }

                this.$store.dispatch('authRequest', request).then((response) => {
                    this.product = response
                    this.status = this.product.status === 'Y';

                    //圖片相關資料整理
                    this.imageConfig.url = this.product.url
                    this.imageBatchConfig = this.product.relatedImage

                    //API抓取資料後再引入component
                    this.imageUploadComponent = () => import('@/components/media/imageUpload.vue')
                    this.imageBatchUploadComponent = () => import('@/components/media/imageBatchUpload.vue')

                    this.isLoading = false
                }).catch((error) => {
	                this.$root.errorNotify(error)
                    this.isLoading = false
                })
            },

            /**
             * save 儲存資訊
             *
             * @param redirect [{boolean}] 是否轉跳回列表頁
             * @since 0.0.1
             * @version 0.0.1
             */
            save(redirect = true) {
            	this.isLoading = true;

            	//商品的對應圖片ID
	            this.product.upload_id = this.imageConfig.uploadId

                //商品的關聯圖片
	            this.product.relateImage = this.imageBatchConfig.map((image) => {
	                return {
	                    'upload_id': image.upload_id,
                        'sort_order': image.sort_order
                    };
                })

                //商品分類
                this.product.categories = this.product.categories.map((categoryId) => {
                    return categoryId;
                })

                //請求參數設定
                const request = {
                    method: (this.method === 'create') ? 'post' : 'put',
                    url: '/api/product' + (this.method === 'create' ? '' : '/' + this.$route.params.id),
                    data: this.qs.stringify(this.product)
                }

                //請求
                this.$store.dispatch('authRequest', request).then((response) => {
                    this.$root.notify(response);
                    if (redirect) {
                        this.$router.push({ name : 'productList'});
                    }
                    this.init();
                }).catch((error) => {
	                this.$root.errorNotify(error);
                }).finally(() => {
                    this.isLoading = false;
                })
            },

            /**
             * setProductDetail 依照語系組成Detail
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            setProductDetail() {
                Object.keys(this.$t('language')).forEach((language) => {
                    this.product.details.push({'language' : language})
                })

                //API抓取資料後再引入component
                this.imageUploadComponent = () => import('@/components/media/imageUpload.vue')
                this.imageBatchUploadComponent = () => import('@/components/media/imageBatchUpload.vue')
            },

            //=== API 接口 ==========//

            //=== 內部功能區塊 ==========//
            /**
             * getNameByLanguage 依照語系取得名稱
             *
             * @since 0.0.1
             * @version 0.0.1
             * @params data
             */
            getNameByLanguage( data ) {
                return Object.values(data).filter((item) => {
                    return item.language === localStorage.getItem('language')
                })[0].name
            },

            /**
             * addOption 新增選項
             *
             * @since 0.0.1
             * @version 0.0.1
             * @params string type
             */
            addOption() {
                this.product.options.push({
                    type: "radio",
                    product_id: this.product.productId,
                    product_option_id: 0,
                    required: "Y",
                    sort_order: 0,
                    details:(Object.keys(this.$t('language')).map((language) => {
                        return {'language' : language}
                    })),
                    values: []
                })
            },

            /**
             * addOptionValue 新增選項值
             *
             * @since 0.0.1
             * @version 0.0.1
             * @params string type
             */
            addOptionValue(data, productOptionId = 0) {
                data.push({
                    price: 0,
                    quantity: 0,
                    point: 0,
                    weight: 0,
                    is_stock: 'N',
                    sort_order: 0,
                    product_option_id: productOptionId,
                    details:(Object.keys(this.$t('language')).map((language) => {
                        return {'language' : language}
                    })),
                })
            },

            /**
             * removeOption 移除選項
             *
             * @since 0.0.1
             * @version 0.0.1
             * @params string type
             * @params int index
             * @params array option
             */
            removeOption( type , index = 0 , option = [] ) {
                let askSetting = {
                    title: this.$t('message.askDelete'),
                    text: this.$t('message.askDeleteMessage'),
                    confirmText: this.$t('common.confirm'),
                    cancelText: this.$t('common.cancel'),
                }

                switch (type) {
                    case 'option':
                        //詢問刪除
                        if(this.product.options[index].product_option_id) {
                            this.$Swal.ask(askSetting).then((result) => {
                                if (result.isConfirmed) {
                                    this.isLoading = true
                                    let productOptionId = this.product.options[index].product_option_id

                                    //請求刪除
                                    const request = {
                                        method: 'delete',
                                        url: '/api/product/option/' + productOptionId,
                                    }

                                    //請求刪除前驗證
                                    this.$store.dispatch('authRequest', request).then((response) => {
                                        this.$root.notify(response);
                                        this.product.options.splice(index , 1)
                                    }).catch((error) => {
                                        this.$root.errorNotify(error);
                                    }).finally(() => {
                                        this.isLoading = false
                                    })
                                }
                            })
                        } else {
                            this.product.options.splice(index , 1)
                        }

                        break;
                    case 'optionValue':
                        //詢問刪除
                        if(option[index].product_option_value_id) {
                            this.$Swal.ask(askSetting).then((result) => {
                                if (result.isConfirmed) {
                                    this.isLoading = true
                                    let productOptionValueId = option[index].product_option_value_id

                                    //請求刪除
                                    const request = {
                                        method: 'delete',
                                        url: '/api/product/option/value/' + productOptionValueId,
                                    }

                                    //請求刪除前驗證
                                    this.$store.dispatch('authRequest', request).then((response) => {
                                        this.$root.notify(response);
                                        option.splice(index , 1)
                                    }).catch((error) => {
                                        this.$root.errorNotify(error);
                                    }).finally(() => {
                                        this.isLoading = false
                                    })
                                }
                            })
                        } else {
                            option.splice(index , 1)
                        }

                        break;
                    default:
                        break;
                }
            }

            //=== 內部功能區塊 ==========//
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
