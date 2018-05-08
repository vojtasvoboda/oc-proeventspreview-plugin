<?php namespace VojtaSvoboda\ProEventsPreview;

use Backend;
use Event;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public $require = [
        'Radiantweb.Proevents',
    ];

    public function pluginDetails()
    {
        return [
            'name' => 'vojtasvoboda.proeventspreview::lang.plugin_name',
            'description' => 'vojtasvoboda.proeventspreview::lang.plugin_description',
            'author' => 'Vojta Svoboda',
            'icon' => 'icon-calendar-o',
        ];
    }

    public function boot()
    {
        // Override backend menu
        Event::listen('backend.menu.extendItems', function($manager)
        {
            $manager->addMainMenuItem('Radiantweb.Proevents', 'proevents', [
                'label' => 'radiantweb.proevents::lang.plugin.name',
                'url' => Backend::url('vojtasvoboda/proeventspreview/calendar'),
                'icon' => 'icon-calendar',
                'permissions' => ['radiantweb.proevents.*'],
                'order' => 500,
            ]);

            $manager->addSideMenuItems('Radiantweb.Proevents', 'proevents', [
                'calendar' => [
                    'label' => 'vojtasvoboda.proeventspreview::lang.calendar_label',
                    'url' => Backend::url('vojtasvoboda/proeventspreview/calendar'),
                    'icon' => 'icon-calendar',
                    'permissions' => ['radiantweb.proevents.*'],
                    'order' => 80,
                ],
            ]);
        });
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label' => 'vojtasvoboda.proeventspreview::lang.settings_label',
                'description' => 'vojtasvoboda.proeventspreview::lang.settings_description',
                'icon' => 'icon-calendar',
                'class' => 'VojtaSvoboda\ProEventsPreview\Models\Settings',
                'order' => 200,
            ]
        ];
    }
}
