<?php

return [

    /**
     * List of all available calendars.
     */
    'calendars' => [

        'fullcalendaraggregated' => [
            'name' => 'Fullcalendar Aggregated',
            'class' => 'VojtaSvoboda\ProEventsPreview\Widgets\FullCalendar',
            'assets' => [
                'css' => [
                    '/plugins/vojtasvoboda/proeventspreview/widgets/fullcalendar/assets/css/fullcalendar.css',
                ],
                'js' => [
                    '/plugins/vojtasvoboda/proeventspreview/widgets/fullcalendar/assets/js/fullcalendar.js',
                    '/plugins/vojtasvoboda/proeventspreview/widgets/fullcalendar/assets/js/fullcalendar_aggregated.js',
                ],
            ],
        ],

        'fullcalendarnormal' => [
            'name' => 'Fullcalendar Normal',
            'class' => 'VojtaSvoboda\ProEventsPreview\Widgets\FullCalendar',
            'assets' => [
                'css' => [
                    '/plugins/vojtasvoboda/proeventspreview/widgets/fullcalendar/assets/css/fullcalendar.css',
                ],
                'js' => [
                    '/plugins/vojtasvoboda/proeventspreview/widgets/fullcalendar/assets/js/fullcalendar.js',
                    '/plugins/vojtasvoboda/proeventspreview/widgets/fullcalendar/assets/js/fullcalendar_normal.js',
                ],
            ],
        ],
    ],
];
