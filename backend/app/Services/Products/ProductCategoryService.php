<?php

namespace App\Services\Products;

use App\Models\Products\Product;
use App\Models\Products\ProductCategory;
use App\Models\Products\ProductCategoryDetail;
use App\Exceptions\CustomException;
use App\Models\Products\ProductCategoryPath;

class ProductCategoryService
{
    const TREE_FILED = [
        'product_category_id',
        'name',
        'key',
        'parent_id',
        'count',
    ];
    /**
     * @var object
     */
    private $productCategory;

    /**
     * @var object
     */
    private $productCategoryDetail;

    /**
     * @var string
     */
    private $tempName;

    /**
     * ProductCategoryService constructor.
     *
     * @param ProductCategory $productCategory
     * @param ProductCategoryDetail $productCategoryDetail
     */
    public function __construct(
        ProductCategory $productCategory,
        ProductCategoryDetail $productCategoryDetail
    )
    {
        $this->productCategory = $productCategory;
        $this->productCategoryDetail = $productCategoryDetail;
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

        $productCategories = $this->productCategory->search($request)->with('families', 'detail')->paginate(per_page());

        $allCategories = $productCategories->all();
        // 判斷是否要顯示為樹狀結構
        if (array_get($request, 'isTree')) {

            // 過濾上層parent_id = 0
            $productCategories = $productCategories->filter(function ($item) {
                return $item->parent_id === 0;
            });

            foreach ($productCategories as $item) {
                $item->children = $this->makeProductCategoryTree($allCategories, $item['product_category_id']);
            }
        }

        // 判斷是否要將名稱加上family
        if (array_get($request, 'isFamilyName')) {
            $this->makeFamilyName($productCategories);
        }
        return $productCategories;
    }

    /**
     * info
     *
     * @param $id
     * @return ProductCategory
     */
    public function info(int $id) :ProductCategory
    {
        $productCategory = $this->productCategory->findOrFail($id);
        $productCategory->details = $productCategory->details()->get()->keyBy(function ($category) {
            return $category->language;
        });

        return $productCategory;
    }

    /**
     * store
     *
     * @param array $request
     * @return void
     */
    public function store(array $request) :void
    {
        $productCategory = $this->productCategory->create($request);
        $this->setCategoryPath($productCategory, array_get($request, 'parent_id', 0));

        collect($request['details'])->map(function ($detail) use ($productCategory) {
            $detail['product_category_id'] = $productCategory->product_category_id;
            $this->productCategoryDetail->create($detail);
        });

        //操作記錄
        $this->productCategory->writeLog(28, $productCategory);
    }

    /**
     * update
     *
     * @param array $request
     * @return void
     */
    public function update(int $id, array $request) :void
    {
        $productCategory = $this->productCategory->findOrFail($id);

        $this->setCategoryPath($productCategory, $request['parent_id']);
        $productCategory->fill($request);
        $productCategory->save();

        //更新詳細內容
        collect($request['details'])->map(function ($detail) use ($id) {
            $this->productCategoryDetail->where([
                'product_category_id' => $id,
                'language' => $detail['language'],
            ])->update($detail);
        });

        //操作記錄
        $this->productCategory->writeLog(29, $productCategory);
    }

    /**
     * delete
     *
     * @param array $id
     * @return void
     */
    public function delete(array $ids) :void
    {
        $productCategories = $this->productCategory->with('products')->whereIn('product_category_id', $ids);

        // 檢查分類有無包含商品
        $productCategories->each(function ($category) {
            if ($category->products->count()) {
                throw new CustomException(trans('common.productCategory.containProduct'));
            }
        });

        // 執行刪除
        $this->productCategory->destroy($ids);

        //操作記錄
        $this->productCategory->writeLog(30);
    }

    /**
     * makeFamilyName 依據商品分類製作層級的名稱
     *
     * @since 0.0.1
     * @version 0.0.1
     * @param array $categories
     * @return mixed
     */
    public function makeFamilyName($categories) {
        $collection = $categories->pluck('detail.name', 'product_category_id');

        foreach ($categories as $item) {
            $name = '';
            foreach ($item->families as $index => $family) {
                $name .= $collection[$family->path_id];

                // 若不是最後一組，加上 >
                if (count($item->families) > $index + 1) {
                    $name .= ' > ';
                }
            }
            $item->name = $name;
        }
    }

    /**
     * makeProductCategoryTree 依據商品分類製作家族數
     *
     * @since 0.0.1
     * @version 0.0.1
     * @param array $categories
     * @param int $parentId
     * @return mixed
     */
    public function makeProductCategoryTree($categories , $parentId = 0) {
        $tempArray = [];
        foreach ($categories as $key => $category) {
            // format 欄位
            $category->count = $category->products->where('status', Product::ENABLE)->count();
            $category->key = $category->product_category_id;
            $category = $category->only(self::TREE_FILED);

            // 抓取子集合
            if($category['parent_id'] == $parentId) {
                unset($categories[$key]);
                $category['children'] = $this->makeProductCategoryTree($categories , $category['product_category_id']);
                $tempArray[] = $category;
            }
        }

        return $tempArray;
    }

    /**
     * 分派分類樹狀結構
     * @param $category
     * @param $parentId
     */
    private function setCategoryPath($category, $parentId)
    {
        $categoryPaths = app(\App\Models\Products\ProductCategoryPath::class);
        $currents = $category->paths;

        if (!$currents->isEmpty()) {
            foreach ($currents->toArray() as $category_path) {
                // Delete the path below the current one 刪除該分類結點以上的父節點
                $categoryPaths->where('product_category_id', $category_path['product_category_id'])
                    ->where('level', '<', $category_path['level'])->delete();

                $path = array();

                // Get the nodes new parents 取該分類節點最新上層parent
                $newParents = $categoryPaths->where('product_category_id', $parentId)->orderBy('level')->get()->toArray();
                foreach ($newParents as $result) {
                    $path[] = $result['path_id'];
                }

                // Get whats left of the nodes current path 取當前分類剩下下線
                $leftPaths = $categoryPaths->where('product_category_id', $category_path['product_category_id'])->orderBy('level')->get()->toArray();
                foreach ($leftPaths as $result) {
                    $path[] = $result['path_id'];
                }

                // 合併
                $level = 0;

                foreach ($path as $path_id) {
                    \DB::select(\DB::raw("REPLACE INTO product_category_paths SET product_category_id = " . $category_path['product_category_id'] . ", path_id = " . $path_id . ", level = " . $level));

                    $level++;
                }
            }
        } else {
            // Delete the path below the current one 刪除該分類結點以上的父節點
            $categoryPaths->where('product_category_id', $category->product_category_id)->delete();

            // Fix for records with no paths
            $level = 0;

            $newParents = $categoryPaths->where('product_category_id', $parentId)->orderBy('level')->get()->toArray();

            // 創建新的樹狀
            foreach ($newParents as $result) {
                $categoryPaths->create([
                    'product_category_id' => $category->product_category_id,
                    'path_id' => $result['path_id'],
                    'level' => $result['level']
                ]);

                $level++;
            }
            \DB::select(\DB::raw("REPLACE INTO product_category_paths SET product_category_id = " . $category->product_category_id. ", path_id = " . $category->product_category_id . ", level = " . $level));
        }
    }
}
