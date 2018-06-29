<?php namespace VojtaSvoboda\ProEventsPreview\Widgets;

use App;
use Backend\Classes\WidgetBase;
use Carbon\Carbon;
use Config;
use Lang;
use Input;
use October\Rain\Database\Collection;
use October\Rain\Exception\AjaxException;
use Radiantweb\Proevents\Models\Calendar;
use Radiantweb\Proevents\Models\GeneratedDate;
use Request;
use VojtaSvoboda\ProEventsPreview\Models\Settings;

class FullCalendar extends WidgetBase
{
    protected $defaultAlias = 'fullcalendar';

    public function widgetDetails()
    {
        return [
            'name' => 'Preview Calendar',
            'description' => 'Calendar preview of ProEvents events.',
        ];
    }

    public function render()
    {
        $this->addCss('/plugins/vojtasvoboda/proeventspreview/widgets/fullcalendar/assets/vendor/fullcalendar/css/fullcalendar.min.css');
        $this->addJs('/plugins/vojtasvoboda/proeventspreview/widgets/fullcalendar/assets/vendor/fullcalendar/js/fullcalendar.min.js');
        $this->addJs('/plugins/vojtasvoboda/proeventspreview/widgets/fullcalendar/assets/vendor/fullcalendar/js/lang-all.js');
        $this->addJs('/plugins/vojtasvoboda/proeventspreview/widgets/fullcalendar/assets/vendor/fullcalendar/js/moment.min.js');

        // load all calendars
        $params['calendars'] = Calendar::all();

        // get calendar config
        $defaultCalendar = Config::get('vojtasvoboda.proeventspreview::calendar.default', 'fullcalendaraggregated');
        $calendarType = Settings::get('type', $defaultCalendar);
        $calendarAssets = Config::get('vojtasvoboda.proeventspreview::calendars.' . $calendarType . '.assets');

        $params['assets_css'] = isset($calendarAssets['css']) ? $calendarAssets['css'] : [];
        $params['assets_js'] = isset($calendarAssets['js']) ? $calendarAssets['js'] : [];

        $params['settings']['firstDay'] = Settings::get('firstDay', 0);
        $params['settings']['timeFormat'] = Settings::get('timeFormat', 'HH:mm');

        return $this->makePartial('calendar', $params);
    }

    public function onGetMonthEvents()
    {
        $showPastEvents = Settings::get('showPastEvents', false);
        $timezone = config('cms.backendTimezone');
        $start = Carbon::createFromTimestamp(Request::get('start'), $timezone);
        $end = Carbon::createFromTimestamp(Request::get('end'), $timezone);
        $now = Carbon::now();
        $calendarView = Request::get('view', 'agendaDay');
        $type = Request::get('type');

        // don't load old events
        if (!$showPastEvents && $start < $now) {
            $start = $now;
        }

        // get calendar
        $calendar_id = $this->getCalendarId();
        if (!$calendar_id) {
            return json_encode([]);
        }

        // load events
        $eventsModel = new GeneratedDate();
        $events = $eventsModel->getFromToCalendarEvents($start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s'), "(calendar_id = '$calendar_id')");

        // hide events already started
        if (!$showPastEvents) {
            foreach ($events as $key => $event) {
                $date = $event->date;
                if ($date != $now->format('Y-m-d')) {
                    continue;
                }
                $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $event->sttime);
                if ($now > $startTime) {
                    $events->pull($key);
                }
            }
        }

        // aggregate items for month view
        if ($calendarView == 'month' && $type == 'aggregated') {
            $event_item = $this->getEventsAggregated($events);

        } else {
            $event_item = $this->getEventsAsCalendarEvents($events);
        }

        return json_encode($event_item);
    }

