var cal = $('#calendar');
var type = 'normal';

var init = function()
{
    cal.fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,basicWeek,agendaDay,agendaList'
        },
        lang: lang,
        fixedWeekCount: false,
        aspectRatio: 2,
        defaultView: 'month',
        timeFormat: timeFormat + '\n',
        firstDay: firstDay,
        editable: false,
        eventLimit: false,
        events: onLoadItems
    });
};

$(document).ready(init);
