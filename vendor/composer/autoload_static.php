<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1a9980739c89d8c3884c5553604e51b5
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'CRC\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'CRC\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1a9980739c89d8c3884c5553604e51b5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1a9980739c89d8c3884c5553604e51b5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1a9980739c89d8c3884c5553604e51b5::$classMap;

        }, null, ClassLoader::class);
    }
}