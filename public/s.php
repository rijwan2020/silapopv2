<?php
$target = '/home/penyuluhjabar/_file/upload/default/koperasi';
$shortcut = '/home/penyuluhjabar/v2/storage/app/public/profile';
symlink($target, $shortcut);

// $target = '/home/penyuluhjabar/v2/storage/app/public';
// $shortcut = '/home/penyuluhjabar/v2/public/storage';
// symlink($target, $shortcut);

echo __DIR__;

// rwxr-xr-x

?>