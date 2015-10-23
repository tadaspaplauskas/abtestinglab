websiteID = {{ $website->id }};
<?php
echo 'abtlData = ' . json_encode($tests, JSON_UNESCAPED_SLASHES) . ';';

//js dependency
require(public_path('abtl_assets/js/jquery.min.js'));
require(public_path('abtl_assets/js/master.js'));

//abtl meat
require(public_path('abtl_assets/js/visitor.js'));
