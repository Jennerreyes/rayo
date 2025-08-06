add_action('wp_insert_post_data', function ($data, $postarr) {
    // Verifica si el tipo de publicación es un producto
    if ($data['post_type'] === 'product' && $data['post_status'] !== 'auto-draft') {
        global $wpdb;

        // Comprueba si ya existe un producto con el mismo título
        $existing_product = $wpdb->get_var(
            $wpdb->prepare("
                SELECT ID
                FROM {$wpdb->prefix}posts
                WHERE post_title = %s
                AND post_type = 'product'
                AND post_status = 'publish'
                LIMIT 1
            ", $data['post_title'])
        );

        // Si existe un producto, cancela la inserción
        if ($existing_product) {
            wp_die(
                __('Error: Ya existe un producto con este título. Cambia el título para continuar.', 'tu-texto-de-dominio'),
                __('Producto duplicado detectado', 'tu-texto-de-dominio'),
                ['back_link' => true]
            );
        }
    }

    return $data;
}, 10, 2);
