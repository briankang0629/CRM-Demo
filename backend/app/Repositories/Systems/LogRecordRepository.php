<?php

namespace App\Repositories\Systems;

use App\Models\Systems\LogRecord;
use Carbon\Carbon;
trait LogRecordRepository
{
    /**
     * @var array
     */
    private $identityArray = [
        'admin' => 'A',
        'user' => 'U',
        'super' => 'S',
    ];

    /**
     * writeLog
     *
     * @param int $logId
     * @param array $request
     * @param mixed $model
     * @return void
     */
    public function writeLog(int $logId, $model = null) :void
    {
        // 宣告
        $oldContent = [];
        $newContent = [];

        // model 不為空
        if (!is_null($model)) {
            // 若是新增的內容，將參數全部印出
            if ($model->wasRecentlyCreated) {
                foreach ($model->getOriginal() as $key => $value) {
                    $newContent[$key] = $this->format($key, $value);
                }
            } else {
                // 若有傳送 model 近來，卻沒有更動紀錄，不紀錄
                if (count($model->getChanges()) === 1 && array_get($model->getChanges(), 'updated_at')) {
                    return;
                }

                // 將有更變的存數存起來
                foreach ($model->getChanges() as $key => $value) {
                    $oldContent[$key] = $this->format($key, $model->getOriginalBeforeSave($key));
                    $newContent[$key] = $this->format($key, $value);
                }
            }
        }

        //S == 相關設定資訊設定
        //sever 資訊
        $serverInfo = request()->server();

        //操作帳號判定
        $identity = 'super';

        if (is_admin()) {
            $identity = 'admin';
        }

        if (is_website()) {
            $identity = 'user';
        }

        //操作者判定
        $loginUser = defined('LOGIN_USER') ? LOGIN_USER : [
            'name' => 'system',
            'class' => 'super'
        ];

        //E == 相關設定資訊設定

        //傳送參數
        $sentData = [
            'log_id' => $logId ,
            'admin_id' => ($identity == 'admin') ? $loginUser['admin_id'] : 0 ,
            'user_id' => ($identity == 'user') ? $loginUser['user_id'] : 0 ,
            'account' => $loginUser['account'] ,
            'class' => $this->identityArray[$identity] ,
            'remote_ip' => $serverInfo['REMOTE_ADDR'] ,
            'server_addr' => $serverInfo['REMOTE_ADDR'] , //$serverInfo['SERVER_ADDR'] ,@todo
            'server_info' => json_encode($serverInfo, JSON_UNESCAPED_UNICODE) ,
            'host' => $serverInfo['REMOTE_ADDR'] , //$serverInfo['HTTP_X_FORWARDED_HOST'] ,@todo
            'path' => $serverInfo['REQUEST_URI'] ,
            'new_date' => Carbon::now()->format('Y-m-d') ,
        ];

        // 有更新內容才紀錄
        $sentData['old_content'] = json_encode($oldContent, JSON_UNESCAPED_UNICODE);
        $sentData['new_content'] = json_encode($newContent, JSON_UNESCAPED_UNICODE);

        app(LogRecord::class)->create($sentData);
    }

    /**
     * format 制定變數規範
     * @param string $key
     * @param mixed $value
     */
    private function format(string $key, $value)
    {
        if ($key == 'updated_at') {
            return Carbon::createFromFormat('Y-m-d H:i:s', $value)->toDateTimeString();
        }

        return $value;
    }
}
