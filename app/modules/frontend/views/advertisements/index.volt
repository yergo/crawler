      
<div class="container">
	
    <h1 class="page-header">Test one ({{ advertisements|length }} ofert)</h1>
    <p class="lead">
        Od nie-pośredników, pogrupowane po numerach telefonicznych, od najstarszej daty aktualizacji rosnąco.
    </p>

    <div class="row col-md-12">
        
        <form action="{{ url('advertisements') }}" method="POST" class="form-inline">
            
            <div class="form-group">
                
                <label for="district">Dzielnica</label>
                <select class="form-control" name="district" id="district">
                    <option value="*">Wszystkie</option>
                </select>
                
            </div>
            <div class="form-group">
                
                <label for="phone">Telefon</label>
                <input type="text" class="form-control" name="phone" id="district" />
                
            </div>
            <div class="form-group">
                
            </div>
            
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="with-ignored"> Wraz z ignorowanymi
                </label>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
        
        
    </div>
            

    <div class="row">
        
        <div class="col-md-9" role="main">
            
            {% for phone,items in advertisements %}
                <div class="container">
                    <h2>{{ phone }} -  {{ items[0]['author'] }}</h2>
                    {% if items[0]['email'] %}
                        <p class="text-warning">mail: <a href="mailto:{{ items[0]['email'] }}">{{ items[0]['email'] }}</a></p>
                    {% endif %}
                    
                    {% for item in items %}
                        <blockquote>
                            <h3>
                                <a href="{{ item['url'] }}"> {{ item['title'] }} </a>
                            </h3>
                            <p>
                                <b>{{ item['district'] }}, ul. {{ item['address'] }}.</b><br/>
                                Mieszkanie <b>{{ item['rooms'] }}-pokojowe</b> o powierzchni <b>{{ item['area'] }}m<sup>2</sup></b>.</br>
                                W cenie <b>{{ item['price_per_area'] }} zł</b> czyli <b>{{ item['price_per_meter'] }} <sup>zł</sup>/<sub>m<sup>2</sup></sub></b>.
                            </p>
                            <footer class="text-muted">
                              Dodano {{ item['added'] }}, ostatnio zaktualizowano {{ item['updated'] }}
                            </footer>
                        </blockquote>
                    {% endfor %}
                    
                </div>
                <hr/>
            {% endfor %}
            
            
        </div>
        
    </div>
    
</div>
            
<script type="text/javascript">
    
    function ignore(var period) {
        
        console.log('Ignore for ' + period);
        
    }
    
</script>

