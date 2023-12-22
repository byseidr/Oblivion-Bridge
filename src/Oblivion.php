<?php

declare(strict_types=1);

namespace Oblivion;

class Oblivion
{
    public static function init($env)
    {
        $dir = __DIR__ . '/functions/';
        $dir = dir($dir);
        while ($arquivo = $dir->read()) {
            if ($arquivo == ".." || $arquivo == ".") {
            } else {
                include '' . __DIR__ . '/functions/' . $arquivo . '';
            }
        }
        $dir->close();
    }
}
