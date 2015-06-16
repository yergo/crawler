      
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
                        
                        <button onclick="similar({{ item['source_id'] }});" type="button" class="btn btn-xs btn-primary">Pokaż podobne</button>
                        <button onclick="skipped({{ item['source_id'] }});" type="button" class="btn btn-xs btn-info">Oznacz jako pozyskany</button>
                        <button onclick="ignored({{ item['source_id'] }}, '1');" type="button" class="btn btn-xs btn-warning">Ignoruj przez tydzień</button>
                        <button onclick="ignored({{ item['source_id'] }}, '4');" type="button" class="btn btn-xs btn-warning">Ignoruj przez miesiąc</button>
                        <button onclick="deleted({{ item['source_id'] }});" type="button" class="btn btn-xs btn-danger">Usuń z bazy</button>
                        
                        <div class="hidden" id="additionals_{{ item['source_id'] }}">
                            
                        </div>
                    {% endfor %}
                    
                </div>
                <hr/>
            {% endfor %}
            
            
        </div>
        
    </div>
    
</div>
            
<script type="text/javascript">
    
    function similar(var id) {
        
    }
    
    function skipped(var id) {
        
    }
    
    function ignored(var id, var weeks) {
        
    }
    
    function deleted(var id) {
        
    }
    
    
    
</script>

