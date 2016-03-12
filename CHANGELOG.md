# CHANGELOG for Ffuenf_DevTools

This file is used to list changes made in each version of Ffuenf_DevTools.

## 2.1.9 (March 12, 2016)

* update travis / MageTestStand
* [docs] minor updates
* fix helper
* re-organize menu / navigation
* fix date input

## 2.1.8 (February 25, 2016)

* update travis build-matrix
* [docs] copyright notice

## 2.1.7 (December 20, 2015)

* fix date input in backend configuration
* add date type config for resave-products shell script (created_at / updated_at)
* fix dependency to [Ffuenf_Common](https://github.com/ffuenf/Ffuenf_Common)
* code-style
* update travis build-matrix

## 2.1.6 (November 22, 2015)

* add uninstallation via [Ffuenf_MageTrashApp](https://github.com/ffuenf/Ffuenf_MageTrashApp)

## 2.1.5 (November 21, 2015)

* add dependency to [Ffuenf_Common](https://github.com/ffuenf/Ffuenf_Common)
* common extension structure
* add more tests
* add note about inherited extensions

## 2.1.4 (November 8, 2015)

* switch back to official builds for versions > 1.9.0.0 in travis tests
* notes about compatibility

## 2.1.3 (November 6, 2015)

* switch completely to OpenMage mirrors in travis tests
* cache helper in model
* code style
* relocate admin routes (compatibility to SUPEE-6788)

## 2.1.2 (November 4, 2015)

* add resave-products shell script (timeframe configurable via backend settings)
* fix wrong helpers
* fix compatibility to [SUPEE-6788](https://info2.magento.com/rs/318-XBX-392/images/SUPEE-6788-Technical%20Details.pdf?mkt_tok=3RkMMJWWfF9wsRolv6jPZKXonjHpfsX66uskXqOxlMI%2F0ER3fOvrPUfGjI4CSMFjI%2BSLDwEYGJlv6SgFSbHNMbhiwrgOUhM%3D)

## 2.1.1 (November 1, 2015)

* add more layout xml conditions (credits to [magehandles](https://github.com/aleron75/magehandles) by Alessandro Ronchi)

## 2.1.0 (October 30, 2015)

* add layout xml conditions (credits to [Aoe_LayoutConditions](https://github.com/fbrnc/Aoe_LayoutConditions) by Fabrizio Branca)
* conditionally enable headerbar in backend (and make css dependent)
* add test whether extension uses the old-style admin routing.

## 2.0.3 (October 29, 2015)

* update to notice of applied patches
* update to possibility of overriding email templates in your own Magento theme

## 2.0.2 (October 22, 2015)

* compatibility to [SUPEE-6788](https://info2.magento.com/rs/318-XBX-392/images/SUPEE-6788-Technical%20Details.pdf?mkt_tok=3RkMMJWWfF9wsRolv6jPZKXonjHpfsX66uskXqOxlMI%2F0ER3fOvrPUfGjI4CSMFjI%2BSLDwEYGJlv6SgFSbHNMbhiwrgOUhM%3D)

## 2.0.1 (July 18, 2015)

* add possibility to mass delete URL-Rewrites

## 2.0.0 (May 25, 2015)

* add environment header in backend (credits to [Magento-AdminHeaderTheme](https://github.com/Zookal/Magento-AdminHeaderTheme) by Cyrill Schumacher)
* add git infos to header in backend
* add setting for cutom admin theme
* add notice of applied patches (credits to [Philwinkle_AppliedPatches](https://github.com/philwinkle/Philwinkle_AppliedPatches) by Phillip Jackson)
* add possibility of overriding email templates in your own Magento theme (credits to [Yireo_EmailOverride](https://github.com/yireo/Yireo_EmailOverride) by Yireo)

## 1.0.1 (May 21, 2015)

* remove composer dependencies
* add codeclimate / scrutinizer-ci / travis

## 1.0.0 (January 8, 2015)

* initial public release
* add travis-ci
* add basic test
