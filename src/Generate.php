<?php
declare(strict_types=1);

/**
 * 生成错误码文件
 */

namespace teamones\responseCodeMsg;

class Generate
{
    private $errorClass = null;
    private $root = "";
    private $minNum = 10000;
    private $systemNumCode = "200";

    public function __construct($config)
    {
        try {
            if (!isset($config["class"]) || !is_object($config["class"])) {
                throw new \Exception("请选择需要生成的错误类对象");
            }

            if (!isset($config["root_path"]) || !is_dir($config["root_path"])) {
                throw new \Exception("请选择项目跟路径");
            }

            if (!isset($config["system_number"]) || !is_dir($config["system_number"])) {
                throw new \Exception("请配置系统编码");
            }

            $this->errorClass = $config["class"];
            $this->root = $config["root_path"];
            $this->systemNumCode = Code::getSystemCode($config["system_number"]);

            if (isset($config["start_min_number"]) && $config["start_min_number"] > 0) {
                $this->minNum = intval($config["start_min_number"]);
            }

        } catch (\Exception $e) {
            $this->errorReport($e->getMessage());
        }
    }

    public function run()
    {
        try {

            try {
                $reflection = new \ReflectionClass($this->errorClass);
            } catch (\Exception $e) {
                throw new \Exception("类不存在");
            }

            $classNameSpaceName = $reflection->getNamespaceName();
            $tmp = explode("\\", $reflection->getName());
            $className = end($tmp);
            $classPath = $reflection->getFileName();
            if (!is_writable($classPath)) {
                throw new \Exception("文件不可写");
            }

            echo "start generate...\n";
            $start = $className . '::';
            $return = shell_exec("find $this->root -name '*.php' ! -path './vendor' | xargs grep '$start'");
            $arr = explode("\n", $return);
            $codeList = [];
            foreach ($arr as $str) {
                $str = str_replace(array(" "), array(""), $str);
                $match = [];
                $result = preg_match("/$start(.*?)[,|)|;]/s", $str, $match);
                if (isset($match[1])) {
                    $codeList[$match[1]] = 1;
                }
            }

            $max = $this->minNum;
            $write_list = [];
            foreach ($reflection->getConstants() as $const_name => $val) {
                $write_list[$const_name] = $val;
                unset($codeList[$const_name]);
                if ($val > $max) $max = $val;
            }
            foreach ($codeList as $name => $val) {
                $currentNumber = ++$max;
                $currentErrorNumber = $this->systemNumCode . ((string)$currentNumber);
                $write_list[$name] = -(int)$currentErrorNumber;
            }
            $template = <<<EOT
<?php
/**
 * 自动生成的文件 ,请不要手动修改.
 * @Author:\$Id$
 */
namespace $classNameSpaceName;
class $className
{
EOT;
            foreach ($write_list as $name => $val) {
                $template .= "    const $name = $val;\n";
            }
            $template .= "}";
            file_put_contents($classPath, $template);
            echo "sucess\n";

        } catch (\Exception $e) {
            $this->errorReport($e->getMessage());
        }
    }


    private function errorReport($msg)
    {
        echo $msg . "\n";
        exit;
    }
}