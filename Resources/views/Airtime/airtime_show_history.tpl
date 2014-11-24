<h4>airtime_show_history.tpl</h4>

<p>output from $showHistory set by controller</p>
{{ foreach $showHistory as $show }}
    <div>
        <span>{{ $show.name }}</span>
        <span>{{ $show.show_id }}</span>
        <span>{{ $show.instance_id }}</span>
        <span>{{ $show.starts }}</span>
        <span>{{ $show.ends }}</span>
        <span>{{ $show.created }}</span>
        <span>{{ $show.last_scheduled }}</span>
        <span>{{ $show.time_filled }}</span>
    </div>
{{ /foreach }}

<p>output from list_airtime_show_history instanceName="local" </p>
{{ list_airtime_show_history instanceName="local" }}
    <div>
        <span>{{ $show.name }}</span>
        <span>{{ $show.show_id }}</span>
        <span>{{ $show.instance_id }}</span>
        <span>{{ $show.starts }}</span>
        <span>{{ $show.ends }}</span>
        <span>{{ $show.created }}</span>
        <span>{{ $show.last_scheduled }}</span>
        <span>{{ $show.time_filled }}</span>
    </div>
{{ /list_airtime_show_history }}
