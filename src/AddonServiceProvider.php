<?php

namespace Backpack\LanguageManager;

use Backpack\LanguageManager\AutomaticServiceProvider;
use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider;

    protected $vendorName = 'backpack';
    protected $packageName = 'language-manager';
    protected $commands = [];
}
