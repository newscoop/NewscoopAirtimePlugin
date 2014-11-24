<h4>airtime_shows.tpl</h4>

<h5>Output from shows var sent from controller</h5>

{{ foreach $shows as $show }}
    <div>
        <span><a href="/airtime/shows/{{ $show.id }}">{{ $show.name}}</a></span>
        <span>{{ $show.id }}</span>
        <span>{{ $show.description }}</span>
        <span>{{ $show.genre }}</span>
        <span>{{ $show.url }}</span>
    </div>
{{ /foreach }} 

<h5>Output from smarty block list_airtime_shows </h5>

{{ list_airtime_shows instanceName="local" }}
    <div>
        <span>{{ $show.name}}</span>
        <span>{{ $show.id}}</span>
        <span>{{ $show.description}}</span>
        <span>{{ $show.genre}}</span>
        <span>{{ $show.url}}</span>
    </div>
{{ /list_airtime_shows }}

<h5>Output from smarty block list_airtime_shows showId=4 </h5>

{{ list_airtime_shows instanceName="local" showId=4 }}
    <div>
        <span>{{ $show.name}}</span>
        <span>{{ $show.id}}</span>
        <span>{{ $show.description}}</span>
        <span>{{ $show.genre}}</span>
        <span>{{ $show.url}}</span>
    </div>
{{ /list_airtime_shows }}
