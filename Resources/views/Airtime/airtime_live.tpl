<h4>airtime_live.tpl</h4>

<p>output from $liveInfo var set by controller</p>
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


<p>output from list_airtime_live_info instanceName="local"</p>
{{ list_airtime_live_info instanceName="local" }}

<p>{{ $AIRTIME_API_VERSION }}</p>
<p>{{ $timezone }}</p>
<p>{{ $timezoneOffset }}</p>

<p>{{ $previous.name }}</p>
<p>{{ $previous.type }}</p>
<p>{{ $previous.starts }}</p>
<p>{{ $previous.ends }}</p>

<p>{{ $current.name }}</p>
<p>{{ $current.type }}</p>
<p>{{ $current.starts }}</p>
<p>{{ $current.ends }}</p>

<p>{{ $next.name }}</p>
<p>{{ $next.type }}</p>
<p>{{ $next.starts }}</p>
<p>{{ $next.ends }}</p>

<p>{{ $currentShow.name }}</p>
<p>{{ $currentShow.starts }}</p>
<p>{{ $currentShow.ends }}</p>
<p>{{ $currentShow.id }}</p>
<p>{{ $currentShow.instance_id }}</p>

<p>{{ $nextShow.name }}</p>
<p>{{ $nextShow.starts }}</p>
<p>{{ $nextShow.ends }}</p>
<p>{{ $nextShow.id }}</p>
<p>{{ $nextShow.instance_id }}</p>

{{ /list_airtime_live_info }}
