<?php

namespace App\Traits;

use Carbon\Carbon;

trait BaseModel
{
    private $originalData;

    /**
     * save
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $this->attachHost();

        // 紀錄儲存前的資料
        $this->setOriginalBeforeSave();

        return parent::save($options);
    }

    /**
     * attachHost
     * @return void
     */
    private function attachHost()
    {
        if (is_super()) {
            return ;
        }

        if ($this->host_id) {
            return ;
        }

        $this->host_id = host()->host_id;
    }

    /**
     * 將父層 getOriginal 覆寫，操作記錄會用到
     */
    private function setOriginalBeforeSave()
    {
        $this->originalData = parent::getOriginal();
    }

    /**
     * 取得儲存前的舊資料
     * @param string $key
     */
    public function getOriginalBeforeSave($key = null)
    {
        if (!$key) {
            return $this->originalData;
        }

        return array_get($this->originalData, $key);
    }

    /**
     * created_at
     * @return string|null
     */
    public function getCreatedAtAttribute()
    {
        if (!array_get($this->attributes, 'created_at')) {
            return null;
        }
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at'])->toDateTimeString();
    }

    /**
     * updated_at
     * @return string|null
     */
    public function getUpdatedAtAttribute()
    {
        if (!array_get($this->attributes, 'updated_at')) {
            return null;
        }
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['updated_at'])->toDateTimeString();
    }
}
