<template>
    <section class="content" id="product-list">
        <div class="container-fluid">
            <!-- Tool Bar-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="float-sm-right" v-if="controlPermission === 'E'">
                        <router-link :to="{ name : 'productCreate' }" class="btn bg-success mr-3 mb-3">
                            <i class="fas fa-pen-nib"></i> {{ $t('common.create') }}
                        </router-link>
                        <a class="btn bg-danger mr-2 mb-3" @click="remove()" v-if="selected.length">
                            <i class="fas fa-trash"></i> {{ $t('common.delete') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table-->
            <b-overlay :show="isLoading" rounded="sm">
                <div class="card card-table">
                    <div class="card-header">
                        <label class="badge bg-success">{{ $t('common.searchBar') }}</label>
                        <b-row class="justify-content-md-left">
                            <!-- 型號 -->
                            <b-col lg="2" md="6" sm="12" class="mt-3">
                                <label class="mr-sm-2 ml-2">{{ $t('product.model') }}：</label>
                                <b-form-input class="mr-sm-2" v-model="sentData.model" :placeholder="$t('product.model')"></b-form-input>
                            </b-col>

                            <!-- 名稱 -->
                            <b-col lg="2" md="6" sm="12" class="mt-3">
                                <label class="mr-sm-2 ml-2">{{ $t('product.name') }}：</label>
                                <b-form-input class="mr-sm-2" v-model="sentData.name" :placeholder="$t('product.name')"></b-form-input>
                            </b-col>

                            <!-- 語系 -->
                            <b-col lg="2" md="6" sm="12" class="mt-3">
                                <label class="mr-sm-2 ml-2">{{ $t('product.language') }}：</label>
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

                            <!-- 狀態 -->
                            <b-col lg="2" md="6" sm="12" class="mt-3">
                                <label class="mr-sm-2 ml-2">{{ $t('product.status') }}：</label>
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

                            <!-- 商品分類 -->
                            <b-col lg="2" md="6" sm="12" class="mt-3">
                                <label class="mr-sm-2 ml-2">{{ $t('product.productCategory') }}：</label>
                                <b-form-select v-model="sentData.product_category_id">
                                    <option v-for="(item, index) in productCategory" :value="item.product_category_id" :key="index">
                                        {{item.name}}
                                    </option>
                                    <template #first>
                                        <b-form-select-option :value="null">-- {{ $t('common.choose') }} --
                                        </b-form-select-option>
                                    </template>
                                </b-form-select>
                            </b-col>

                            <!-- 搜尋 -->
                            <b-col lg="2" md="6" sm="12" class="mt-3">
                                <label class="mr-sm-2 ml-2">{{ $t('product.operate') }} ：</label>
                                <div class="col-sm-12">
                                    <b-button variant="outline-success" class="my-2 my-sm-0 ml-2" @click="getProduct()"><i class="fas fa-search"></i> {{ $t('message.search') }}</b-button>
                                </div>
                            </b-col>
                        </b-row>

                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-striped text-nowrap table-bordered">
                            <thead>
                                <tr>
                                    <th v-if="controlPermission === 'E'" >{{ $t('common.select') }}</th>
                                    <th>{{ $t('common.image') }}</th>
                                    <th>{{ $t('product.productId') }}</th>
                                    <th>{{ $t('product.model') }}</th>
                                    <th>{{ $t('product.name') }}</th>
                                    <th>{{ $t('product.costPrice') }}</th>
                                    <th>{{ $t('product.price') }}</th>
                                    <th>{{ $t('product.productCategory') }}</th>
                                    <th>{{ $t('product.view') }}</th>
                                    <th>{{ $t('product.sortOrder') }}</th>
                                    <th>{{ $t('product.createdAt') }}</th>
                                    <th>{{ $t('product.status') }}</th>
                                    <th>{{ $t('product.operate') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in productList" :key="index">
                                    <td v-if="controlPermission === 'E'"><input type="checkbox" :value="data.product_id" v-model="selected"/></td>
                                    <td class="product-image">
                                        <b-img :src="data.url" alt="product-image" v-if="data.url"></b-img>
                                        <b-img :src="notFound" alt="notFound-image" v-else></b-img>
                                    </td>
                                    <td>{{data.product_id}}</td>
                                    <td>{{data.model}}</td>
                                    <td>{{data.name}}</td>
                                    <td>{{data.cost_price}}</td>
                                    <td>{{data.price}}</td>
                                    <td style="white-space: normal;">
                                        <span v-for="(sort, key) in data.categories" class="badge bg-success mr-2" :key="key">
                                            {{ categories[sort] }}
                                        </span>
                                    </td>
                                    <td>{{data.view}}</td>
                                    <td>{{data.sort_order}}</td>
                                    <td>{{data.created_at}}</td>
                                    <td><span class="badge"
                                              :class="classStatus(data.status)">{{$t('status.' + data.status)}}</span>
                                    </td>
                                    <td>
                                        <router-link :to="{ name: 'productEdit', params: { id: data.product_id }}" class="btn btn-primary">
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
                        <div v-if="!isLoading && !productList.length" class="text-notfound text-center">
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
                                    @input="getProduct()"
                            ></b-pagination>
                        </ul>
                    </div>
                </div>
            </b-overlay>
        </div>
    </section>
</template>

<script>
	//notFound-image
	import notFound from '../../assets/notFound.png'

    export default {
        layout: 'admin',
        data() {
            return {
                //分頁資料
                pagination: {
                    current_page: 1,
                    per_page: 10,
                    total: 0,
                    total_pages: 0,
                },
                //商品清單
                productList: {},
                //商品分類
                productCategory: {},
                //狀態列的class
                classStatus: (status) => [
                    {'bg-success': status === 'Y'},
                    {'bg-danger': status === 'N'},
                ],
                //商品分類依k&v格式組合成的陣列
                categories:{},
                //選擇
                selected: [],
                //notfound-image
	            notFound: notFound,
                //請求參數
                sentData: {
                    model:'',
                    name:'',
                    language: null,
                    status: null,
                    product_category_id: null,
                },
                //loading
                isLoading: false,
                //操作的權限
                controlPermission: '',
            }
        },
        computed: {},
        mounted() {
        	//permission
            this.controlPermission = this.$store.state.permission.product.productList

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
                    this.getProduct(),
                    this.getProductCategory()
                ])
            },

            /**
             * getProduct 取商品
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            getProduct() {
                this.isLoading = true
                const request = {
                    method: 'get',
                    url: '/api/product',
                    params: {
                        page: this.pagination.current_page,
                        per_page: this.pagination.per_page
                    }
                }

                if(this.sentData.model !== '') request.params.model = this.sentData.model
                if(this.sentData.name !== '') request.params.name = this.sentData.name
                if(this.sentData.language !== '') request.params.language = this.sentData.language
                if(this.sentData.status !== '') request.params.status = this.sentData.status
                if(this.sentData.product_category_id !== '') request.params.product_category_id = this.sentData.product_category_id

                this.$store.dispatch('authRequest', request).then((response) => {
                    this.productList = response.data
                    this.pagination = response.meta.pagination
                    this.isLoading = false
                }).catch((error) => {
	                this.$root.errorNotify(error)
                })
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
                    this.productCategory = response.data;

                    response.data.forEach((data) => {
                        this.categories[data.product_category_id] = data.name
                    })
                }).catch((error) => {
	                this.$root.errorNotify(error);
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
                        let productId = this.selected.join(',')
                        //請求刪除
                        const request = {
                            method: 'delete',
                            url: '/api/product/' + productId,
                        }

                        //請求刪除前驗證
                        this.$store.dispatch('authRequest', request).then((response) => {
	                        this.$root.notify(response);

                            this.getProduct()
                        }).catch((error) => {
	                        this.$root.errorNotify(error);

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
