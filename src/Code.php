<?php
declare(strict_types=1);

/**
 * 错误码规范
 * - -2xx00000
 * - 错误码为负数，共8位，前三位标识系统，中间两位是服务标识，后3位标识错误码
 */

namespace teamones\responseCodeMsg;

class Code
{
    // 错误码系统标识
    protected static $systemCode = "200";

    // 错误码服务标识
    protected static $serverCode = "01";

    // 系统标识长度
    const SYSTEM_CODE_LEN = 3;

    // 服务编码长度
    const SERVER_CODE_LEN = 2;

    /**
     * 生成处理code位数
     * @param string $code
     * @param int $len
     * @return string
     */
    protected static function generateCode(string $code, int $len): string
    {
        $numberLength = (int)\strlen($code);

        if ($numberLength > $len) {
            // 大于3位截取前三位
            return \substr($code, 0, $len);
        } elseif ($numberLength < $len) {
            // 小于3位后面补充0
            return \str_pad($code, ($len - $numberLength), "0", STR_PAD_RIGHT);
        } else {
            return $code;
        }
    }

    /**
     * 设置错误码系统标识
     * @param string $systemCode
     */
    public static function setSystemCode(string $systemCode = "200")
    {
        self::$systemCode = self::generateCode($systemCode, self::SYSTEM_CODE_LEN);
    }

    /**
     * 设置错误码服务标识
     * @param string $serverCode
     */
    public static function setServerCode(string $serverCode = "01")
    {
        self::$serverCode = self::generateCode($serverCode, self::SERVER_CODE_LEN);
    }
}