<?php
declare(strict_types=1);

/**
 * 生成错误码文件
 */

namespace teamones\responseCodeMsg;

class Generate
{
    // 错误码存储文件
    protected $file = '';

    public function __construct($config)
    {
        try {
            if (!isset($config["file"]) || !is_dir($config["file"])) {
                throw new \Exception("请选择项目跟路径");
            }

            $this->file = $config["file"];

        } catch (\Exception $e) {
            $this->errorReport($e->getMessage());
        }
    }

    /**
     * 打印错误信息
     * @param $msg
     */
    private function errorReport($msg)
    {
        echo $msg . "\n";
        exit;
    }
}