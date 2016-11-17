var cal = $('#calendar');
var type = 'aggregated';

var init = function()
{
    cal.fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month'
        },
        lang: lang,
        fixedWeekCount: false,
        aspectRatio: 2,
        defaultView: 'month',
        timeFormat: timeFormat + "\n",
        firstDay: firstDay,
        editable: false,
        eventLimit: false,
        events: onLoadItems,
        eventClick: switchToDayView
    });
};

$(document).ready(init);
