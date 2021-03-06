<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit16368032b2572ba506086efeced66ebb
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Wythe\\' => 6,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Wythe\\' => 
        array (
            0 => __DIR__ . '/../..' . '/wythe',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit16368032b2572ba506086efeced66ebb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit16368032b2572ba506086efeced66ebb::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
