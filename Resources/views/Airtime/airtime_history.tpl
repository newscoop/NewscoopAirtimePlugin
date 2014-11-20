<h4>airtime_history.tpl</h4>

{{ list_airtime_history instanceName="local" }}

    <span>{{ $show.name }}</span>
    <span>{{ $show.id }}</span>
    <span>{{ $show.starts }}</span>
    <span>{{ $show.ends }}</span>
    <span>{{ $show.created }}</span>
    <span>{{ $show.last_scheduled }}</span>
    <span>{{ $show.time_filled }}</span>

{{ /list_airtime_history }}
