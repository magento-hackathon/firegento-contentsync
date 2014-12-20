firegento-contentsync
=====================

Share content between Magento installations through the file system (i.e. via Git or SVN)

This Magento module allows exporting and importing of content (for example CMS pages and blocks)
to the file system.

Usage
---

``` sh
  php shell/contentsync.php [options]
```
Options:

- --import --> Import all data from the secondary storage
- --export --> Export all data to the secondary storage



Status
=====================
unstable

This project isn't maintained any more as we didn't manage to get it stable with the current approach and available time. I know of the following other solutions:

- http://magerun.net/harrisstreet-impex-for-magento/ - an extension for n98-magerun (which you should be using anyway if you are a Magento developer)
- http://mageflow.com/ - I saw a demo about that at Meet Magento NY, which made a good impression.
- http://mageploy.com/ - no personal experience.
- https://genmato.com/setup-script-versioning - no personal experience, link broken ATM
