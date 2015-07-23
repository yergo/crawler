      
<div class="container">
	
    <h1 class="page-header">Odsuniętych w czasie lista ogłoszeń ({{ advertisements|length }} offers)</h1>
    <p class="lead">
        Od nie-pośredników, pogrupowane po numerach telefonicznych, od najstarszej daty aktualizacji rosnąco.
    </p>

    <div class="row">
        
        <div class="col-md-12" role="main">
            
            {% for phone,items in advertisements %}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>{{ phone }} -  {{ items[0]['author'] }}</b>
                        {% if items[0]['email'] %}
                            <br/>
                            <span class="text-warning">mail: <a href="mailto:{{ items[0]['email'] }}">{{ items[0]['email'] }}</a></span>
                        {% endif %}
                    </div>
                    
                    {% for item in items %}
                        <div class="advertisement panel-body" id="adv_{{ item['id'] }}">
                            <h4>
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <img src="{{ url('img/' ~ item['source_name'] ~ '.ico') }}" width="16" height="16" />
                                    </div>
                                    <div class="media-body">
                                        <a href="{{ item['url'] }}"> {{ item['title'] }} </a>
                                    </div>
                                </div>
                            </h4>
                            <p>
                                <b>{{ item['district'] }}{% if item['address'] %}, ul. {{ item['address'] }}{% endif %}.</b><br/>
                                Mieszkanie <b>{{ item['rooms'] }}-pokojowe</b> o powierzchni <b>{{ item['area'] }}m<sup>2</sup></b>.</br>
                                W cenie <b>{{ item['price_per_area'] }} zł</b> czyli <b>{{ item['price_per_meter'] }} <sup>zł</sup>/<sub>m<sup>2</sup></sub></b>.
                            </p>
                            <p class="text-muted">
                              <span class="glyphicon glyphicon-triangle-right"></span>
                              Dodano {{ item['added'] }}, ostatnio zaktualizowano {{ item['updated'] }}
                            </p>

                            <p>
{#                                <button onclick="similar('{{ item['id'] }}');" type="button" class="btn btn-xs btn-primary">Pokaż podobne</button>#}
{#                                <button onclick="skipped('{{ item['id'] }}');" type="button" class="btn btn-xs btn-info">Oznacz jako pozyskany</button>#}
{#                                <button onclick="ignored('{{ item['id'] }}', '1');" type="button" class="btn btn-xs btn-warning">Ignoruj przez tydzień</button>#}
{#                                <button onclick="ignored('{{ item['id'] }}', '4');" type="button" class="btn btn-xs btn-warning">Ignoruj przez miesiąc</button>#}
                                <button onclick="deleted('{{ item['id'] }}');" type="button" class="btn btn-xs btn-danger">Usuń z bazy</button>
                            </p>
                            
                            <ul class="list-group hidden" id="loader_{{ item['id'] }}">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                            </ul>
                            
                        </div>

                        <ul class="list-group hidden" id="additionals_{{ item['id'] }}">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                            </div>
                        </ul>
                            
                    {% endfor %}
                    
                </div>
                <hr/>
            {% endfor %}
            
            
        </div>
        
    </div>
    
</div>
            
<script type="text/javascript">
    
    function similar(id) {
        elem = $('ul#additionals_' + id.toString());
        elem.toggleClass('hidden');
        
        $.ajax({
            type: "POST",
            url: "{{ url('api/advertisements/similar') }}",
            data: JSON.stringify({id: id}),
            dataType: 'json',
            success: function(response) {
                if(response.status == "success") {
                    items = response.data.items;
                    
                    var html = '<li class="list-group-item text-warning">nie znaleziono</li>'
                    if(items.length > 0) {
                    
                        html = '';
                        for(i in items) {
                            html += '<li class="list-group-item"><a href="' + items[i].url + '">' + items[i].title + '</a> <b>' + items[i].rooms + '-pokojowe</b> pod adresem <b>' + items[i].address + '</b> w cenie <b>' + items[i].price_per_area + 'zł</b> - ' + (items[i].middleman == 1 ? 'od biura' : 'prywatna') + '</li>'
                        }
                        html + "";
                    }
                    
                    elem.html(html);
                }
                
            }
        });
        
    }
    
    function skipped(id) {

        elem = $('ul#loader_' + id.toString());
        elem.toggleClass('hidden');

        $.ajax({
            type: "POST",
            url: "{{ url('api/advertisements/skipped') }}",
            data: JSON.stringify({id: id}),
            dataType: 'json',
            success: function(response) {
                if(response.status == "success") {
                    $('div.advertisement#adv_' + id.toString()).html('<p class="alert alert-danger">Oferta nie będzie więcej wyświetlana.</p>');
                }
            }
        });
        
    }
    
    function ignored(id, weeks) {
    
        elem = $('ul#loader_' + id.toString());
        elem.toggleClass('hidden');

        $.ajax({
            type: "POST",
            url: "{{ url('api/advertisements/ignored') }}",
            data: JSON.stringify({id: id, weeks: weeks}),
            dataType: 'json',
            success: function(response) {
                if(response.status == "success") {
                    $('div.advertisement#adv_' + id.toString()).html('<p class="alert alert-warning">Oferta nie będzie się wyświetlać do ' + response.data.till + ' włącznie</p>');
                }
                
            }
        });
        
    }
    
    function deleted(id) {

        elem = $('ul#loader_' + id.toString());
        elem.toggleClass('hidden');

        $.ajax({
            type: "POST",
            url: "{{ url('api/advertisements/deleted') }}",
            data: JSON.stringify({id: id}),
            dataType: 'json',
            success: function(response) {
                if(response.status == "success") {
                    $('div.advertisement#adv_' + id.toString()).html('<p class="alert alert-info">Oferta zostanie zaktualizowana przy następnym ściąganiu.</p>');
                }
                
            }
        });

    }
    
    
    
</script>

