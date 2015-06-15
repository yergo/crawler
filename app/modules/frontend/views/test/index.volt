      
<div class="container">
	
    <div class="row">
        
        <div class="col-md-9" role="main">
            <h1 class="page-header">Test one ({{ advertisements|length }} ofert)</h1>
            <p class="lead">
                Od nie-pośredników, pogrupowane po numerach telefonicznych, od najstarszej daty aktualizacji rosnąco.
            </p>
            
            {% for items in advertisements %}
                <div class="container btn-default">
                    <h2>{{ items[0]['phone'] }} -  {{ items[0]['author'] }}</h2>
                    {% if items[0]['email'] %}
                        <p class="text-warning">mail: <a href="mailto:{{ items[0]['email'] }}">{{ items[0]['email'] }}</a></p>
                    {% endif %}
                    {% for item in items %}
                    <h3>
                        <a href="{{ item['url'] }}"> {{ item['title'] }} </a>
                    </h3>
                        <p>
                            <b>{{ item['district'] }}, ul. {{ item['address'] }}.</b><br/>
                            Mieszkanie <b>{{ item['rooms'] }}-pokojowe</b> o powierzchni <b>{{ item['area'] }}m<sup>2</sup></b>.</br>
                            W cenie <b>{{ item['price_per_area'] }} zł</b> czyli <b>{{ item['price_per_meter'] }} <sup>zł</sup>/<sub>m<sup>2</sup></sub></b>.
                        </p>
                        <p class="text-muted">
                          Dodano {{ item['added'] }}, ostatnio zaktualizowano {{ item['updated'] }}
                        </p>
                    {% endfor %}
                </div>
                <hr/>
            {% endfor %}
            
            
        </div>
        
    </div>
    
</div>

