      
<div class="container">
	
    <h1 class="page-header">Advertisements List ({{ advertisements|length }} offers)</h1>
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
                        <div class="advertisement" id="adv_{{ item['source_id'] }}">
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

                                <p>
                                    <button onclick="similar({{ item['source_id'] }});" type="button" class="btn btn-xs btn-primary">Pokaż podobne</button>
                                    <button onclick="skipped({{ item['source_id'] }});" type="button" class="btn btn-xs btn-info">Oznacz jako pozyskany</button>
                                    <button onclick="ignored({{ item['source_id'] }}, '1');" type="button" class="btn btn-xs btn-warning">Ignoruj przez tydzień</button>
                                    <button onclick="ignored({{ item['source_id'] }}, '4');" type="button" class="btn btn-xs btn-warning">Ignoruj przez miesiąc</button>
                                    <button onclick="deleted({{ item['source_id'] }});" type="button" class="btn btn-xs btn-danger">Usuń z bazy</button>
                                </p>

                                <div class="hidden" id="additionals_{{ item['source_id'] }}">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                    </div>
                                </div>
                            
                            </blockquote>
                        </div>
                    {% endfor %}
                    
                </div>
                <hr/>
            {% endfor %}
            
            
        </div>
        
    </div>
    
</div>
            
<script type="text/javascript">
    
    function similar(id) {
        elem = $('div#additionals_' + id.toString());
        elem.toggleClass('hidden');
        
        $.ajax({
            type: "POST",
            url: "{{ url('api/advertisements/similar') }}",
            data: JSON.stringify({id: id}),
            dataType: 'json',
            success: function(response) {
                if(response.status == "success") {
                    items = response.data.items;
                    
                    var html = '<p class="text-warning">nie znaleziono</p>'
                    if(items.length > 0) {
                    
                        html = '<p><ul class="list-unordered">';
                        for(i in items) {
                            html += '<li><a href="' + items[i].url + '">' + items[i].title + '</a> <b>' + items[i].rooms + '-pokojowe</b> pod adresem <b>' + items[i].address + '</b> w cenie <b>' + items[i].price_per_area + 'zł</b> - ' + (items[i].middleman == 1 ? 'od biura' : 'prywatna') + '</li>'
                        }
                        html + "</ul></p>"
                    }
                    
                    elem.html(html);
                }
                
            }
        });
        
    }
    
    function skipped(id) {
    
        $.ajax({
            type: "POST",
            url: "{{ url('api/advertisements/skipped') }}",
            data: JSON.stringify({id: id}),
            dataType: 'json',
            success: function(response) {
                if(response.status == "success") {
                    $('div.advertisement#adv_' + id.toString()).html('<p class="text-danger">Oferta nie będzie więcej wyświetlana.</p>');
                }
            }
        });
        
    }
    
    function ignored(id, weeks) {
    
        $.ajax({
            type: "POST",
            url: "{{ url('api/advertisements/ignored') }}",
            data: JSON.stringify({id: id, weeks: weeks}),
            dataType: 'json',
            success: function(response) {
                if(response.status == "success") {
                    
                    console.log(response.data);
                }
                
            }
        });
        
    }
    
    function deleted(id) {
    
        $.ajax({
            type: "POST",
            url: "{{ url('api/advertisements/deleted') }}",
            data: JSON.stringify({id: id}),
            dataType: 'json',
            success: function(response) {
                if(response.status == "success") {
                    $('div.advertisement#adv_' + id.toString()).html('<p class="text-danger">Oferta zostanie zaktualizowana przy następnym ściąganiu.</p>');
                }
                
            }
        });

    }
    
    
    
</script>

