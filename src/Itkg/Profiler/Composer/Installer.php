<?php

namespace Itkg\Profiler\Composer;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Post install & update command to copy debug bar assets
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Installer
{
    const EXTRA_ASSET_DIR = 'itkg_debug_asset_dir';

    /**
     * Copy assets into you assets directory
     *
     * @param Event $event
     */
    public static function copyAssets(Event $event)
    {
        $extra = $event->getComposer()->getPackage()->getExtra();
        if (!isset($extra[self::EXTRA_ASSET_DIR])) {
            return;
        }

        $fs = new Filesystem();
        $fs->mirror(
            __DIR__ . '/../Resources/public',
            $extra[self::EXTRA_ASSET_DIR] . '/vendor/itkg/profiler/assets'
        );
    }
}
