function onLoadItems(start, end, timezone, callback)
{
    var cal = $('#calendar');
    var date = cal.fullCalendar('getDate');
    var view = cal.fullCalendar('getView').name;
    $.request('fullcalendar::onGetMonthEvents', {
        data: {
            start: start.unix(),
            end: end.unix(),
            timezone: timezone,
            date: date.unix(),
            view: view,
            type: type
        },
        success: function(data){
            var events = $.parseJSON(data.result);
            callback(events);
        }
    });
}

function switchToDayView(calEvent, e, view)
{
    if (view.name === 'month') {
        cal.fullCalendar('gotoDate', calEvent.start);
        cal.fullCalendar('changeView', 'agendaDay');
        cal.fullCalendar('refetchEvents');
        e.preventDefault();
    }
}
