<?php
namespace common\helpers;
/**
 * 
 */
class CRC16
{
	/**
	 * 
	 */
	public static function hex($str)
	{
		$str = self::test($str);
		$num = self::crc16($str, 0x8005, 0xffff, 0, true, true);
		//转换成16位整数时，选择大端序，在字节顺序上符合人们的一般习惯，即高位在前(在字节流的前面)。
		$b=pack('n',$num);
		//转换成16进制串时，一次性吃进半个字节，所以这里要解包4次，
		//一般人的阅读习惯，16进制表示的字符串都是高位（半字节）在前，所以格式符选H而不选h
		$s = unpack('H4s',$b)['s'];
		return substr($s,2,2).substr($s,0,2);
	}
	public static function test($sendStr){
	    $sendStrArray = str_split(str_replace(' ', '', $sendStr), 2);  // 将16进制数据转换成两个一组的数组
	    $hex = "";
	    for ($j = 0; $j < count($sendStrArray); $j++) {
	        $hex .= chr(hexdec($sendStrArray[$j]));
	    }
	    return  $hex;
	}
	    /**
	 * 将一个字符按比特位进行反转 eg: 65 (01000001) --> 130(10000010)
	 * @param $char
	 * @return $char
	 */
	public static function reverseChar($char) {
	    $byte = ord($char);
	    $tmp = 0;
	    for ($i = 0; $i < 8; ++$i) {
	        if ($byte & (1 << $i)) {
	            $tmp |= (1 << (7 - $i));
	        }
	    }
	    return chr($tmp);
	}
	/**
	 * 将一个字节流按比特位反转 eg: 'AB'(01000001 01000010)  --> '\x42\x82'(01000010 10000010)
	 * @param $str
	 */
	public static function reverseString($str) {
	    $m = 0;
	    $n = strlen($str) - 1;
	    while ($m <= $n) {
	        if ($m == $n) {
	            $str[$m] = self::reverseChar($str[$m]);
	            break;
	        }
	        $ord1 = self::reverseChar($str[$m]);
	        $ord2 = self::reverseChar($str[$n]);
	        $str[$m] = $ord2;
	        $str[$n] = $ord1;
	        $m++;
	        $n--;
	    }
	    return $str;
	}
	/**
	 * @param string $str 待校验字符串
	 * @param int $polynomial 二项式
	 * @param int $initValue 初始值
	 * @param int $xOrValue 输出结果前异或的值
	 * @param bool $inputReverse 输入字符串是否每个字节按比特位反转
	 * @param bool $outputReverse 输出是否整体按比特位反转
	 * @return int
	 */
	public static function crc16($str, $polynomial, $initValue, $xOrValue, $inputReverse = false, $outputReverse = false) {
	    $crc = $initValue;
	    for ($i = 0; $i < strlen($str); $i++) {
	        if ($inputReverse) {
	            // 输入数据每个字节按比特位逆转
	            $c = ord(self::reverseChar($str[$i]));
	        } else {
	            $c = ord($str[$i]);
	        }
	        $crc ^= ($c << 8);
	        for ($j = 0; $j < 8; ++$j) {
	            if ($crc & 0x8000) {
	                $crc = (($crc << 1) & 0xffff) ^ $polynomial;
	            } else {
	                $crc = ($crc << 1) & 0xffff;
	            }
	        }
	    }
	    if ($outputReverse) {
	        // 把低地址存低位，即采用小端法将整数转换为字符串
	        $ret = pack('cc', $crc & 0xff, ($crc >> 8) & 0xff);
	        // 输出结果按比特位逆转整个字符串
	        $ret = self::reverseString($ret);
	        // 再把结果按小端法重新转换成整数
	        $arr = unpack('vshort', $ret);
	        $crc = $arr['short'];
	    }
	    return  $xOrValue ^ $crc ;
	}
}