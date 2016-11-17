<?php namespace VojtaSvoboda\ProEventsPreview\Models;

use Config;
use October\Rain\Database\Model;
use October\Rain\Database\Traits\Validation as ValidationTrait;

class Settings extends Model
{
    use ValidationTrait;

    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'vojtasvoboda_proeventspreview_settings';

    public $settingsFields = 'fields.yaml';

    public $rules = [];

    /**
     * Load all calendar types.
     *
     * @return array
     */
    public function getTypeOptions()
    {
        $calendars = Config::get('vojtasvoboda.proeventspreview::calendars');
        $return = [];

        foreach($calendars as $key => $calendar) {
            $return[$key] = $calendar['name'];
        }

        return $return;
    }
}
