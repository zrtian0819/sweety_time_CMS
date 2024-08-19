<?php
//此程式用於蒐集function函式以方便頁面調用。


/*
截取字串中的前幾個文字回傳
($text:欲截斷的文字；$num:欲取的字元數)。
*/
function getLeftChar($text, $num)
{
    return substr($text, 0, $num);
}
