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


    public static function encodeText($text, $foreground = null, $background = null, $style = null):string
    {
        $codes = [];
        if($foreground !== null) {
            $codes[] = $foreground;
        }
        if($background !== null) {
            $codes[] = $background;
        }
        if($style !== null) {
            $codes[] = $style;
        }
        if(!empty($codes)) {
            $escapeCodes = implode(';', $codes);
            return "\033[${escapeCodes}m ${text} \033[0m";
        }

        return $text;
    }

}