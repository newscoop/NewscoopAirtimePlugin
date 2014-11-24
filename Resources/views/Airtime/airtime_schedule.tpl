<h4>airtime_schedule.tpl</h4>

<p>output from $schedule var set by controller</p>

{{ foreach $schedule as $day => $shows }}
    {{ foreach $shows as $show }}
        <div>
            <span>{{ $day }}</span>
            <span>{{ $show.name }}</span>
            <span>{{ $show.url }}</span>
            <span>{{ $show.starts }}</span>
            <span>{{ $show.ends }}</span>
        </div>
    {{ /foreach }}
    
{{ /foreach }}

<p>output from list_airtime_week_info instanceName="local"</p>
{{ list_airtime_week_info instanceName="local" }}
    {{ foreach $day as $show }}
        <div>
            <span>{{ $dow }}</span>
            <span>{{ $show.name }}</span>
            <span>{{ $show.url }}</span>
            <span>{{ $show.starts }}</span>
            <span>{{ $show.ends }}</span>
        </div>
    {{ /foreach }}
{{ /list_airtime_week_info }}
