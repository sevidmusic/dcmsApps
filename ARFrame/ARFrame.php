<?php
/**
 * CREDITS | NOTES:
 * <!-- AR.js by @jerome_etienne - github: https://github.com/jeromeetienne/ar.js - info: https://medium.com/arjs/augmented-reality-in-10-lines-of-html-4e193ea9fdbf -->
 * <!-- aframe.js CDN: "https://aframe.io/releases/0.8.0/aframe.min.js" | Note: It is preferable to serve locally, CDN should only be used if it is not possible to serve locally -->
 * <!-- ar.js CDN Link: "https://cdn.rawgit.com/jeromeetienne/AR.js/1.6.0/aframe/build/aframe-ar.js" | Note: It is preferable to serve locally, CDN should only be used if it is not possible to serve locally -->
 *
 * --- WORKING EXAMPLES --- @TODO: Create different views for each example so each can be demoed from the UI
 *
 * CUBE EXAMPLE:
 * <a-scene embedded arjs>
 * <!-- Ar Elements | Must be at least 1 defined, or there will be now ar elements to show. -->
 * <a-box position='0 0.5 0' material='opacity: 0.5;'></a-box>
 * <!-- REQUIRED! Define a camera which will move according to the marker position -->
 * <a-marker-camera preset='hiro'></a-marker-camera>
 * </a-scene>
 * <!-- NOTE: Took awhile to figure this out. This code does work, but not in chrome unless site uses https. Chrome has weird requirement that sites using camera serve only over https. Since local mamp is not configured for https, the local code wont work in chrome. @see https://medium.com/@aschmelyun/so-you-want-to-get-started-with-ar-js-41dd4fba5f81 | "This assumes that you have a (local or otherwise) development environment already set up and secured with an SSL certificate. Why SSL? Chrome requires all sites that use scripts calling for camera access to be delivered strictly over https." -->
 *
 * TEXT EXAMPLE:
 * <a-scene embedded arjs>
 * <a-text value="AR via ar.js and A-frame" material="color: red;"></a-text>
 * <!-- REQUIRED: Define a camera which will move according to the marker position -->
 * <a-marker-camera preset='hiro'></a-marker-camera>
 * </a-scene>
 *
 *
 * IMAGE EXAMPLE:
 * <a-scene embedded arjs>
 * <a-image src="<?php echo $logoImgPath;x ?>"></a-image>
 * <!-- REQUIRED: Define a camera which will move according to the marker position -->
 * <a-marker-camera preset='hiro'></a-marker-camera>
 * </a-scene>
 *
 */
$amInfo = new \Apps\AppManager\classes\AppInfo();
$logoImgPath = $amInfo->getDemoImgPath('ARFrame');
?>
<a-scene embedded arjs>
    <a-image src="<?php echo $logoImgPath; ?>"></a-image>
    <!-- REQUIRED: Define a camera which will move according to the marker position -->
    <a-marker-camera preset='hiro'></a-marker-camera>
    <!-- <a-marker-camera preset='custom' type="pattern" url="CoreValues::getSiteRootUrl()/UMNLOgoMarker.patt"></a-marker-camera> -->
</a-scene>
