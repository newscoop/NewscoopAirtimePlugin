NewscoopAirtimePluginBundle
===================

This Newscoop Plugin adds smarty functions and Admin tools to enable you to manage and display Airtime data in Newscoop.


##### Install instructions for Newscoop v4.3 on OSX with MAMP

1. php application/console plugin:install newscoop/airtime-plugin-bundle 

##### Update instructions for Newscoop v4.3 on OSX with MAMP

1. php application/console plugin:remove newscoop/airtime-plugin-bundle
2. follow steps for manual install above



### Airtime Schedule View

Provides endpoit, **/airtime/schedule** for viewing an airtime instances show schedule.  Loads template **Resources/views/Airtime/airtime_schedule.tpl** or **airtime/airtime_schedule.tpl** if it exists in the loaded theme.

Usage:
```smarty
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
```


### Airtime Show Tracks View

Provides endpoit, **/airtime/show_tracks** for displaying tracks from a specific show instance.  Loads template **Resources/views/Airtime/airtime_show_tracks.tpl** or **airtime/airtime_show_tracks.tpl** if it exists in the loaded theme.

Usage:
```smarty
{{ foreach $tracks as $track }}
    <div>
        <span>{{ $track.title }}</span>
        <span>{{ $track.artist }}</span>
        <span>{{ $track.starts }}</span>
        <span>{{ $track.length }}</span>
        <span>{{ $track.file_id }}</span>
    </div>
{{ /foreach }}
```

### Airtime File 

Provides endpoit, **/airtime/file/{fileId}** for delivering an audio track inline for streaming.  

### Airtime Shows View

Provides endpoit, **/airtime/shows/{showId}** for displaying show metadata.  
showId parameter is optional.  
Loads template **Resources/views/Airtime/airtime_shows.tpl** or **airtime/airtime_shows.tpl** if it exists in the loaded theme.
for single show **Resources/views/Airtime/airtime_show.tpl** or **airtime/airtime_show.tpl** if it exists in the loaded theme.

shows.tpl Usage:
```smarty
{{ foreach $shows as $show }}
    <div>
        <span><a href="/airtime/shows/{{ $show.id }}">{{ $show.name}}</a></span>
        <span>{{ $show.id }}</span>
        <span>{{ $show.description }}</span>
        <span>{{ $show.genre }}</span>
        <span>{{ $show.url }}</span>
    </div>
{{ /foreach }}
```

show.tpl Usage:
```smarty
<div>
    <p>{{ $show.name }}</p>
    <p>{{ $show.id }}</p>
    <p>{{ $show.description }}</p>

    <audio id="show-audio" controls autoplay></audio>
    <h4>Show Instances</h4>
    {{ foreach $showInstances as $showInstance }}
       <p>{{ $showInstance.instance_id }} :  {{ $showInstance.starts }} - {{ $showInstance.ends }}</p>
        {{ foreach $showInstance.tracks as $track }}
            <div class="show-track" data-src="/airtime/file/{{ $track.file_id}}" data-type="{{ $track.mime }}" style="cursor: pointer; cursor: hand;">
                <span>{{ $track.starts}} : {{ $track.title }} - {{ $track.artist }}</span>
            </div>
        {{ /foreach }}
    {{ /foreach }}
</div>

<script>
$(document).ready(function() {
    
    $('.show-track').live('click', function() {
        var audio = $('#show-audio')[0];
        var trackSrc = $(this).data('src');
        var trackType = $(this).data('type');
        $('#show-audio').attr('src', trackSrc); 
        $('#show-audio').attr('type', trackType); 
        audio.load();
    });
});
</script>
```

### Airtime Show History View

Provides endpoit, **/airtime/show_history** for displaying historical show schedule data (not tracks).  Takes optional parameters:
start - start date (defaults to default schedule display defined in plugin admin)
end - end date (defaults to default schedule display defined in plugin admin)
Loads template **Resources/views/Airtime/airtime_show_history.tpl** or **airtime/airtime_show_history.tpl** if it exists in the loaded theme.

Usage:
```smarty
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
```

### Airtime Track History View

Provides endpoit, **/airtime/track_history** for displaying historical show schedule data (not tracks).  Takes optional parameters:
start - start date (defaults to default schedule display defined in plugin admin)
end - end date (defaults to default schedule display defined in plugin admin)
Loads template **Resources/views/Airtime/airtime_track_history.tpl** or **airtime/airtime_track_history.tpl** if it exists in the loaded theme.

Usage:
```smarty
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
```

### Airtime Live View

Provides endpoit, **/airtime/live** for displaying live broadcast data.
Loads template **Resources/views/Airtime/airtime_live.tpl** or **airtime/airtime_live.tpl** if it exists in the loaded theme.

Usage:
```smarty
<p>{{ $liveInfo.AIRTIME_API_VERSION }}</p>
<p>{{ $liveInfo.timezone }}</p>
<p>{{ $liveInfo.timezoneOffset }}</p>

<p>{{ $liveInfo.previous.name }}</p>
<p>{{ $liveInfo.previous.type }}</p>
<p>{{ $liveInfo.previous.starts }}</p>
<p>{{ $liveInfo.previous.ends }}</p>

<p>{{ $liveInfo.current.name }}</p>
<p>{{ $liveInfo.current.type }}</p>
<p>{{ $liveInfo.current.starts }}</p>
<p>{{ $liveInfo.current.ends }}</p>

<p>{{ $liveInfo.next.name }}</p>
<p>{{ $liveInfo.next.type }}</p>
<p>{{ $liveInfo.next.starts }}</p>
<p>{{ $liveInfo.next.ends }}</p>

<p>{{ $liveInfo.currentShow.name }}</p>
<p>{{ $liveInfo.currentShow.starts }}</p>
<p>{{ $liveInfo.currentShow.ends }}</p>
<p>{{ $liveInfo.currentShow.id }}</p>
<p>{{ $liveInfo.currentShow.instance_id }}</p>

<p>{{ $liveInfo.nextShow.name }}</p>
<p>{{ $liveInfo.nextShow.starts }}</p>
<p>{{ $liveInfo.nextShow.ends }}</p>
<p>{{ $liveInfo.nextShow.id }}</p>
<p>{{ $liveInfo.nextShow.instance_id }}</p>
```


### Smarty Functions

blocks
------------------------
* list_airtime_live_info
* list_airtime_show_tracks
* list_airtime_track_history
* list_airtime_show_history
* list_airtime_shows
* list_airtime_week_info

functions
------------------------

* airtime_current_show
* airtime_prev_track
* airtime_next_show
* airtime_stream_url


