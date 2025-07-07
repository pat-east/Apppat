<?php

enum AssetType {
    case Stylesheet;
    case JavaScript;
}

class Asset {

    var string $assetUid;
    var string $path;
    var AssetType $type;

    /** @var array<string> */
    var array $requiredAssetUids;
    var string $version;

    /**
     * @param string $assetUid
     * @param string $path
     * @param string[] $requiredAssetUids
     * @param string $version
     */
    function __construct(string $assetUid, string $path, AssetType $type, array $requiredAssetUids, string $version) {
        $this->assetUid = $assetUid;
        $this->path = $path;
        $this->type = $type;
        $this->requiredAssetUids = $requiredAssetUids;
        $this->version = $version;
    }

}