<?php

// Dashboard
Breadcrumbs::register('dashboard', function($breadcrumbs)
{
    $breadcrumbs->push('Dashboard', route('dashboard'));
});

// Dashboard > Websites
Breadcrumbs::register('websites', function($breadcrumbs)
{
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Websites', route('website.index'));
});

// Dashboard > Websites > [Website]
Breadcrumbs::register('website', function($breadcrumbs, $website = null)
{
    $breadcrumbs->parent('websites');
    
    if ($website !== null)
        $breadcrumbs->push($website->title, route('website.show', $website->id));
    else
        $breadcrumbs->push('New website');
});

// NOT NEEDED Dashboard > Websites > [Website] > Tests
/*Breadcrumbs::register('tests', function($breadcrumbs, $website)
{
    $breadcrumbs->parent('website', $website->title);
    $breadcrumbs->push('Tests');
});*/

// Dashboard > Websites > [Website] > Archived tests
Breadcrumbs::register('archived_tests', function($breadcrumbs, $website)
{
    $breadcrumbs->parent('website', $website);
    $breadcrumbs->push('Archived tests');
});

// Dashboard > Websites > [Website] > [Test]
Breadcrumbs::register('test', function($breadcrumbs, $test)
{
    $breadcrumbs->parent('website', $test->website);
    $breadcrumbs->push($test->title);
});