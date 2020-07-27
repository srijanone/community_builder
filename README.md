CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Installation
 * Configuration
 * How to use

Introduction
--------------
This module will provides a ready community platform with few basic entities such as community, 
Where User can quickly create community and start a platform, Also provides different pages as a start point.
This module enables a faster development for a community platform.

Installation:
--------------
* Strongly recommend installing this module using composer:
`composer require 'drupal/community_builder:1.0.x-dev'` 


Configuration
--------------
 * Admin will get and configuration menu in admin menu links named.
 `Community Builder`
 * This will provide you different pages links to Communities, posts etc.
 
 
How to use
--------------
* once enabled you will see `Community Builder` menu in top manu bar for admin
* From there you can go to `Add community page`, where you can create communities
* Content types Posts and Blog will be created.
* To enable posts listing on community pages please go to 
  `/admin/structure/block`
  (Administration > Structure > Block Layout)
  - Enable view block called `Community Posts: Post list` in content region
  - Set pages config to `{path_alias_to_community}/*`
  
  
 



