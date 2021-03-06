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
        // ??????
        $product = $this->product->findOrNew($id);
        $product->fill($request);
        $product->save();

        // ??????
        ProductDetail::where('product_id', $id)->delete();
        foreach ($request['details'] as $detail) {
            $product->detail->create($detail);
        }

        // ????????????????????????????????????parent
        $request['categories'] = ProductCategoryPath::whereIn('product_category_id', $request['categories'])
            ->pluck('path_id')->flatten()->unique();

        // ??????
        $product->categories()->detach();
        $product->categories()->attach($request['categories']);

        // ????????????
        if ($options = array_get($request, 'options')) {
            $optionService = new \App\Services\Products\ProductOptionService;
            $optionService->updateOptionByProduct($product, $options);
        }

        // ??????????????????
        $product->relateToImage()->detach();

        if(array_get($request, 'relateImage')) {
            $relateImage = [];
            foreach ($request['relateImage'] as $image) {
                $relateImage[$image['upload_id']] = ['sort_order' => $image['sort_order']];
            }

            $product->relateToImage()->attach($relateImage);
        }

        //????????????
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
        //??????
        $productOptionIds = $this->productOption->whereIn('product_id', $ids)->pluck('product_option_id')->toArray();

        //?????????
        $productOptionValueIds = $this->productOptionValue->whereIn('product_option_id', $productOptionIds)->pluck('product_option_value_id')->toArray();

        // ????????????
        $this->deleteOption($productOptionIds);

        // ???????????????
        $this->deleteOptionValue($productOptionValueIds);

        // ??????????????????
        \DB::table('product_to_category')->whereIn('product_id', $ids)->delete();
        $this->productDetail->whereIn('product_id', $ids)->delete();
        $this->product->whereIn('product_id', $ids)->delete();

        //????????????
        $this->product->writeLog(19);
    }

    /**
     * deleteOption ????????????
     *
     * @param array $ids
     */
    public function deleteOption(array $ids)
    {
        $this->productOption->whereIn('product_option_id', $ids)->delete();
        $this->productOptionDetail->whereIn('product_option_id', $ids)->delete();
    }

    /**
     * deleteOptionValue ???????????????
     *
     * @param array $ids
     */
    public function deleteOptionValue(array $ids)
    {
        $this->productOptionValue->whereIn('product_option_value_id', $ids)->delete();
        $this->productOptionValueDetail->whereIn('product_option_value_id', $ids)->delete();
    }
}
