<?php

/**
 *    处理文本助手类
 *
 *
 * @package HanJia
 * @subpackage helpers
 * @version 0.0.1
 * @author  johnsneakers
 *
 *
 */
class OutputHelper
{
    /**
     * 生成原图片路径
     * @param $path
     * @return string
     */
    public static function getImageRawPath($path)
    {
        $paths = explode('/', $path);
        $paths[count($paths) - 1] = '.raw/' . $paths[count($paths) - 1];
        $path = implode('/', $paths);
        return $path;
    }


    /**
     * 检查是否被采集
     */
    public static function checkIsCapture()
    {
        if (
                !isset($_SERVER['HTTP_USER_AGENT']) ||
                !isset($_SERVER['HTTP_REFERER']) ||
                !isset($_SERVER['HTTP_HOST'])
        ) {
            return true;
        } else {
            return false;
        }
    }

    public static function currentMicroTime()
    {
        $time = explode(" ", microtime());
        $time = $time[1] + $time[0];
        return $time;
    }

    /**
     * 清除word格式
     * @param $string
     * @return mixed
     */
    public static function clearWordFomat($string)
    {
        preg_match_all('/<p.+?>/is', $string, $p);

        if ($p && $p[0]) {

            foreach ($p[0] as $element) {
                if (preg_match('/class=\"p\d+\"/is', $element)) {
                    $string = str_replace($element, '<p>', $string);
                }
                if (preg_match('/margin:0cm/is', $element)) {
                    $string = str_replace($element, '<p>', $string);
                }
            }


            //strpos($p[0])
        }

        preg_match_all('/<span.+?>/is', $string, $span);

        if ($span && $span[0]) {

            foreach ($span[0] as $element) {
                if (strpos($element, 'font-family') !== false) {
                    $string = str_replace($element, '<span>', $string);
                }
                if (strpos($element, 'font-size') !== false) {
                    $string = str_replace($element, '<span>', $string);
                }
            }
            //strpos($p[0])
        }

        $string = str_replace(array(
                '<span class="Apple-tab-span" style="white-space:pre"></span>'
        ), '', $string);

        //$string = preg_replace('/选项\d+　/is', '', $string);
        //$string = preg_replace('/选项\d+\s+/is', '', $string);
        $string = self::fullWidth2HalfWidth($string);
        return $string;
    }


    /**
     * 清除采集微信文章
     * @param $string
     * @return string
     */
    public static function clearWeixinFomat($string)
    {

        /*$string = preg_replace(
                array(
                        '/<p.*?>/is',
                        '/<span.*?>/is',
                ),
                array(
                        '<p>',
                        '<span>',
                ),
                $string);*/

        $string = str_replace(
                array(),
                array(),
                $string
        );

        $string = self::fullWidth2HalfWidth($string);
        return $string;
    }

    /**
     *  将一个字串中含有全角的数字字符、字母、空格或'%+-()'字符转换为相应半角字符
     *
     * @access  public
     * @param   string 　$str　待转换字串
     * @return  string
     */
    public static function fullWidth2HalfWidth($str)
    {
        $arr = array(
                '０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
                '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
                'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
                'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
                'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
                'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
                'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
                'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
                'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
                'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
                'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
                'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
                'ｙ' => 'y', 'ｚ' => 'z'
        );
        return strtr($str, $arr);
    }

    /**
     * 判断一串字符是否为日语
     * @param $string
     * @return int
     */
    public static function checkStringIsJapanese($string)
    {
        #前半断判断平假名，后半段判断片假名
        return preg_match('/\xe3(\x81[\x81-\xbf]|\x82[\x80-\x93]|\x83\xbc)|\xe3(\x82[\xa1-\xbf]|\x83[\x80-\xb6]|\x83\xbc)/', $string);
    }


    /**
     * 获取球面距离
     */
    public static function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $distance = 6371.012 *
                acos(cos(acos(-1) / 180 * $lat2) *
                        cos(acos(-1) / 180 * $lat1) *
                        cos(acos(-1) / 180 * $lng2 - acos(-1) / 180 * $lng1) +
                        sin(acos(-1) / 180 * $lat2) *
                        sin(acos(-1) / 180 * $lat1)) * 1;

        if ($distance <= 1000) {
            $distance = round($distance, 0) . 'm';
        } else {
            $distance = round($distance / 1000, 2) . 'km';
        }

