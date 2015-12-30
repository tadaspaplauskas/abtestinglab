<?php
$publicUrl = url('abtl_assets/js/');
require(public_path('abtl_assets/js/jquery.min.js'));
require(public_path('abtl_assets/js/master_functions.js'));
?>

websiteID = {{ $website->id }};
abtlBackUrl = '{{ url(route('website.show', ['id' => $website->id])) }}';
abtlUrl = '{{ url() }}';

//entry point for token
if(window.location.hash.substring(0, 7) === '#token=')
{
    if(window.FileReader)
    {
        setLocal('token', window.location.hash.replace('#token=', ''));
        window.location = window.location.href.replace(window.location.hash, '');
    }
    else
    {
        alert('Your browser is too old to edit tests.');
        window.location = abtlBackUrl;
    }
}
//in all other cases
else
{
    $(document).ready(function() {
        //read manager token if its set
        token = getLocal('token');
        //request manager
        if (token !== null)
        {
           loadJS('{{ $publicUrl }}/manager.js');
        }
        else
        {
            <?php
            echo 'abtlData = ' . json_encode($tests, JSON_UNESCAPED_SLASHES) . ';';

            //abtl meat
            require(public_path('abtl_assets/js/visitor.js'));
            ?>
        }
    });
}