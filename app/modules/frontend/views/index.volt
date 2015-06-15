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
          <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="{{ url() }}">Home</a></li>
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


