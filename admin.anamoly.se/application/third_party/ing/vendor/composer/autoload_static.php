<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite0ff689e98fb605847dd7abd538335bd
{
    public static $files = array (
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        'a4ecaeafb8cfb009ad0e052c90355e98' => __DIR__ . '/..' . '/beberlei/assert/lib/Assert/functions.php',
        'e39a8b23c42d4e1452234d762b03835a' => __DIR__ . '/..' . '/ramsey/uuid/src/functions.php',
        '8c232a3106b024d31151594d94ad3f2c' => __DIR__ . '/..' . '/ingpsp/ing-php/src/Fallback.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Ctype\\' => 23,
            'Symfony\\Contracts\\Translation\\' => 30,
            'Symfony\\Component\\Translation\\' => 30,
        ),
        'R' => 
        array (
            'Ramsey\\Uuid\\' => 12,
        ),
        'G' => 
        array (
            'GingerPayments\\Payment\\' => 23,
        ),
        'D' => 
        array (
            'Dotenv\\' => 7,
        ),
        'A' => 
        array (
            'Assert\\' => 7,
            'Alcohol\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Symfony\\Contracts\\Translation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/translation-contracts',
        ),
        'Symfony\\Component\\Translation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/translation',
        ),
        'Ramsey\\Uuid\\' => 
        array (
            0 => __DIR__ . '/..' . '/ramsey/uuid/src',
        ),
        'GingerPayments\\Payment\\' => 
        array (
            0 => __DIR__ . '/..' . '/ingpsp/ing-php/src',
        ),
        'Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/phpdotenv/src',
        ),
        'Assert\\' => 
        array (
            0 => __DIR__ . '/..' . '/beberlei/assert/lib/Assert',
        ),
        'Alcohol\\' => 
        array (
            0 => __DIR__ . '/..' . '/alcohol/iso3166',
        ),
    );

    public static $fallbackDirsPsr4 = array (
        0 => __DIR__ . '/..' . '/nesbot/carbon/src',
    );

    public static $prefixesPsr0 = array (
        'V' => 
        array (
            'Verraes' => 
            array (
                0 => __DIR__ . '/..' . '/mathiasverraes/classfunctions/src',
            ),
        ),
        'U' => 
        array (
            'UpdateHelper\\' => 
            array (
                0 => __DIR__ . '/..' . '/kylekatarnls/update-helper/src',
            ),
        ),
        'I' => 
        array (
            'IsoCodes' => 
            array (
                0 => __DIR__ . '/..' . '/ronanguilloux/isocodes/src',
            ),
        ),
        'H' => 
        array (
            'Httpful' => 
            array (
                0 => __DIR__ . '/..' . '/nategood/httpful/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite0ff689e98fb605847dd7abd538335bd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite0ff689e98fb605847dd7abd538335bd::$prefixDirsPsr4;
            $loader->fallbackDirsPsr4 = ComposerStaticInite0ff689e98fb605847dd7abd538335bd::$fallbackDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInite0ff689e98fb605847dd7abd538335bd::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}