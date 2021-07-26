<?php
namespace Taro\Libs\IOInterface\Format;

class ColorScheme
{
    public static $foregroundColors = [
        'black'=>'30',
        'red'=>'31',
        'green'=>'32',
        'blue'=>'34',
        'cyan'=>'36',
        'yellow'=>'1;33',
        'white'=>'1;37',
    ];

    public static $backgroundColors = [
        'black'=>'40',
        'red'=>'41',
        'green'=>'42',
        'blue'=>'44',
        'cyan'=>'46',
        'yellow'=>'1;43',
        'white'=>'1;47',
    ];

    public static $styles = [
        'normal'=>'0',
        'bold'=>'1',
        'underlined'=>'4',
        'blinking'=>'5',
        'reverse video'=>'7',
    ];

    private static function getColorTextCode($colorText)
    {
        if(isset(ColorScheme::$foregroundColors[$colorText])) {
            return ColorScheme::$foregroundColors[$colorText];
        }
        return null;
    }

    private static function getColorBgCode($colorBgText)
    {
        if(isset(ColorScheme::$backgroundColors[$colorBgText])) {
            return ColorScheme::$backgroundColors[$colorBgText];
        }
        return null;
    }

    private static function getStyleCode($styleText)
    {
        if(isset(ColorScheme::$styles[$styleText])) {
            return ColorScheme::$styles[$styleText];
        }
        return null;
    }


    public static function encodeText($text, $foreground = null, $background = null, $style = null):string
    {
        $codes = [];
        if($foreground !== null) {
            $codes[] = self::getColorTextCode($foreground);
        }
        if($background !== null) {
            $codes[] = self::getColorBgCode($background);
        }
        if($style !== null) {
            $codes[] = self::getStyleCode($style);
        }

        $codes = array_filter($codes, function($code) {
            return $code !== null;
        });

        if(!empty($codes)) {
            $escapeCodes = implode(';', $codes);
            return "\033[${escapeCodes}m${text}\033[0m";
        }

        return $text;
    }

}