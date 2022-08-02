<?php

if (!function_exists('addonThumbnail')) {
    function addonThumbnail($name) {
        $path = join(DIRECTORY_SEPARATOR, ['Modules', $name, 'Resources', 'assets', 'thumbnail.png']);

        if (file_exists($path)) {
            return url($path);
        }

        return url(join(DIRECTORY_SEPARATOR, ['Modules', 'Addons', 'Resources', 'assets', 'thumbnail.png']));
    }
}