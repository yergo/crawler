{% extends "common/main.volt" %}

{% block navigation %}
	
	  <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{ url('') }}">
              <span class="glyphicon"><img src="{{ url('img/spider_white.png') }}" width="26" style="vertical-align: top;" /></span>
              Crawler
          </a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="{{ url() }}">Home</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ogłoszenia <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ url('advertisements') }}">Aktualna lista</a></li>
                    <li><a href="{{ url('advertisements/ignored') }}">Odsunięte w czasie</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ url('advertisements/skipped') }}">Pozyskane / Buraki</a></li>
                </ul>
            </li>
            <li><a href="{{ url('api') }}">API</a></li>
            <li><a href="{{ url('contact') }}">Kontakt</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	
{% endblock %}



{% block content %}

    <div class="container">

		{{ content() }}
		
    </div><!-- /.container -->	

{% endblock %}



{% block footer %}
	
	{#<footer class="footer">
      <div class="container">
        <p class="text-muted">
			Place sticky footer content here.
		</p>
      </div>
    </footer>#}
	
{% endblock %}