    /**
     * @param $events
     * @return array
     * @see https://v4-alpha.getbootstrap.com/utilities/colors/
     */
    private function getEventsAggregated($events)
    {
        $days = [];
        $colorAvailable = '#1ea362';
        $colorBooked = '#b1071f';

        foreach ($events as $event) {
            $date = $event->date;
            if (!isset($days[$date])) {
                $days[$date] = 0;
            }
            if ($event->status == 'available') {
                $days[$date]++;
            }
        }

        $timezone = config('cms.backendTimezone');

        $events = [];
        $index = 0;
        foreach ($days as $key => $day) {
            if ($day == 0) {
                $title = Lang::get('vojtasvoboda.proeventspreview::lang.fully_booked');

            } elseif ($day == 1) {
                $title = Lang::get('vojtasvoboda.proeventspreview::lang.last_available_place');

            } elseif ($day > 1 && $day < 5) {
                $title = $day . ' ' . Lang::get('vojtasvoboda.proeventspreview::lang.available_places_singular');

            } else {
                $title = $day . ' ' . Lang::get('vojtasvoboda.proeventspreview::lang.available_places_singular');
            }

            $events[] = [
                'id' => $index++,
                'title' => $title,
                'allDay' => true,
                'start' => Carbon::createFromFormat('Y-m-d H:i:s', $key . ' 09:00:00', $timezone)->toIso8601String(),
                'end' => Carbon::createFromFormat('Y-m-d H:i:s', $key . ' 17:00:00', $timezone)->toIso8601String(),
                'color' => $day > 0 ? $colorAvailable : $colorBooked,
                'url' => '#',
                'className' => 'larger ' . ($day > 0 ? 'available' : 'booked'),
            ];
        }

        return $events;
    }

    /**
     * @param $events
     * @return array
     * @see https://v4-alpha.getbootstrap.com/utilities/colors/
     */
    private function getEventsAsCalendarEvents($events)
    {
        $event_item  = [];
        $skip_events = [];
        $colorAvailable = '#1ea362';
        $colorBooked = '#b1071f';

        foreach ($events as $event) {
            $temp_event_array = [];
            $path = '';

            /*
             * If the event is daily and also an allday
             * then we loop and grab the last date of the same
             * event_id
             */
            if ($event->recur == 'daily' && $event->allday > 0) {
                /* get end date */
                foreach ($events as $levent) {
                    if ($levent->event_id == $event->event_id) {
                        $temp_event_array[] = $levent->date;
                        if ($levent->id != $event->id) {
                            /*
                             * add all days of this daily allday event
                             * to skip array
                             */
                            $skip_events[] = $levent->id;
                        }
                    }
                }
            }

            $temp_event_count = count($temp_event_array);

            /*
             * If the temp loop count is > 0
             * then we know we have an allday daily recurring
             * else proceed as normal
             */
            if ($temp_event_count > 0) {
                $end_date = $temp_event_array[$temp_event_count - 1];
            } else {
                $end_date = $event->date;
            }

            /*
             * If this event is not skipped, add it
             */
            if (!in_array($event->id, $skip_events)) {
                $available = $event->status == 'available';
                $event_item[] = [
                    'id' => $event->id,
                    'title' => $event->title,
                    'allDay' => $event->allday,
                    'start' => $event->date . 'T' . $event->sttime . '+00:00',
                    'end' => $end_date . 'T' . $event->entime . '+00:00',
                    'color' => $available ? $colorAvailable : $colorBooked,
                    'url' => $path,
                    'description' => $event->excerpt,
                    'className' => $available ? 'available' : 'booked',
                ];
            }
        }

        return $event_item;
    }

    /**
     * Get calendar ID.
     *
     * @return bool
     */
    private function getCalendarId()
    {
        $calendarSlug = Request::get('calendar');
        $calendar = null;

        // try to find calendar by slug
        if ($calendarSlug) {
            $calendar = Calendar::where('slug', $calendarSlug)->first();
        }

        // otherwise take first calendar
        if (!$calendar) {
            $calendar = Calendar::first();
        }

        // if not found, return false
        if (!$calendar) {
            return false;
        }

        return $calendar->id;
    }
}
