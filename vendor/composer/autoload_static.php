<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4f108693905659dc2f65984b25cbc709
{
    public static $prefixLengthsPsr4 = array (
        'B' => 
        array (
            'Battleships\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Battleships\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/Battleships',
        ),
    );

    public static $classMap = array (
        'Battleships\\Classes\\Battleship' => __DIR__ . '/../..' . '/app/Battleships/Classes/Battleship.php',
        'Battleships\\Classes\\GameEngine' => __DIR__ . '/../..' . '/app/Battleships/Classes/GameEngine.php',
        'Battleships\\Classes\\Grid' => __DIR__ . '/../..' . '/app/Battleships/Classes/Grid.php',
        'Battleships\\Classes\\Layout' => __DIR__ . '/../..' . '/app/Battleships/Classes/Layout.php',
        'Battleships\\Classes\\Ship' => __DIR__ . '/../..' . '/app/Battleships/Classes/Ship.php',
        'Battleships\\Classes\\Test' => __DIR__ . '/../..' . '/app/Battleships/Classes/Test.php',
        'Battleships\\Controllers\\CliController' => __DIR__ . '/../..' . '/app/Battleships/Controllers/CliController.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4f108693905659dc2f65984b25cbc709::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4f108693905659dc2f65984b25cbc709::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4f108693905659dc2f65984b25cbc709::$classMap;

        }, null, ClassLoader::class);
    }
}