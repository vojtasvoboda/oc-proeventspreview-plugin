# ProEvents Preview plugin for OctoberCMS

Provide backend preview calendar for [ProEvents](http://octobercms.com/plugin/radiantweb-proevents) plugin. 

Key features:

- quick overview of available and booked dates in visual way
- normal or **aggregated** view, see screenshots
- last stable version of [fullCalendar](https://fullcalendar.io/) component
- easily **extensible**!

Before use be sure you have [ProEvents](http://octobercms.com/plugin/radiantweb-proevents) installed.

## Settings

At `Backend > Settings > Misc > ProEvents Calendar` you can find calendar settings.

## Add your own calendar

For adding new calendar, copy file `/plugins/vojtasvoboda/proeventspreview/config/config.php` to 
`/config/vojtasvoboda/proeventspreview/config.php` and add new calendar section by coping any other section.

For example I took second calendar and made a copy to:

```
'mycalendar' => [
    'name' => 'My calendar',
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
```

If you want only change seetings of **fullCalendar**, create copy of file `/plugins/vojtasvoboda/proeventspreview/widgets/fullcalendar/assets/js/fullcalendar_normal.js` to 
`/plugins/acme/site/widgets/fullcalendar/assets/js/fullcalendar_normal.js` and change this path in config above.

Do you want to change whole logic of fetching data? Create copy of FullCalendar class and change it in config file.

All configured calendars will be loaded at backend Settings and you can select which one to show.
