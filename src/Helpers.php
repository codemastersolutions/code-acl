<?php

if (! function_exists('per_page')) {
    function per_page($codeAclMetaData = []): int
    {
        if (empty($codeAclMetaData)) {
            return 0;
        }

        $items = $codeAclMetaData['pagination']['per_page'];

        return is_int($items) ? ((int) $items > 0 ? (int) $items : 0 ): 0;
    }
}
