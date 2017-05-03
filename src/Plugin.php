<?php
namespace foobar\truncator;

use foobar\truncator\variables\Variable;
use craft\base\Plugin as BasePlugin;

class Plugin extends BasePlugin
{
    public function init()
    {
        parent::init();

        // Custom initialization code goes here...
    }

    public function defineTemplateComponent()
    {
        return Variable::class;
    }
}