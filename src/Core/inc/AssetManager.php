<?php

require_once('Asset.php');

class AssetManager {

    public const string LatestVersion = 'latest';

    /** @var array<string, Asset[]> */
    var array $registeredAssets;

    /** @var Asset[]  */
    var array $usingAssets;

    function __construct() {
        $this->registeredAssets = [];
        $this->usingAssets = [];
    }

    public function init(): void {

    }

    /**
     * @param string $assetUid
     * @param string $version
     * @throws Exception
     */
    public function use(string $assetUid, string $version = self::LatestVersion): void {
        if(!isset($this->registeredAssets[$assetUid . ':' . $version])) {
            throw new Exception(sprintf('Asset not found [uid=%s]', $assetUid));
        }

        foreach($this->registeredAssets[$assetUid . ':' . $version] as $asset) {
            foreach($asset->requiredAssetUids as $assetUid) {
                $parts = explode(':', $assetUid);
                // TODO Eliminate potentially dangerous recursion
                $this->use($parts[0], $parts[1]);
            }
            $this->usingAssets[] = $asset;
        }
    }

    /**
     * @param string $assetUid
     * @param string $stylePath
     * @param array<string> $requiredAssetUids
     * @param string $version
     */
    public function registerStyle(string $assetUid, string $stylePath, array $requiredAssetUids = [], string $version = self::LatestVersion): void {
        $this->registerAsset($assetUid, $stylePath, AssetType::Stylesheet, $requiredAssetUids, $version);
    }

    /**
     * @param string $assetUid
     * @param string $scriptPath
     * @param array<string> $requiredAssetUids
     * @param string $version
     */
    public function registerScript(string $assetUid, string $scriptPath, array $requiredAssetUids = [], string $version = self::LatestVersion): void {
        $this->registerAsset($assetUid, $scriptPath, AssetType::JavaScript, $requiredAssetUids, $version);
    }

    public function registerAsset(string $assetUid, string $scriptPath, AssetType $type, $requiredAssetUids = [], string $version = self::LatestVersion): void {
        if(!isset($this->registeredAssets[$assetUid . ':' . $version])) {
            $this->registeredAssets[$assetUid . ':' . $version] = [];
        }

        $required = array_map(
            function($assetUid) {
                return str_contains($assetUid, ':') ? $assetUid : $assetUid . ':' . self::LatestVersion;
            }, $requiredAssetUids);

        $this->registeredAssets[$assetUid . ':' . $version][] = new Asset($assetUid, $scriptPath, $type, $required, $version);
    }

    public function includeStyles(): void {
        $assets = array_filter($this->usingAssets, function($asset) {
            return $asset->type == AssetType::Stylesheet;
        });
        foreach($assets as $asset) {
            ?><link rel="stylesheet" href="<?= $asset->path ?>"><?php
        }
    }

    public function includeScripts(): void {
        $assets = array_filter($this->usingAssets, function($asset) {
            return $asset->type == AssetType::JavaScript;
        });
        foreach($assets as $asset) {
            ?><script src="<?= $asset->path ?>"></script><?php
        }
    }

}