<h4>airtime_track_history.tpl</h4>

<p>output from $trackHistory set by controller</p>
{{ foreach $trackHistory as $track }}
    <div>
        <span>{{ $track.track_title }}</span>
        <span>{{ $track.artist_name }}</span>
        <span>{{ $track.instance_id }}</span>
        <span>{{ $track.starts }}</span>
        <span>{{ $track.ends }}</span>
        <span>{{ $track.history_id }}</span>
    </div>
{{ /foreach }}

<p>output from list_airtime_track_history instanceName="local"</p>
{{ list_airtime_track_history instanceName="local" }}
    <div>
        <span>{{ $track.track_title }}</span>
        <span>{{ $track.artist_name }}</span>
        <span>{{ $track.instance_id }}</span>
        <span>{{ $track.starts }}</span>
        <span>{{ $track.ends }}</span>
        <span>{{ $track.history_id }}</span>
    </div>
{{ /list_airtime_track_history }}
