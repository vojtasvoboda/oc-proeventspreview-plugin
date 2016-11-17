<?php namespace VojtaSvoboda\ProEventsPreview\Controllers;

use App;
use BackendMenu;
use Backend\Classes\Controller;
use Config;
use File;
use Lang;
use VojtaSvoboda\ProEventsPreview\Models\Settings;
use Yaml;

class Calendar extends Controller
{
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Radiantweb.Proevents', 'proevents', 'calendar');

        // load particular calendar
        $calendarType = Settings::get('type');
        $calendarDefaultClass = 'VojtaSvoboda\ProEventsPreview\Widgets\FullCalendar';
        $calendarClass = Config::get('vojtasvoboda.proeventspreview::calendars.' . $calendarType . '.class', $calendarDefaultClass);

        // create calendar widget and pass all config data
        $fullcalendar = App::make($calendarClass, [$this]);
        $fullcalendar->bindToController();
    }

    /**
     * Controller index action.
     */
    public function index()
    {
        $this->pageTitle = Lang::get('vojtasvoboda.proeventspreview::lang.page_title');
    }
}
