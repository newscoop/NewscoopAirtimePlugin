<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<h4>airtime_show.tpl</h4>

<div>
    <p>{{ $show.name }}</p>
    <p>{{ $show.id }}</p>
    <p>{{ $show.description }}</p>

    <audio id="show-audio" controls autoplay></audio>
    <h4>Show Instances</h4>
    {{ foreach $showInstances as $showInstance }}
       <p>{{ $showInstance.instance_id }} :  {{ $showInstance.starts }} - {{ $showInstance.ends }}</p>
        {{ if isset($showInstance.tracks) }}
        {{ foreach $showInstance.tracks as $track }}
            <div class="show-track" data-src="/airtime/file/{{ $track.file_id}}" data-type="{{ $track.mime }}" style="cursor: pointer; cursor: hand;">
                <span>{{ $track.starts}} : {{ $track.title }} - {{ $track.artist }}</span>
            </div>
        {{ /foreach }}
        {{ /if }}
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
