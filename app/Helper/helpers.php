<?php

/** for side bar menu active */
function set_active($route)
{
    if (is_array($route)) {
        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active' : '';
}

function fileIcon($filename)
{
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    return match ($ext) {
        'pdf' => 'fa-file-pdf-o',
        'doc', 'docx' => 'fa-file-word-o',
        'xls', 'xlsx' => 'fa-file-excel-o',
        'png', 'jpg', 'jpeg' => 'fa-file-image-o',
        'zip', 'rar' => 'fa-file-archive-o',
        default => 'fa-file-o',
    };
}
