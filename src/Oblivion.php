<?php

declare(strict_types=1);

namespace Oblivion;

class Oblivion
{
    public static function init($env)
    {
        $dir = __DIR__ . '/classes/';
        $dir = dir($dir);
        while ($arquivo = $dir->read()) {
            if ($arquivo == ".." || $arquivo == ".") {
            } else {
                include_once '' . __DIR__ . '/classes/' . $arquivo . '';
            }
        }
        $dir->close();
    }
}
