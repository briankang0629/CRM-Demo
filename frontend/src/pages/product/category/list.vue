<template>
    <section class="content" id="product-category-list">
        <div class="container-fluid">
            <!-- Tool Bar-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="float-sm-right" v-if="controlPermission === 'E'">
                        <router-link :to="{ name : 'productCategoryCreate' }" class="btn bg-success mr-3 mb-3">
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
                                <b-form-select v-model="sentData.parent_id">
                                    <option v-for="(item, index) in categories" :value="item.product_category_id" :key="index">
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
                                    <b-button variant="outline-success" class="my-2 my-sm-0 ml-2" @click="overview()"><i class="fas fa-search"></i> {{ $t('message.search') }}</b-button>
                                </div>
                            </b-col>
                        </b-row>

                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-striped text-nowrap table-bordered">
                            <thead>
                                <tr>
                                    <th v-if="controlPermission === 'E'" >{{ $t('common.select') }}</th>
                                    <th>{{ $t('productCategory.productCategoryId') }}</th>
                                    <th>{{ $t('productCategory.name') }}</th>
                                    <th>{{ $t('productCategory.count') }}</th>
                                    <th>{{ $t('productCategory.sortOrder') }}</th>
                                    <th>{{ $t('productCategory.createdAt') }}</th>
                                    <th>{{ $t('productCategory.status') }}</th>
                                    <th>{{ $t('productCategory.operate') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in categories" :key="index">
                                    <td v-if="controlPermission === 'E'"><input type="checkbox" :value="data.product_category_id" v-model="selected"/></td>
                                    <td>{{data.product_category_id}}</td>
                                    <td>{{data.name}}</td>
                                    <td>{{data.count}}</td>
                                    <td>{{data.sort_order}}</td>
                                    <td>{{data.created_at}}</td>
                                    <td><span class="badge"
                                              :class="classStatus(data.status)">{{$t('status.' + data.status)}}</span>
                                    </td>
                                    <td>
                                        <router-link :to="{ name: 'productCategoryEdit', params: { id: data.product_category_id }}" class="btn btn-primary">
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
                        <div v-if="!isLoading && !categories.length" class="text-notfound text-center">
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
    export default {
        layout: 'admin',
        data() {
            return {
                // 分頁資料
                pagination: {
                    current_page: 1,
                    per_page: 10,
                    total: 0,
                    total_pages: 0,
                },
                // 商品分類清單
                categories: {},
                // 商品分類
                productCategory: {},
                // 狀態列的class
                classStatus: (status) => [
                    {'bg-success': status === 'Y'},
                    {'bg-danger': status === 'N'},
                ],
                // 選擇
                selected: [],
                // 請求參數
                sentData: {
                    model: '',
                    name: '',
                    language: null,
                    status: null,
                    parent_id: null,
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
            this.controlPermission = this.$store.state.permission.product.productCategory

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
                    this.overview()
                ])
            },

            /**
             * overview 取商品分類
             *
             * @since 0.0.1
             * @version 0.0.1
             */
            overview() {
                this.isLoading = true
                const request = {
                    method: 'get',
                    url: '/api/product/category',
                    params: {
                        page: this.pagination.current_page,
                        per_page: this.pagination.per_page
                    }
                }

                if(this.sentData.name !== '') request.params.name = this.sentData.name
                if(this.sentData.status !== '') request.params.status = this.sentData.status
                if(this.sentData.parent_id !== '') request.params.parent_id = this.sentData.parent_id

                this.$store.dispatch('authRequest', request).then((response) => {
                    this.categories = response.data
                    this.pagination = response.meta.pagination
                    this.isLoading = false
                }).catch((error) => {
	                this.$root.errorNotify(error)
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
                        let categoriesId = this.selected.join(',')
                        //請求刪除
                        const request = {
                            method: 'delete',
                            url: '/api/product/category/' + categoriesId,
                        }

                        //請求刪除前驗證
                        this.$store.dispatch('authRequest', request).then((response) => {
	                        this.$root.notify(response);

                            this.overview()
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
