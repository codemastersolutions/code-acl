<?php

if (! function_exists('per_page')) {
    function per_page($codeAclMetaData = []): int
    {
        if (empty($codeAclMetaData)) {
            return 0;
        }

        $items = $codeAclMetaData['pagination']['per_page'];

        if (!is_int($items)) {
            return 0;
        }

        return  (int) $items > 0 ? (int) $items : 0;
    }
}
