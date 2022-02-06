<?php

namespace App\Services\Products;

use App\Models\Products\Product;
use App\Models\Products\ProductOption;
use App\Models\Products\ProductOptionDetail;
use App\Models\Products\ProductOptionValue;
use App\Models\Products\ProductOptionValueDetail;
use App\Models\Products\ProductCategory;
use App\Models\Products\ProductDetail;
use App\Models\Products\ProductCategoryPath;

class ProductService
{
    /**
     * @var object
     */
    private $product;

    /**
     * @var object
     */
    private $productCategory;

    /**
     * @var object
     */
    private $productDetail;

    /**
     * @var object
     */
    private $productOption;

    /**
     * @var object
     */
    private $productOptionDetail;

    /**
     * @var object
     */
    private $productOptionValue;

    /**
     * @var object
     */
    private $productOptionValueDetail;

    /**
     * ProductService constructor.
     *
     * @param Product $product
     * @param ProductCategory $productCategory
     * @param ProductDetail $productDetail
     * @param ProductOption $productOption
     * @param ProductOptionDetail $productOptionDetail
     * @param ProductOptionValue $productOptionValue
     * @param ProductOptionValueDetail $productOptionValueDetail
     */
    public function __construct(
        Product $product,
        ProductCategory $productCategory,
        ProductDetail $productDetail,
        ProductOption $productOption,
        ProductOptionDetail $productOptionDetail,
        ProductOptionValue $productOptionValue,
        ProductOptionValueDetail $productOptionValueDetail
    )
    {
        $this->product = $product;
        $this->productCategory = $productCategory;
        $this->productDetail = $productDetail;
        $this->productOption = $productOption;
        $this->productOptionDetail = $productOptionDetail;
        $this->productOptionValue = $productOptionValue;
        $this->productOptionValueDetail = $productOptionValueDetail;
    }

    /**
     * lists
     *
     * @param array $request
     * @return mixed
     */
    public function lists(array $request)
    {
        $request['language'] = array_get($request, 'language', config('system.defaultLanguage'));

        $products = $this->product->search($request)->with('categories.details')->paginate(per_page());
        return $products;
    }

    /**
     * info
     *
     * @param $id
     * @return Product
     */
    public function info(int $id, array $request) :Product
    {
        if (array_get($request, 'language')) {
            $product = $this->product->with(['detail' => function ($product) use ($request) {
                $product->where('language', $request['language']);
            }])->with(['options.detail' => function ($detail) use ($request) {
                $detail->where('language', $request['language']);
            }])->with(['options.values.detail' => function ($detail) use ($request) {
                $detail->where('language', $request['language']);
            }])->with(['categories', 'image', 'relateImage'])->findOrFail($id);
        } else {
            $product = $this->product->with(['details', 'categories', 'options.details', 'options.values.details', 'image', 'relateImage'])->findOrFail($id);
        }
        return $product;
    }

    /**
     * update
     *
     * @param array $request
     * @param int $id
     * @return void
     */
    public function update(array $request, int $id = null) :void
    {
        // 商品
        $product = $this->product->findOrNew($id);
        $product->fill($request);
        $product->save();

        // 語系
        ProductDetail::where('product_id', $id)->delete();
        foreach ($request['details'] as $detail) {
            $product->detail->create($detail);
        }

        // 分類依據樹狀資料搜尋所有parent
        $request['categories'] = ProductCategoryPath::whereIn('product_category_id', $request['categories'])
            ->pluck('path_id')->flatten()->unique();

        // 分類
        $product->categories()->detach();
        $product->categories()->attach($request['categories']);

        // 處理選項
        if ($options = array_get($request, 'options')) {
            $optionService = new \App\Services\Products\ProductOptionService;
            $optionService->updateOptionByProduct($product, $options);
        }

        // 關聯商品圖片
        $product->relateToImage()->detach();

        if(array_get($request, 'relateImage')) {
            $relateImage = [];
            foreach ($request['relateImage'] as $image) {
                $relateImage[$image['upload_id']] = ['sort_order' => $image['sort_order']];
            }

            $product->relateToImage()->attach($relateImage);
        }

        //操作記錄
        $this->product->writeLog($id ? 18 : 17, $product);
    }

    /**
     * delete
     *
     * @param array $id
     * @return void
     */
    public function delete(array $ids) :void
    {
        //選項
        $productOptionIds = $this->productOption->whereIn('product_id', $ids)->pluck('product_option_id')->toArray();

        //選項值
        $productOptionValueIds = $this->productOptionValue->whereIn('product_option_id', $productOptionIds)->pluck('product_option_value_id')->toArray();

        // 選項刪除
        $this->deleteOption($productOptionIds);

        // 選項值刪除
        $this->deleteOptionValue($productOptionValueIds);

        // 商品相關刪除
        \DB::table('product_to_category')->whereIn('product_id', $ids)->delete();
        $this->productDetail->whereIn('product_id', $ids)->delete();
        $this->product->whereIn('product_id', $ids)->delete();

        //操作記錄
        $this->product->writeLog(19);
    }

    /**
     * deleteOption 選項刪除
     *
     * @param array $ids
     */
    public function deleteOption(array $ids)
    {
        $this->productOption->whereIn('product_option_id', $ids)->delete();
        $this->productOptionDetail->whereIn('product_option_id', $ids)->delete();
    }

    /**
     * deleteOptionValue 選項值刪除
     *
     * @param array $ids
     */
    public function deleteOptionValue(array $ids)
    {
        $this->productOptionValue->whereIn('product_option_value_id', $ids)->delete();
        $this->productOptionValueDetail->whereIn('product_option_value_id', $ids)->delete();
    }
}
