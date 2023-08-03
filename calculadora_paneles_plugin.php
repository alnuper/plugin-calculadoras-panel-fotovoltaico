<?php
/*
Plugin Name: Calculadora de paneles solares Fotovoltaicos
Description: Plugin que permite calcular el número de paneles fotovoltaicos necesarios para cubrir las necesidades de una vivienda. Utiliza el shortcode [solar_calculator_form] para insertar la calculadora.
Version: 1.1
Author: Alberto NÚÑEZ
Author URI: https://webficina.es
License: GPLv2 o posterior
Text Domain: Calculadora paneles solares
*/

//Función para añadir los estilos
function enqueue_panel_solar_styles() {
    wp_enqueue_style('panel_solar_styles', plugins_url('/css/calculadora_solar.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'enqueue_panel_solar_styles');


// Función que muestra el formulario
function solar_calculator_form_shortcode() {
    ob_start();
    ?>
    <div class="contenedor">
    <form id="solar_calculator_form" method="post">
        <h2>Calculadora Paneles Solares Fotovoltaicos</h2>
        <div class="imagen">
        <img src="<?php echo plugins_url('/img/panel_solar.webp', __FILE__);?>" alt="Panel Solar">
        </div>
        <label for="consumo_vivienda">Introduce el consumo eléctrico anual de la vivienda (kWh):</label>
        <p class="etiqueta">Este dato puedes verlo en tu factura de la luz</p>
        <input type="number" name="consumo_vivienda" id="consumo_vivienda" required placeholder="Consumo anual en Kwh">

        <label for="provincia">Selecciona tu provincia:</label>
        <?php
        $provincias = array(
            'A Coruña', 'Álava', 'Albacete', 'Alicante', 'Almería', 'Asturias', 'Ávila', 'Badajoz', 'Barcelona', 'Burgos', 'Cáceres', 'Cádiz', 'Cantabria', 'Castellón', 'Ciudad Real', 'Córdoba', 'Cuenca', 'Girona', 'Granada', 'Guadalajara', 'Guipúzcoa', 'Huelva', 'Huesca', 'Jaén', 'La Palma', 'La Rioja', 'Lanzarote', 'León', 'Lleida', 'Lugo', 'Madrid', 'Málaga', 'Mallorca', 'Menorca', 'Murcia', 'Ourense', 'Pamplona', 'Pontevedra', 'Salamanca', 'Segovia', 'Sevilla', 'Soria', 'Tarragona', 'Tenerife', 'Teruel', 'Toledo', 'Valencia', 'Valladolid', 'Vizcaya', 'Zamora', 'Zaragoza');
        ?>
        <select name="provincia" id="provincia" required>
            <option value="" disabled selected>Selecciona una provincia</option>
            <?php
            foreach ($provincias as $provincia) {
                echo '<option value="' . esc_attr($provincia) . '">' . esc_html($provincia) . '</option>';
            }
            ?>
        </select>

        <label for="potencia_panel">Introduce la potencia del panel a instalar (en watios):</label>
        <p class="etiqueta">Si no conoces este dato, tomaremos de referencia un panel de 500w</p>
        <input type="number" name="potencia_panel" id="potencia_panel" placeholder="Potencia panel">

        <input type="submit" value="Calcular">
    </form>
        <div id="solar_calculator_result">
            <div id="numero_paneles" class="numero_paneles"></div>
            <div id="texto_paneles" class="texto_paneles"></div>
        </div>
    </div>

    <script>
        document.getElementById('solar_calculator_form').addEventListener('submit', function(event) {
            event.preventDefault();

            var consumoVivienda = parseFloat(document.getElementById('consumo_vivienda').value);
            var provincia = document.getElementById('provincia').value;
            var potenciaPanel = parseFloat(document.getElementById('potencia_panel').value);

            // Si no se proporciona la potencia del panel, asumimos 500W
            if (isNaN(potenciaPanel)) {
                potenciaPanel = 500;
            }

            // Obtenemos las horas de sol anuales de la provincia seleccionada
            var horasSolAnuales = getHorasSolAnuales(provincia);

            // Realizamos el cálculo
            var panelesInstalar = Math.ceil((consumoVivienda * 1.15 * 1000) / (horasSolAnuales * potenciaPanel));

            // Mostramos el resultado
            
            //document.getElementById('solar_calculator_result').innerHTML = 'Número de paneles solares fotovoltaicos requeridos: ' + panelesInstalar;
            document.getElementById('numero_paneles').innerHTML =  panelesInstalar;
            document.getElementById('texto_paneles').innerHTML = 'Paneles solares fotovoltaicos de ' + potenciaPanel + ' w de potencia necesitas instalar para cubrir la demanda de energía indicada';

            document.getElementById('solar_calculator_result').classList.add('resultado');
        });

        wp_enqueue_style('calculadora_solar', plugins_url('/css/calculadora_solar.css', __FILE__));

        // Función para obtener las horas de sol anuales de la provincia seleccionada (simulación)
        function getHorasSolAnuales(provincia) {
            // Aquí puedes implementar la lógica para obtener las horas de sol anuales de cada provincia
            // Por ahora, simplemente simularemos algunos valores
            var horasSolPorProvincia = {
                'A Coruña' : 2453, 'Álava' : 2147, 'Albacete' : 3282, 'Alicante' : 3397, 'Almería' : 3305, 'Asturias' : 1962, 'Ávila' : 3065, 'Badajoz' : 3224, 'Barcelona' : 2453, 'Burgos' : 2751, 'Cáceres' : 3365, 'Cádiz' : 3316, 'Cantabria' : 1639, 'Castellón' : 3321, 'Ciudad Real' : 3295, 'Córdoba' : 3316, 'Cuenca' : 2779, 'Girona' : 2800, 'Granada' : 3228, 'Guadalajara' : 2900, 'Guipúzcoa' : 1906, 'Huelva' : 3527, 'Huesca' : 3099, 'Jaén' : 3289, 'La Palma' : 2800, 'La Rioja' : 2708, 'Lanzarote' : 2924, 'León' : 2734, 'Lleida' : 3031, 'Lugo' : 2820, 'Madrid' : 2691, 'Málaga' : 3248, 'Mallorca' : 3098, 'Menorca' : 2981, 'Murcia' : 3348, 'Ourense' : 2800, 'Pamplona' : 2285, 'Pontevedra' : 3031, 'Salamanca' : 3262, 'Segovia' : 3024, 'Sevilla' : 3526, 'Soria' : 2894, 'Tarragona' : 2820, 'Tenerife' : 3098, 'Teruel' : 3011, 'Toledo' : 2815, 'Valencia' : 2808, 'Valladolid' : 3016, 'Vizcaya' : 1694, 'Zamora' : 2858, 'Zaragoza' : 2620,
                
            };

            return horasSolPorProvincia[provincia] || 0;
        }
    </script>
    
    <?php
    return ob_get_clean();
}

add_shortcode('solar_calculator_form', 'solar_calculator_form_shortcode');
