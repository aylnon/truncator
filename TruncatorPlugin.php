<?php
namespace Craft;

class TruncatorPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('Truncator');
    }

    public function getVersion()
    {
        return '0.0.1';
    }

    public function getDeveloper()
    {
        return 'Adrian Calton';
    }

    public function getDeveloperUrl()
    {
        return 'http://www.google.com';
    }

    public function getDescription()
    {
        return 'Truncates text while leaving formatting intact.';
    }
}