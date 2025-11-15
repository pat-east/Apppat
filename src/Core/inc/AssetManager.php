<?php

require_once('Asset.php');

class AssetManager {

    public const string LatestVersion = 'latest';

    /** @var array<string, Asset[]> */
    var array $registeredAssets;

    /** @var Asset[] */
    var array $usingAssets;

    public function __construct() {
        $this->registeredAssets = [];
        $this->usingAssets = [];
    }

    public function init(): void {
        $this->registerDefaultScriptsAndStyles();
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
        if (!isset($this->registeredAssets[$assetUid . ':' . $version])) {
            $this->registeredAssets[$assetUid . ':' . $version] = [];
        }

        $required = array_map(
                function ($assetUid) {
                    return str_contains($assetUid, ':') ? $assetUid : $assetUid . ':' . self::LatestVersion;
                }, $requiredAssetUids);

        $this->registeredAssets[$assetUid . ':' . $version][] = new Asset($assetUid, $scriptPath, $type, $required, $version);
    }

    /**
     * @param string $assetUid
     * @param string $version
     * @throws Exception
     */
    public function use(string $assetUid, string $version = self::LatestVersion): void {
        if (!isset($this->registeredAssets[$assetUid . ':' . $version])) {
            throw new Exception(sprintf('Asset not found [uid=%s]', $assetUid));
        }

        foreach ($this->registeredAssets[$assetUid . ':' . $version] as $asset) {
            foreach ($asset->requiredAssetUids as $assetUid) {
                $parts = explode(':', $assetUid);
                // TODO Eliminate potentially dangerous recursion
                $this->use($parts[0], $parts[1]);
            }
            $this->usingAssets[] = $asset;
        }
    }

    public function includeStyles(): void {
        $assets = array_filter($this->usingAssets, function ($asset) {
            return $asset->type == AssetType::Stylesheet;
        });
        foreach ($assets as $asset) {
            ?>
            <link rel="stylesheet" href="<?= $asset->path ?>"><?php
        }
    }

    public function includeScripts(): void {
        $assets = array_filter($this->usingAssets, function ($asset) {
            return $asset->type == AssetType::JavaScript;
        });
        foreach ($assets as $asset) {
            ?>
            <script src="<?= $asset->path ?>" nonce="<?= Csp::CreateNonce() ?>"></script><?php
        }
    }

    private function registerDefaultScriptsAndStyles(): void {
        $this->registerStyle(
                'kernux',
                '/public/assets/node_modules/@kern-ux/native/dist/kern.min.css');
        $this->registerStyle(
                'kernux',
                '/public/assets/node_modules/@kern-ux/native/dist/fonts/fira-sans.css');

        $this->registerScript(
                'jquery',
                '/public/assets/node_modules/jquery/dist/jquery.min.js');

        $this->registerScript(
                'bootstrap',
                '/public/assets/dist/bootstrap-4.1.3-dist/js/bootstrap.min.js',
                ['jquery'], '4.1.3');
        $this->registerStyle(
                'bootstrap',
                '/public/assets/dist/bootstrap-4.1.3-dist/css/bootstrap.min.css',
                [], '4.1.3');

        $this->registerScript(
                'bootstrap',
                '/public/assets/node_modules/bootstrap/dist/js/bootstrap.min.js');
        $this->registerStyle(
                'bootstrap',
                '/public/assets/node_modules/bootstrap/dist/css/bootstrap.min.css');

        $this->registerScript(
                'uikit',
                '/public/assets/node_modules/uikit/dist/js/uikit.min.js');
        $this->registerScript(
                'uikit',
                '/public/assets/node_modules/uikit/dist/js/uikit-icons.min.js');
        $this->registerStyle(
                'uikit',
                '/public/assets/node_modules/uikit/dist/css/uikit.min.css');
    }
}