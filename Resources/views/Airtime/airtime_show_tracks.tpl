<h4>airtime_show_tracks.tpl</h4>

<h4>output from $tracks set by controller</h4>
{{ foreach $tracks as $track }}
    <div>
        <span>{{ $track.title }}</span>
        <span>{{ $track.artist }}</span>
        <span>{{ $track.starts }}</span>
        <span>{{ $track.length }}</span>
        <span>{{ $track.file_id }}</span>
    </div>
{{ /foreach }}

<h4>output from list_airtime_show_tracks instanceName="local" showInstanceId="4"</h4>
{{ list_airtime_show_tracks instanceName="local" showInstanceId="4" }}
    <div>
        <span>{{ $track.title }}</span>
        <span>{{ $track.artist }}</span>
        <span>{{ $track.starts }}</span>
        <span>{{ $track.length }}</span>
        <span>{{ $track.file_id }}</span>
    </div>
{{ /list_airtime_show_tracks }}
