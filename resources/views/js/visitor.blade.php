<?php
//js dependency
require(public_path('abtl_assets/js/jquery.min.js'));

echo 'var abtlData = ' . json_encode($tests, JSON_UNESCAPED_SLASHES) . ';';
?>
var websiteID = {{ $website->id }};
var abtlUrl = '{{ url('/') }}';

var abtl = jQuery.noConflict(true);
<?php
require(public_path('abtl_assets/js/master_functions.js'));

//abtl meat
require(public_path('abtl_assets/js/visitor.js'));
