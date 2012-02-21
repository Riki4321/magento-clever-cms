# Magento CMS pages as a tree structure

![Clever CMS](http://i.imgur.com/3NOIN.jpg)

## Features
* CMS pages as a tree structure, drag & drop pages
* Restrict CMS pages access to customer groups (if enabled)
* Create 301 permanent redirects for old urls (if enabled)
* Manage a global tree and a store view independent tree
* Frontend navigation

## Installation

### Magento CE 1.6+

Install with [modgit](https://github.com/jreinke/modgit):

    $ cd /path/to/magento
    $ modgit init
    $ modgit -e README.md -e CHANGELOG.md clone clever-cms https://github.com/jreinke/magento-clever-cms.git

or download package manually:

* Download latest version [here](https://github.com/jreinke/magento-clever-cms/downloads)
* Unzip in Magento root folder
* Clean cache

### Magento CE 1.4.x, 1.5.x

**Compatibility of master branch is not guaranteed with versions of Magento above!**
**Please download/clone 1.2 branch**

Install with [modgit](https://github.com/jreinke/modgit):

    $ cd /path/to/magento
    $ modgit init
    $ modgit -e README.md -e CHANGELOG.md clone clever-cms https://github.com/jreinke/magento-clever-cms.git --branch 1.2

or download package manually:

* Download 1.2.x version [here](https://github.com/jreinke/magento-clever-cms/tags)
* Unzip in Magento root folder
* Clean cache

## Full overview

I wrote an article on my blog for full extension overview, [click here](http://www.johannreinke.com/en/2012/01/10/magento-cms-pages-in-a-tree-structure/).
