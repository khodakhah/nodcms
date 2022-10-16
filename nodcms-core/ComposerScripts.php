<?php
/*
 *  This file is part of NodCMS.
 *
 *  (c) Mojtaba Khodakhah <info@nodcms.com>
 *  https://nodcms.com
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 */

namespace NodCMS\Core;

use Composer\Script\Event;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * This is a copy of the Codeigniter\ComposerScripts class to copy code
 * that is used by Composer during installs and updates
 * to move files to locations within the system folder so that end-users
 * do not need to use Composer to install a package, but can simply
 * download.
 */
final class ComposerScripts
{
    private const CMD_COLOR_ERROR = "\e[0;31;40m";
    private const CMD_COLOR_SUCCESS = "\e[1;32;40m";
    private const CMD_COLOR_WARNING = "\e[1;33;40m";
    private const CMD_COLOR_CODE = "\e[1;37;42m";
    private const CMD_COLOR_END = "\e[0m";

    /**
     * CodeIgniter core directory
     *
     * @var string
     */
    private static $basePath = __DIR__ . '/../system/';

    /**
     * Direct dependencies of CodeIgniter to copy
     * contents to `/system/`.
     *
     * @var array<string, array<string, string>>
     */
    private static $dependencies = [
        'codeigniter' => [
            'from' => __DIR__ . '/../vendor/codeigniter4/framework/system/',
            'to'   => __DIR__ . '/../system/',
        ],
        'kint-src' => [
            'from' => __DIR__ . '/../vendor/kint-php/kint/src/',
            'to'   => __DIR__ . '/../system/ThirdParty/Kint/',
        ],
        'kint-resources' => [
            'from' => __DIR__ . '/../vendor/kint-php/kint/resources/',
            'to'   => __DIR__ . '/../system/ThirdParty/Kint/resources/',
        ],
        'escaper' => [
            'from' => __DIR__ . '/../vendor/laminas/laminas-escaper/src/',
            'to'   => __DIR__ . '/../system/ThirdParty/Escaper/',
        ],
        'psr-log' => [
            'from' => __DIR__ . '/../vendor/psr/log/Psr/Log/',
            'to'   => __DIR__ . '/../system/ThirdParty/PSR/Log/',
        ],
        'translations' => [
            'from' => __DIR__ . '/../vendor/codeigniter4/translations/Language/',
            'to'   => __DIR__ . '/../system/Language/',
            'pattern' => "/^[a-z]{2}(\-[A-Z]{2})?$/",
        ],
    ];

    /**
     * This static method is called by Composer after every update event,
     * i.e., `composer install`, `composer update`, `composer remove`.
     */
    public static function postUpdate()
    {
        // Remove directory if it's exists
        if (is_dir(self::$basePath)) {
            self::recursiveDelete(self::$basePath);
            rmdir(self::$basePath);
        }

        foreach (self::$dependencies as $dependency) {
            if (!key_exists("pattern", $dependency)) {
                self::recursiveMirror($dependency['from'], $dependency['to']);
                continue;
            }
            foreach (scandir($dependency['from']) as $content) {
                if (preg_match($dependency['pattern'], $content)) {
                    self::recursiveMirror($dependency['from']."$content/", $dependency['to']."$content/");
                }
            }
        }

        self::copyKintInitFiles();
        self::recursiveDelete(self::$dependencies['psr-log']['to'] . 'Test/');
    }

    /**
     * Builds and saves the production environment
     *
     * @param Event $event
     */
    public static function setEnv(Event $event)
    {
        if (empty($event->getArguments())) {
            echo self::CMD_COLOR_ERROR . "No Base URL given! " . self::CMD_COLOR_END . "\n" .
                "Please enter your url like bellow:\n" .
                self::CMD_COLOR_CODE . "composer env-production YOUR_URL" . self::CMD_COLOR_END . "\n";
            return;
        }

        $url = $event->getArguments()[0];

        $source = ".env.production";
        $destination = ".env";

        $sourceFile = fopen($source, "r") or die(self::CMD_COLOR_ERROR . "Unable to open $source file!" . self::CMD_COLOR_END);
        $content = fread($sourceFile, filesize($source));
        fclose($sourceFile);

        $content = str_replace("{{url}}", $url, $content);
        $destinationFile = fopen($destination, "w") or die(self::CMD_COLOR_ERROR . "Unable to create $destination file!" . self::CMD_COLOR_END);
        fwrite($destinationFile, $content);
        fclose($destinationFile);

        echo self::CMD_COLOR_SUCCESS . "Success!" . self::CMD_COLOR_END . "\n" .
            "Your production environment has been generated.\n";
    }

    /**
     * Recursively remove the contents of the previous `system/ThirdParty`.
     */
    private static function recursiveDelete(string $directory): void
    {
        if (! is_dir($directory)) {
            echo sprintf('Cannot recursively delete "%s" as it does not exist.', $directory);
        }

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(rtrim($directory, '\\/'), FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        ) as $file) {
            $path = $file->getPathname();

            if ($file->isDir()) {
                @rmdir($path);
            } else {
                @unlink($path);
            }
        }
    }

    /**
     * Recursively copy the files and directories of the origin directory
     * into the target directory, i.e. "mirror" its contents.
     */
    private static function recursiveMirror(string $originDir, string $targetDir): void
    {
        $originDir = rtrim($originDir, '\\/');
        $targetDir = rtrim($targetDir, '\\/');

        if (! is_dir($originDir)) {
            echo sprintf('The origin directory "%s" was not found.', $originDir);

            exit(1);
        }

        if (is_dir($targetDir)) {
            echo sprintf('The target directory "%s" is existing. Run %s::recursiveDelete(\'%s\') first.', $targetDir, self::class, $targetDir);

            exit(1);
        }

        @mkdir($targetDir, 0755, true);

        $dirLen = strlen($originDir);

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($originDir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        ) as $file) {
            $origin = $file->getPathname();
            $target = $targetDir . substr($origin, $dirLen);

            if ($file->isDir()) {
                @mkdir($target, 0755);
            } else {
                @copy($origin, $target);
            }
        }
    }

    /**
     * Copy Kint's init files into `system/ThirdParty/Kint/`
     */
    private static function copyKintInitFiles(): void
    {
        $originDir = self::$dependencies['kint-src']['from'] . '../';
        $targetDir = self::$dependencies['kint-src']['to'];

        foreach (['init.php', 'init_helpers.php'] as $kintInit) {
            @copy($originDir . $kintInit, $targetDir . $kintInit);
        }
    }
}