        return $distance;
    }

    /**
     * 网址修正
     * @param $surl 需要修正的网址
     * @param $URI 　上一个网址
     * @return string
     */
    public static function urlCorrection($surl, $URI)
    {

        $urls = parse_url($URI);
        $_HomeUrl = $urls["host"];
        $_BaseUrlPath = $_HomeUrl . $urls["path"];
        $_BaseUrlPath = preg_replace("/\/([^\/]*)\.(.*)$/", "/", $_BaseUrlPath);
        $_BaseUrlPath = preg_replace("/\/$/", "", $_BaseUrlPath);

        $i = 0;
        $dstr = "";
        $pstr = "";
        $okurl = "";
        $pathStep = 0;
        $surl = trim($surl);
        if ($surl == "")
            return "";
        $pos = strpos($surl, "#");
        if ($pos > 0)
            $surl = substr($surl, 0, $pos);
        if ($surl[0] == "/") {
            $okurl = "http://" . $_HomeUrl . $surl;
        } else
            if ($surl[0] == ".") {
                if (strlen($surl) <= 1)
                    return "";
                else
                    if ($surl[1] == "/") {
                        $okurl = "http://" . $_BaseUrlPath . "/" . substr($surl, 2, strlen($surl) - 2);
                    } else {
                        $urls = explode("/", $surl);
                        foreach ($urls as $u) {
                            if ($u == "..")
                                $pathStep++;
                            else
                                if ($i < count($urls) - 1)
                                    $dstr .= $urls[$i] . "/";
                                else
                                    $dstr .= $urls[$i];
                            $i++;
                        }
                        $urls = explode("/", $_BaseUrlPath);
                        if (count($urls) <= $pathStep)
                            return "";
                        else {
                            $pstr = "http://";
                            for ($i = 0; $i < count($urls) - $pathStep; $i++) {
                                $pstr .= $urls[$i] . "/";
                            }
                            $okurl = $pstr . $dstr;
                        }
                    }
            } else {
                if (strlen($surl) < 7)
                    $okurl = "http://" . $_BaseUrlPath . "/" . $surl;
                else
                    if (strtolower(substr($surl, 0, 7)) == "http://")
                        $okurl = $surl;
                    else
                        $okurl = "http://" . $_BaseUrlPath . "/" . $surl;
            }
        $okurl = preg_replace("/^(http:\/\/)/", "", $okurl);
        $okurl = preg_replace("/\/{1,}/", "/", $okurl);
        return "http://" . $okurl;
    }


    /**
     * 获取今天起始时间戳和结束时间戳
     * @return array
     */
    public static function getTodayScope()
    {
        return array(
                'begin' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                'end' => mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1,
        );
    }

    public static function cookieEncrypt($string, $length = -6)
    {
        return substr(md5($string), $length);
    }

    public static function cookieEncryptCheck($string, $password)
    {
        return $string == self::cookieEncrypt($password);
    }

    /**
     * 获取昨日起始时间戳和结束时间戳
     * @return array
     */
    public static function getYestodayScope()
    {
        return array(
                'begin' => mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')),
                'end' => mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1,
        );
    }

    /**
     * 获取本周起始时间戳和结束时间戳
     * @return array
     */
    public static function getThisWeekScope()
    {
        return array(
                'begin' => mktime(0, 0, 0, date('m'), date('d') - date('w') + 1, date('Y')),
                'end' => mktime(23, 59, 59, date('m'), date('d') - date('w') + 7, date('Y')),
        );
    }

    /**
     * 获取上周起始时间戳和结束时间戳
     * @return array
     */
    public static function getLastWeekScope()
    {
        return array(
                'begin' => mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - 7, date('Y')),
                'end' => mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - 7, date('Y')),
        );
    }

    /**
     * 获取本月起始时间戳和结束时间戳
     * @return array
     */
    public static function getThisMonthScope()
    {
        return array(
                'begin' => mktime(0, 0, 0, date('m'), 1, date('Y')),
                'end' => mktime(23, 59, 59, date('m'), date('t'), date('Y')),
        );
    }

    public static function getLastMonthScope()
    {
        return array(
                'begin' => mktime(0, 0, 0, date('m') - 1, 1, date('Y')),
                'end' => mktime(23, 59, 59, date('m') + 1, date('t', mktime(0, 0, 0, date('m') - 1, 1, date('Y'))), date('Y')),
        );
    }

    /**
     * Character Limiter
     *
     * 文字到达一定字数结尾用省略号....
     *
     * @access public
     * @param  string - 字符串
     * @param  integer - 多少个字符截断
     * @param  string - 结束符号
     * @return string  -
     */
    public static function characterLimiter($str, $n = 500, $end_char = '&#8230;')
    {
        if (mb_strlen($str, 'utf-8') < $n)
            return $str;

        $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));
        echo $str;
        exit;
        if (mb_strlen($str, 'utf-8') <= $n)
            return $str;

        $out = "";

        foreach (explode(' ', trim($str)) as $val) {
            $out .= $val . ' ';

            if (mb_strlen($out, 'utf-8') >= $n) {
                $out = trim($out);
                return (mb_strlen($out, 'utf-8') == mb_strlen($str, 'utf-8')) ? $out : $out . $end_char;
            }
        }
    }

    /**
     * Word Limiter
     *
     * Обрезать текст до определенного колиства слов, добавив в конце "..."
     *
     * @access public
     * @param  string - строка для обрезания
     * @param  integer - до скольких символов обрезать строку
     * @param  string - окончание текста
     * @return string  - новая строка
     */

    public static function wordLimiter($str, $limit = 100, $end_char = '&#8230;')
    {
        if (trim($str) == '')
            return $str;

        preg_match('/^\s*+(?:\S++\s*+){1,' . (int)$limit . '}/', $str, $matches);

        if (mb_strlen($str) == mb_strlen($matches[0]))
            $end_char = '';

        return rtrim($matches[0]) . $end_char;
    }

    /**
     * Цензор слов
     *
     * Принимает строку и массив запрещенных слов. Слова в строке,
     * которые содержатся в массиве заменяются на символы ###
     *
     * @access public
     * @param  string - строка
     * @param  array - массив запрещенных слов
     * @param  string - чем замещать слова
     * @return string - строка после замены
     */
    public static function wordCensor($str, $censored, $replacement = '')
    {
        if (!is_array($censored))
            return $str;

        $str = ' ' . $str . ' ';

        // \w, \b and a few others do not match on a unicode character
        // set for performance reasons. As a result words like über
        // will not match on a word boundary. Instead, we'll assume that
        // a bad word will be bookended by any of these characters.
        $delim = '[-_\'\"`(){}<>\[\]|!?@#%&,.:;^~*+=\/ 0-9\n\r\t]';

        foreach ($censored as $badword) {
            if ($replacement != '')
                $str = preg_replace(
                        "/({$delim})(" . str_replace('\*', '\w*?', preg_quote($badword, '/')) . ")({$delim})/i",
                        "\\1{$replacement}\\3",
                        $str
                );
            else
                $str = preg_replace(
                        "/({$delim})(" . str_replace('\*', '\w*?', preg_quote($badword, '/')) . ")({$delim})/ie",
                        "'\\1'.str_repeat('#', strlen('\\2')).'\\3'",
                        $str
                );
        }

        return trim($str);
    }

    /**
     * Выделить фразу
     *
     * Выделить фразу в тексте
     *
     * @access public
     * @param  string - строка для поиска
     * @param  string - фраза для выделения
     * @param  string - текст, который будет вставлен до найденной фразы
     * @param  string - текст, который будет вставлен после найденной фразы
     * @return string - строка с выделенными фразами
     */
    public static function highlightPhrase($str, $phrase, $tag_open = '<strong>', $tag_close = '</strong>')
    {
        if ($str == '')
            return '';

        if ($phrase != '')
            return preg_replace('/(' . preg_quote($phrase, '/') . ')/i', $tag_open . "\\1" . $tag_close, $str);

        return $str;
    }

    /**
     * Word Wrap
     *
     * Wraps text at the specified character.  Maintains the integrity of words.
     * Anything placed between {unwrap}{/unwrap} will not be word wrapped, nor
     * will URLs.
     *
     * @access public
     * @param  integer - the number of characters to wrap at
     * @param string $charlim
     * @internal param $string - the text string
     * @return string
     */

    function wordWrap($str, $charlim = '76')
    {
        // Se the character limit
        if (!is_numeric($charlim))
            $charlim = 76;

        // Reduce multiple spaces
        $str = preg_replace("| +|", " ", $str);

        // Standardize newlines
        if (strpos($str, "\r") !== FALSE)
            $str = str_replace(array("\r\n", "\r"), "\n", $str);

        // If the current word is surrounded by {unwrap} tags we'll
        // strip the entire chunk and replace it with a marker.
        $unwrap = array();
        if (preg_match_all("|(\{unwrap\}.+?\{/unwrap\})|s", $str, $matches))
            for ($i = 0; $i < count($matches['0']); $i++) {
                $unwrap[] = $matches['1'][$i];
                $str = str_replace($matches['1'][$i], "{{unwrapped" . $i . "}}", $str);
            }

        // Use PHP's native function to do the initial wordwrap.
        // We set the cut flag to FALSE so that any individual words that are
        // too long get left alone.  In the next step we'll deal with them.
        $str = wordwrap($str, $charlim, "\n", FALSE);

        // Split the string into individual lines of text and cycle through them
        $output = "";
        foreach (explode("\n", $str) as $line) {
            // Is the line within the allowed character count?
            // If so we'll join it to the output and continue
            if (strlen($line) <= $charlim) {
                $output .= $line . "\n";
                continue;
            }

            $temp = '';
            while ((strlen($line)) > $charlim) {
                // If the over-length word is a URL we won't wrap it
                if (preg_match("!\[url.+\]|://|wwww.!", $line))
                    break;

                // Trim the word down
                $temp .= substr($line, 0, $charlim - 1);
                $line = substr($line, $charlim - 1);
            }

            // If $temp contains data it means we had to split up an over-length
            // word into smaller chunks so we'll add it back to our current line
            $output .= ($temp != '') ? $temp . "\n" . $line : $line;
            $output .= "\n";
        }

        // Put our markers back
        if (count($unwrap) > 0)
            foreach ($unwrap as $key => $val)
                $output = str_replace("{{unwrapped" . $key . "}}", $val, $output);

        // Remove the unwrap tags
        $output = str_replace(array('{unwrap}', '{/unwrap}'), '', $output);

        return $output;
    }


    public static function asciiToEntities($str)
    {
        $count = 1;
        $out = '';
        $temp = array();

        for ($i = 0, $s = mb_strlen($str); $i < $s; $i++) {
            $ordinal = ord($str[$i]);

            if ($ordinal < 128) {
                /*
                 * If the $temp array has a value but we have moved on, then it seems only
                 * fair that we output that entity and restart $temp before continuing. -Paul
                 */
                if (count($temp) == 1) {
                    $out .= '&#' . array_shift($temp) . ';';
                    $count = 1;
                }

                $out .= $str[$i];
            } else {
                if (count($temp) == 0)
                    $count = ($ordinal < 224) ? 2 : 3;

                $temp[] = $ordinal;

                if (count($temp) == $count) {
                    $number = ($count == 3)
                            ? (($temp['0'] % 16) * 4096) + (($temp['1'] % 64) * 64) + ($temp['2'] % 64)
                            : (($temp['0'] % 32) * 64) + ($temp['1'] % 64);

                    $out .= '&#' . $number . ';';
                    $count = 1;
                    $temp = array();
                }
            }
        }

        return $out;
    }


    public static function entitiesToAscii($str, $all = TRUE)
    {
        if (preg_match_all('/\&#(\d+)\;/', $str, $matches)) {
            for ($i = 0, $s = count($matches['0']); $i < $s; $i++) {
                $digits = $matches['1'][$i];
                $out = '';

                if ($digits < 128)
                    $out .= chr($digits);
                elseif ($digits < 2048) {
                    $out .= chr(192 + (($digits - ($digits % 64)) / 64));
                    $out .= chr(128 + ($digits % 64));
                } else {
                    $out .= chr(224 + (($digits - ($digits % 4096)) / 4096));
                    $out .= chr(128 + ((($digits % 4096) - ($digits % 64)) / 64));
                    $out .= chr(128 + ($digits % 64));
                }

                $str = str_replace($matches['0'][$i], $out, $str);
            }
        }

        if ($all)
            $str = str_replace(
                    array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;", "&#45;"),
                    array("&", "<", ">", "\"", "'", "-"),
                    $str
            );

        return $str;
    }

    /**
     * 从序列化数组中获取数据
     * @param $data
     * @param null $item
     * @return mixed|null
     */
    public static function getItemFromSerializeData($data, $item = null)
    {
        $data = unserialize($data);
        if ($item) {
            if (isset($data[$item]) && $data[$item]) {
                $r = $data[$item];
            } else {
                $r = null;
            }
        } else {
            $r = $data;
        }
        return $r;
    }

    /**
     * 自动填充数字位数
     * @param int $number
     * @param int $bit
     * @return string
     */
    public static function fillNumberWithZone($number, $bit = 6)
    {
        return sprintf("%0" . $bit . "d", intval($number));
    }


    /**
     * 时间格式化函数
     * @param $dateline
     * @param bool $hommization 　人性化时间显示
     * @param string $format
     * @return bool|string
     */
    public static function timeFormat($dateline, $hommization = true, $format = "Y-m-d")
    {
        if (!$dateline) {
            return null;
        }
        if ($hommization) {
            $rtime = date($format, $dateline);
            $htime = date("H:i", $dateline);
            $difftime = DATELINE - $dateline;
            if ($difftime < -86400 * 7) {
                $str = date($format, $dateline);
            } else if ($difftime > -86400 * 7 && $difftime <= -86400) {
                $str = abs(ceil($difftime / 86400)) . '天后';
            } else if ($difftime > -86400 && $difftime <= -3600) {
                $str = abs(ceil($difftime / 3600)) . '小时后';
            } else if ($difftime > -3600 && $difftime <= -60) {
                $str = abs(ceil($difftime / 60)) . '分钟后';
            } else if ($difftime > -60 && $difftime < 0) {
                $str = abs(ceil($difftime / 60)) . '秒后';
            } else if ($difftime == 0) {
                $str = '刚刚';
            } else if ($difftime < 60) {
                $str = $difftime . '秒前';
            } else if ($difftime < 3600) {
                $min = floor($difftime / 60);
                $str = $min . '分钟前';
            } else if ($difftime < 3600 * 24) {
                $h = floor($difftime / (3600));
                $str = $h . '小时前';
            } else if ($difftime < 3600 * 24 * 3) {
                $d = floor($difftime / (3600 * 24));
                if ($d == 1) {
                    $str = '昨天 ' . $htime;
                } else {
                    $str = '前天 ' . $htime;
                }
            } else {
                $str = $rtime;
            }
            $time = $str;
        } else {
            $time = date($format, $dateline);
        }

        return $time;
    }


    /**
     * 获取延迟日期
     * @param null $item
     * @return array|string
     */
    public static function getDelayDateList($item = null)
    {
        $data = array(
                time() + 86400 => '延迟 1 天',
                time() + 86400 * 2 => '延迟 2 天',
                time() + 86400 * 3 => '延迟 3 天',
                time() + 86400 * 4 => '延迟 4 天',
                time() + 86400 * 5 => '延迟 5 天',
                time() + 86400 * 6 => '延迟 6 天',
                time() + 86400 * 7 => '延迟 7 天',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getSexList($item = null)
    {
        $data = array(
                '0' => '保密',
                '1' => '男',
                '2' => '女',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    /**
     * 获取状态列表
     * @param null $item
     * @return array
     */
    public static function getStatusList($item = null)
    {
        $data = array(
                'Y' => '已审核',
                'N' => '未审核',
                'F' => '审核未通过',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getEmailSendStatusList($item = null)
    {
        $data = array(
                'Y' => '已发送',
                'N' => '未发送',
                'F' => '发送失败',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getLogTypeList($item = null)
    {
        $data = array(
                'notice' => '提醒错误',
                'fatal' => '致命错误',
                'timeout' => '命令超时',

        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    public static function getStatusEditorList($item = null)
    {
        $data = array(
                'Y' => '已审核',
                'N' => '未审核',
                'E' => '待审核',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getStatusEditorVistorList($item = null)
    {
        $data = array(
                'N' => '未审核',
                'E' => '待审核',
                'Y' => '已审核',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getStatusNotificationList($item = null)
    {
        $data = array(
                'N' => '未读',
                'Y' => '已读',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getStatusFeedbackList($item = null)
    {
        $data = array(
                'N' => '未回复',
                'Y' => '已回复',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    public static function getNotificationSourceList($item = null)
    {
        $data = array(
                'from' => '收到',
                'to' => '发出',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getNotificationReadStatusList($item = null)
    {
        $data = array(
                'Y' => '已读',
                'N' => '未读',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getWeixinEnabledDisableList($item = null)
    {
        $data = array(
                'Y' => '启用',
                'F' => '禁用',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    public static function getStatusEnabledDisableList($item = null)
    {
        $data = array(
                'Y' => '启用',
                'N' => '禁用',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    public static function getExamLimitTypeList($item = null)
    {
        $data = array(
                'password' => '密码访问',
                'ip' => '限定IP范围',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getExamStatusList($item = null)
    {
        $data = array(
                'Y' => '启用',
                'N' => '禁用',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getTaskStatusList($item = null)
    {
        $data = array(
                'N' => '未分配',
                'P' => '处理中',
                'V' => '待审核',
                'Y' => '已审核',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    public static function getDefaultStatusList($item = null)
    {
        $data = array(
                'Y' => '默认',
                'N' => '--',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getResponseTypeList($item = null)
    {
        $data = array(
                'N' => '图文',
                'T' => '文字',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    /**
     * 获取模块列表
     * @return array
     */
    public static function getModulesList()
    {
        $model = Module::model()->findAll();

        return CHtml::listData($model, 'sign', 'name');
    }


    /**
     * 积分重复周期名称列表
     * @param null $item
     * @return array
     */
    public static function getCreditRepeatTypeList($item = null)
    {
        $data = array(
                'I' => '不限',
                'O' => '仅一次',
                'Mon' => '每 x 月',
                'Day' => '每 x 天',
                'Hour' => '每 x 时',
                'Min' => '每 x 分',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;

    }


    /**
     * 获取视频访问权限
     * @param null $item
     * @return array
     */


    public static function getSuggestionTypeList($item = null)
    {
        $data = array(
                'A' => '咨询', //ask
                'S' => '建议', //suggest
                'C' => '投诉', //complaint
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    public static function getVoteRequiredList($item = null)
    {
        $data = array(
                'Y' => '必须投票', //ask
                'N' => '选投', //suggest
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getVoteMultipleList($item = null)
    {
        $data = array(
                'Y' => '多选', //ask
                'N' => '单选', //suggest
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    public static function getDeliveryTimeList($item = null)
    {
        $data = array(
                '尽快送达' => '尽快送达',
                '中午12:00前送达' => '中午12:00前送达',
                '傍晚18:00后送达' => '傍晚18:00后送达',
                '其他时间，特殊要求中注明' => '其他时间，特殊要求中注明',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    /**
     * 获取微信菜单类型列表
     * @param null $item
     * @return array|string
     */
    public static function getWeixinMenuTypeList($item = null)
    {
        $data = array(
                'menu' => '菜单',
                'view' => '链接',
                'click' => '指定功能',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;

    }

    /**
     * 获取微信订阅状态
     * @param null $item
     * @return array|string
     */
    public static function getWeixinSubcribeStatusList($item = null)
    {
        $data = array(
                'Y' => '已订阅',
                'N' => '已退订',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;

    }

    /**
     * 微信消息类型
     * @param null $item
     * @return array|string
     */
    public static function getWeixinMessageTypeList($item = null)
    {
        $data = array(
                'text' => '文本',
                'image' => '图片',
                'location' => '位置',
                'link' => '链接',
                'voice' => '语音',
                'event' => '事件',
        );

        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;

    }

    /**
     * 微信回复状态
     * @param null $item
     * @return array|string
     */
    public static function getWeixinMessageStatusList($item = null)
    {
        $data = array(
                'Y' => '已回复',
                'N' => '未回复',
        );

        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;

    }

    /**
     * 微信账号类型
     * @param null $item
     * @return array|string
     */
    public static function getWeixinAccountTypeList($item = null)
    {
        $data = array(
                'D' => '订阅号',
                'F' => '服务号',
        );

        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;

    }

    /**
     * 微信指令类型
     * @param null $item
     * @return array|string
     */
    public static function getWeixinCommandTypeList($item = null)
    {
        $data = array(
                'flow' => '流程',
                'relation' => '关联素材',
                'function' => '子程序处理',
                'event' => '菜单按钮事件',
                'interface' => '第三方接口',
        );

        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;

    }

    public static function getWeixinFlowDataTypeList($item = null)
    {
        $data = array(
                'text' => '文本',
                'choice' => '选择题',
                'audio' => '音频',
                'video' => '视频',
                'image' => '图片'
        );

        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;


    }

    /**
     * 获取微信指令匹配模式
     * @param null $item
     * @return array|string
     */
    public static function getWeixinCommandMatchTypeList($item = null)
    {
        $data = array(
                'precise' => '精确匹配',
                'fuzzy' => '模糊匹配',
                'regular' => '正则匹配',
        );

        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;

    }

    public static function getWeixinFlowNeedAnswerList($item = null)
    {
        $data = array(
                'Y' => '需要用户回答',
                'N' => '不需要用户回答',
        );

        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getNeedTypeList($item = null)
    {
        $data = array(
                'N' => '不需要',
                'Y' => '需要',
        );

        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;

    }

    /**
     * 获取微信素材类型
     * @param null $item
     * @return array|string
     */
    public static function getWeixinMaterialsTypeList($item = null)
    {
        $data = array(
                'text' => '文本',
                'news' => '图文',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;

    }

    /**
     * 微信命令记录统计范围列表
     * @param null $item
     * @return array|string
     */
    public static function getWeixinCommandLogScopeList($item = null)
    {
        $data = array(
                'day' => '今天',
                'yestoday' => '昨天',
                'week' => '最近一周',
                '2week' => '最近两周',
                'month' => '最近一月',
                'season' => '最近一季',
                'year' => '最近一年',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    public static function getWhetherDisplaylist($item = null)
    {
        $data = array(
                'Y' => '显示',
                'N' => '隐藏',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getWhetherAllowlist($item = null)
    {
        $data = array(
                'Y' => '允许',
                'N' => '禁止',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getWeixinMemberStatuslist($item = null)
    {
        $data = array(
                'Y' => '正常',
                'N' => '取消关注',
                'F' => '违禁用户',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    public static function getAlipayOrderStatusList($item = null)
    {
        $data = array(
                'W' => '待付款',
                'Y' => '已付款',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    public static function getFormTypeList($item = null)
    {
        $data = array(
                'text' => '单行文本',
                'textarea' => '多行文本',
                'radio' => '单选',
                'checkbox' => '多选',
                'hidden' => '隐藏文本',
                'password' => '密码文本',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getFieldRequiredList($item = null)
    {
        $data = array(
                'Y' => '必填',
                'N' => '选填',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    public static function getWeixinAdvancedInterfaceList($item = null)
    {
        $data = array(
                'Y' => '高级接口',
                'N' => '普通接口',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getShopDishStatusList($item = null)
    {
        $data = array(
                'Y' => '上架',
                'N' => '下架',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getShopOrderScopeList($item = null)
    {
        $data = array(
                'thisWeek' => '本周',
                'lastWeek' => '上周',
                'thisMonth' => '本月',
                'lastMonth' => '上月',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    /**
     * 订单状态，分三个阶段
     * 0~9　商家派送中
     * 10~19    商家派送中
     * 20~  用户确认
     * @param null $item
     * @return array|string
     */
    public static function getShopOrderStatusList($item = null)
    {
        $data = array(
                '0' => '未受理',
                '1' => '已受理',
                '2' => '已取消',
                '9' => '催单中',
                '10' => '派送中',
                '20' => '已完成',
                '21' => '自动完成',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getMaterialUrlList($item = null)
    {
        $data = array(
                '/weixin/default/index' => '首页',
                '/weixin/product/index' => '菜品',
                '/weixin/profile/index' => '会员中心',
            #'{DINGZUO}' => '订座',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    public static function getMenuUrlList($item = null)
    {
        $data = array(
                '/weixin/default/index' => '首页',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    public static function getNotifyCustomerMethodList($item = null)
    {
        $data = array(
                '' => '不通知',
                'sms' => '短信通知',
                'weixin' => '微信模板消息',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getPushMethodList($item = null)
    {
        $data = array(
                'sms' => '短信通知',
                'printer' => '云打印机',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getPushDeviceList($item = null)
    {
        $data = array(
                'feie' => '飞鹅打印机',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function getMenuFunctionList($item = null)
    {
        $data = array(
                '{DINGDAN}' => '我的订单',
                '{CUIDAN}' => '催单',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }


    public static function getPaidStatusList($item = null)
    {
        $data = array(
                'Y' => '已支付',
                'N' => '未支付',
                'F' => '支付失败',
        );
        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    /**
     * 获取定义字段的名称
     * @param $array
     * @param $type
     * @return string
     */
    public static function getDefineFieldName($array, $type)
    {
        if (is_array($type)) {

            $r = array();
            foreach ($type as $key => $value) {
                $r[] = ($array && isset($array[$value])) ? $array[$value] : $type . '未定义';
            }
            $result = implode('、', $r);

        } else {
            $result = ($array && isset($array[$type])) ? $array[$type] : $type . '未定义';
        }

        return $result;
    }


    /*生成随机字符串*/
    static function genRandomString($len)
    {
        $chars = array(
                "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
                "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
                "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
                "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
                "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
                "3", "4", "5", "6", "7", "8", "9", "!", "@", "#", "$",
                "%", "^", "&", "*", "(", ")", "-", "_", "[", "]", "{",
                "}", "~", "`", "+", "=", ",", ".", ":", ";", "/", "?"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars); // 将数组打乱
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }


    //年份列表
    //@para, array
    static function getYearList()
    {
        $list = array();
        for ($i = 1940; $i < 2014; $i++) {
            $list[] = $i;
        }

        return $list;
    }


    /*获取文件类型
     *
     * @return 数据库枚举类型
     */
    public static function getFileTypeEnum($file)
    {
        $file = pathinfo($file);
        $enum = '';
        $file['extension'] = strtolower($file['extension']);
        if ($file['extension'] == 'wmv' || $file['extension'] == 'flv' || $file['extension'] == 'mp4')
            $enum = 'V';
        elseif ($file['extension'] == 'jpg' || $file['extension'] == 'gif' || $file['extension'] == 'png')
            $enum = 'I';
        else
            $enum = 'UNKNOWN';

        return $enum;
    }


    /**
     * 获取文件夹
     * @param $dir
     * @return array
     */
    public static function getDocumentList($dir)
    {
        $doc_arr = array();

        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != "..") {
                        array_push($doc_arr, $file);
                        //echo "<a href=file/".$file.">".$file."</a><br>";
                    }
                }
                closedir($dh);
            }
        }

        return $doc_arr;
    }

    /**
     * 字符串切割
     * @param $string
     * @param $length
     * @param string $etc
     * @param bool $filler 填充物，默认为中文空格
     * @return string
     */
    public static function strcut($string, $length, $etc = '...', $filler = false)
    {
        $temp_length = $length;
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++) {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')) {
                if ($length < 1.0) {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
                $temp_length -= 1;

            } else {
                $result .= substr($string, $i, 1);
                $length -= 0.5;

                $temp_length -= 0.5;
            }
        }

        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');

        if ($i < $strlen) {
            $result .= $etc;
        } else {
            if ($filler) {
                $result .= str_repeat('　', $temp_length);
            }
        }
        return $result;
    }


    /**
     * 将内容进行UNICODE编码，编码后的内容格式：YOKA\u738b （原始：YOKA王）
     * @param $name
     * @return string
     */
    public static function unicode_encode($name)
    {
        $name = iconv('UTF-8', 'UCS-2', $name);
        $len = strlen($name);
        $str = '';
        for ($i = 0; $i < $len - 1; $i = $i + 2) {
            $c = $name[$i];
            $c2 = $name[$i + 1];
            if (ord($c) > 0) { // 两个字节的文字
                $str .= '%u' . base_convert(ord($c), 10, 16) . base_convert(ord($c2), 10, 16);
            } else {
                $str .= $c2;
            }
        }
        return $str;
    }

    /**
     * 将UNICODE编码后的内容进行解码，编码后的内容格式：YOKA\u738b （原始：YOKA王）
     * @param $name
     * @return string
     */
    public static function unicode_decode($name)
    {
        // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
        $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
        preg_match_all($pattern, $name, $matches);
        if (!empty($matches)) {
            $name = '';
            for ($j = 0; $j < count($matches[0]); $j++) {
                $str = $matches[0][$j];
                if (strpos($str, '\%u') === 0) {
                    $code = base_convert(substr($str, 2, 2), 16, 10);
                    $code2 = base_convert(substr($str, 4), 16, 10);
                    $c = chr($code) . chr($code2);
                    $c = iconv('UCS-2', 'UTF-8', $c);
                    $name .= $c;
                } else {
                    $name .= $str;
                }
            }
        }
        return $name;
    }

    /**
     * 获取浏览器信息
     * @return string
     */
    public static function getBrowser()
    {
        if (stripos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {
            $browser = 'Chrome';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {
            $browser = 'Firefox';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'Safari')) {
            $browser = 'Safari';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE 13.0')) {
            $browser = 'IE13.0';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE 12.0')) {
            $browser = 'IE12.0';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE 11.0')) {
            $browser = 'IE11.0';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE 10.0')) {
            $browser = 'IE10.0';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.0')) {
            $browser = 'IE9.0';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0')) {
            $browser = 'IE8.0';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0')) {
            $browser = 'IE7.0';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0')) {
            $browser = 'IE6.0';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'iphone') || stripos($_SERVER['HTTP_USER_AGENT'], 'ipod')) {
            $browser = 'iPhone';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'ipad')) {
            $browser = 'iPad';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'android')) {
            $browser = 'Android';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'NetCaptor')) {
            $browser = 'NetCaptor';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'Netscape')) {
            $browser = 'Netscape';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'Lynx')) {
            $browser = 'Lynx';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'Opera')) {
            $browser = 'Opera';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'Maxthon')) {
            $browser = 'Maxthon';
        } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'windows')) {
            $browser = 'Windows';
        } else {
            $browser = 'other';
        }
        return $browser;
    }

    /**
     * 支付类型的状态
     * @param null $item
     * @return array|string
     */
    public static function getPaymentStatusList($item = null)
    {
        $data = array(
                'Y' => '开通',
                'N' => '关闭',
        );

        if ($item) {
            $result = self::getDefineFieldName($data, $item);
        } else {
            $result = $data;
        }
        return $result;
    }

    /**
     * IP匹配
     * @param $limit_ip 　必须为 *.*.*.*　完成格式
     * @param null $match_ip 　为空则取用户IP
     * @return bool
     */
    public static function ipMatch($limit_ip, $match_ip = null)
    {
        $result = true;

        $match_ip = $match_ip ? $match_ip : Yii::app()->request->userHostAddress;
        $limits = explode('.', $limit_ip);
        $matchs = explode('.', $match_ip);
        if (count($limits) != count($matchs)) {
            $result = false;
        } else {

            foreach ($limits as $key => $value) {
                if ($value == '*') {
                    $match = true;
                } else if ($limits[$key] == $matchs[$key]) {
                    $match = true;
                } else {
                    $match = false;
                }

                if (!$match) {
                    $result = false;
                }
            }
        }
        return $result;
    }


    /**
     * JSON encode 时将汉字由ASCII转换为汉字
     * @param $data
     * @return string
     */
    public static function jsonEncode($data)
    {
        if (phpversion() <= 5.3) {
            array_walk_recursive(
                    $data,
                    function (&$item, $key) {
                        if (is_string($item)) $item = mb_encode_numericentity($item, array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
                    }
            );
            $result = mb_decode_numericentity(json_encode($data), array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
        } else {
            $result = json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        return $result;

    }
}