@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);
    $column['escaped'] = $column['escaped'] ?? false;
    $column['limit'] = 999999999;

    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }

    if(!empty($column['value'])) {
        $column['value'] = '<i class="'.$column['value'].'"></i>';
    }
@endphp

@include('crud::columns.text')

@switch ($column['iconset'])
    @case('ionicon')
        @basset('https://unpkg.com/bootstrap-iconpicker@1.8.2/icon-fonts/ionicons-1.5.2/css/ionicons.min.css')
        @basset('https://unpkg.com/bootstrap-iconpicker@1.8.2/icon-fonts/ionicons-1.5.2/fonts/ionicons.woff')
        @break
    @case('weathericon')
        @basset('https://unpkg.com/bootstrap-iconpicker@1.8.2/icon-fonts/weather-icons-1.2.0/css/weather-icons.min.css')
        @basset('https://unpkg.com/bootstrap-iconpicker@1.8.2/icon-fonts/weather-icons-1.2.0/fonts/weathericons-regular-webfont.woff')
        @break
    @case('mapicon')
        @basset('https://unpkg.com/bootstrap-iconpicker@1.8.2/icon-fonts/map-icons-2.1.0/css/map-icons.min.css')
        @basset('https://unpkg.com/bootstrap-iconpicker@1.8.2/icon-fonts/map-icons-2.1.0/fonts/map-icons.woff')
        @break
    @case('octicon')
        @basset('https://unpkg.com/bootstrap-iconpicker@1.8.2/icon-fonts/octicons-2.1.2/css/octicons.min.css')
        @basset('https://unpkg.com/bootstrap-iconpicker@1.8.2/icon-fonts/octicons-2.1.2/fonts/octicons.woff')
        @break
    @case('typicon')
        @basset('https://unpkg.com/bootstrap-iconpicker@1.8.2/icon-fonts/typicons-2.0.6/css/typicons.min.css')
        @basset('https://unpkg.com/bootstrap-iconpicker@1.8.2/icon-fonts/typicons-2.0.6/fonts/typicons.woff')
        @break
    @case('elusiveicon')
        @basset('https://unpkg.com/bootstrap-iconpicker@1.8.2/icon-fonts/elusive-icons-2.0.0/css/elusive-icons.min.css')
        @basset('https://unpkg.com/bootstrap-iconpicker@1.8.2/icon-fonts/elusive-icons-2.0.0/fonts/Elusive-Icons.woff')
        @break
    @case('meterialdesign')
        @basset('https://unpkg.com/bootstrap-iconpicker@1.8.2/icon-fonts/material-design-1.1.1/css/material-design-iconic-font.min.css')
        @basset('https://unpkg.com/bootstrap-iconpicker@1.8.2/icon-fonts/material-design-1.1.1/fonts/Material-Design-Iconic-Font.woff')
        @break
    @default
        @basset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css')
        @basset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/webfonts/fa-solid-900.woff2', false)
        @basset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/webfonts/fa-brands-400.woff2', false)
        @basset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/webfonts/fa-regular-400.woff2', false)
        @break
@endswitch
