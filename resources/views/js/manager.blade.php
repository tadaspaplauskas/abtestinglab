<?php
$publicUrl = protocolRelativeUrl('abtl_assets/js/');
require(public_path('abtl_assets/js/jquery.min.js'));
?>
var abtl = jQuery.noConflict(true);
<?php
require(public_path('abtl_assets/js/master_functions.js'));
?>

var websiteID = {{ $website->id }};
var abtlBackUrl = '{{ url(route('tests.manager.exit', ['id' => $website->id])) }}';
var abtlUrl = '{{ url('/') }}';

//entry point for token
if(window.location.hash.substring(0, 7) === '#token=')
{
    if(window.FileReader)
    {
        abtl.setLocal('token', window.location.hash.replace('#token=', ''));
        window.location = window.location.href.replace(window.location.hash, '');
    }
    else
    {
        alert('Sorry, Your browser is not compatible. We recommend using the latest version of Chrome.');
        window.location = abtlBackUrl;
    }
}
//in all other cases
else
{
    //read manager token if its set
    var token = abtl.getLocal('token');
    //request manager
    if (token !== null)
    {
       abtl.loadJS('{{ $publicUrl }}/manager.js');
    }
    else
    {
        <?php
        echo 'var abtlData = ' . json_encode($tests, JSON_UNESCAPED_SLASHES) . ';';

        //abtl meat
        require(public_path('abtl_assets/js/visitor.js'));
        ?>
    }
}