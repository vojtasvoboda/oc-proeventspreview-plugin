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
            'name' => 'ProEvents Preview',
            'description' => 'Backend calendar preview for ProEvents plugin.',
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
                    'label' => 'Overview',
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
                'label' => 'ProEvents Calendar',
                'description' => 'Manage ProEvents preview calendar.',
                'icon' => 'icon-calendar',
                'class' => 'VojtaSvoboda\ProEventsPreview\Models\Settings',
                'order' => 200,
            ]
        ];
    }
}
