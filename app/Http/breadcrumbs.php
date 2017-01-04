<?php


/*
|------------------------------------------------------------------------
| Registering all breadcrumbs for the application
|------------------------------------------------------------------------
*/
// Home
Breadcrumbs::register('home', function($breadcrumbs)
{
  $breadcrumbs->push('Home', route('pages.index'));
});

// Home > Xprs Menu
Breadcrumbs::register('xprs.search', function($breadcrumbs)
{
  $breadcrumbs->parent('home');

  $breadcrumbs->push('Global Menu', route('items.search.xprs-menu.get'));
});

// Home > Xprs > Search
Breadcrumbs::register('xprs.single', function($breadcrumbs, $item )
{
  $breadcrumbs->parent('xprs.search');

  $breadcrumbs->push($item->itemable->name , route('items.xprs-menu.single.get', $item->itemable->slug));
});

// Home > Hot Deals
Breadcrumbs::register('hot_deals.search', function($breadcrumbs)
{
  $breadcrumbs->parent('home');

  $breadcrumbs->push('Today\'s Specials', route('items.hot_deals.get'));
});

// Home > Hot Deals > Search
Breadcrumbs::register('hot_deals.single', function($breadcrumbs, $item )
{
  $breadcrumbs->parent('hot_deals.search');

  $breadcrumbs->push($item->itemable->name , route('items.hot_deals.single.get', $item->itemable->slug));
});