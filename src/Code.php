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
    // 系统标识长度
    const SYSTEM_CODE_LEN = 3;

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
     * 获取错误码系统标识
     * @param string $systemCode
     * @return string
     */
    public static function getSystemCode(string $systemCode = "200")
    {
        return self::generateCode($systemCode, self::SYSTEM_CODE_LEN);
    }
}