<?php
//此程式用於蒐集function函式以方便頁面調用。


/*
截取字串中的前幾個文字回傳
($text:欲截斷的文字；$num:欲取的字元數)。
*/
function getLeftChar($text, $num)
{
    $textLength = mb_strlen($text, "UTF-8");
    $textLength > $num ? $word = "..." : $word = "";
    return mb_substr($text, 0, $num, "UTF-8") . $word;
}


/*
將上架狀態的原始狀態字串移除
*/
function statusStrRemoveJoe($text)
{
    $search = ["&status=all", "&status=on", "&status=off"];
    $replace = ["", "", ""];
    $newText = str_replace($search, $replace, $text);
    return $newText;
}


/*先取名為超級模糊搜尋*/
function superSearch($text)
{
    // 使用 mb_str_split() 將字串正確地拆分為字符數組
    $characters = mb_str_split($text, 1, 'UTF-8');
    // 用 '%' 將字符數組連接起來
    $result = '%' . implode('%', $characters) . '%';
    return $result;
}
