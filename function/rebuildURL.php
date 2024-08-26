<?php
function rebuild_url($params = []) {
    // 取得當前的完整網址（包含查詢字串）
    $current_url = $_SERVER['REQUEST_URI'];

    // 解析網址，並分離出查詢字串
    $parsed_url = parse_url($current_url);

    // 將查詢字串解析為關聯陣列
    $query_params = [];
    if (isset($parsed_url['query'])) {
        parse_str($parsed_url['query'], $query_params);
    }

    // 遍歷傳入的參數陣列，設定或修改對應的GET參數
    foreach ($params as $paramName => $paramValue) {
        if (is_array($paramValue)) {
            // 如果值是陣列，遍歷陣列中的每一個元素
            foreach ($paramValue as $key => $value) {
                if ($value === null) {
                    // 如果值為 null，則從查詢字串中移除該參數
                    unset($query_params[$paramName][$key]);
                } else {
                    // 否則，設置或更新該參數
                    $query_params[$paramName][$key] = $value;
                }
            }
        } elseif ($paramValue === null) {
            // 如果值為 null，則從查詢字串中移除該參數
            unset($query_params[$paramName]);
        } else {
            // 否則，設置或更新該參數
            $query_params[$paramName] = $paramValue;
        }
    }

    // 重新構建查詢字串
    $new_query_string = http_build_query($query_params);

    // 組合成新的 URL
    return $parsed_url['path'] . '?' . $new_query_string;
}
?>
