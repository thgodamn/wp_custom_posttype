<?php

/* MY CUSTOM JK */
/* Добавляю кастомные таксономии для post_type - jk */

//Близость к метро ЖК
function jk_taxonomy_metro() {
    register_taxonomy(
        'metro',  					// This is a name of the taxonomy. Make sure it's not a capital letter and no space in between
        'jk',        			//post type name
        array(
            'hierarchical' => true,
            'label' => 'Близость к метро',  	//Display name
            'query_var' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'jk')
        )
    );
}
add_action( 'init', 'jk_taxonomy_metro');

//Срок сдачи ЖК
function jk_taxonomy_srok() {
    register_taxonomy(
        'srok',  					// This is a name of the taxonomy. Make sure it's not a capital letter and no space in between
        'jk',        			//post type name
        array(
            'hierarchical' => true,
            'label' => 'Срок сдачи',  	//Display name
            'query_var' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'jk')
        )
    );
}
add_action( 'init', 'jk_taxonomy_srok');

//Класс жилья ЖК
function jk_taxonomy_classjk() {
    register_taxonomy(
        'classjk',  					// This is a name of the taxonomy. Make sure it's not a capital letter and no space in between
        'jk',        			//post type name
        array(
            'hierarchical' => true,
            'label' => 'Класс жилья',  	//Display name
            'query_var' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'jk')
        )
    );
}
add_action( 'init', 'jk_taxonomy_classjk');

//Основные функции ЖК
function jk_taxonomy_main_functions() {
    register_taxonomy(
        'main_functions',  					// This is a name of the taxonomy. Make sure it's not a capital letter and no space in between
        'jk',        			//post type name
        array(
            'hierarchical' => true,
            'label' => 'Основные функции',  	//Display name
            'query_var' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'jk')
        )
    );
}
add_action( 'init', 'jk_taxonomy_main_functions');

//Дополнительные функции ЖК
function jk_taxonomy_additional_functions() {
    register_taxonomy(
        'additional_functions',  					// This is a name of the taxonomy. Make sure it's not a capital letter and no space in between
        'jk',        			//post type name
        array(
            'hierarchical' => true,
            'label' => 'Дополнительные функции',  	//Display name
            'query_var' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'jk')
        )
    );
}
add_action( 'init', 'jk_taxonomy_additional_functions');

function jk_taxonomy_uslugi() {
    register_taxonomy(
        'uslugi',  					// This is a name of the taxonomy. Make sure it's not a capital letter and no space in between
        'jk',        			//post type name
        array(
            'hierarchical' => true,
            'label' => 'Услуги',  	//Display name
            'query_var' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'jk')
        )
    );
}
add_action( 'init', 'jk_taxonomy_uslugi');

//Регистрируем post_type ЖК (jk)
function cw_post_type_jk() {
    $supports = array(
        'title', // post title
        'editor', // post content
        'author', // post author
        'thumbnail', // featured images
        'excerpt', // post excerpt
        'custom-fields', // custom fields
        'page-attributes',
        'comments', // post comments
        'revisions', // post revisions
        'post-formats', // post formats
    );
    $labels = array(
        'name' => _x('Новостройки', 'plural'),
        'singular_name' => _x('Новостройки', 'singular'),
        'menu_name' => _x('Новостройки', 'admin menu'),
        'name_admin_bar' => _x('Новостройки', 'admin bar'),
        'add_new' => _x('Добавить новый ЖК', 'add new'),
        'add_new_item' => __('Добавить новый ЖК (JK)'),
        'new_item' => __('Новый ЖК'),
        'edit_item' => __('Редактировать'),
        'view_item' => __('Показать ЖК'),
        'all_items' => __('Все ЖК'),
        'search_items' => __('Поиск ЖК'),
        'not_found' => __('Нет добавленых ЖК'),
    );
    $args = array(
        'supports' => $supports,
        'labels' => $labels,
        'public' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'jk'),
        'has_archive' => true,
        'hierarchical' => true,
        'taxonomies' => array('metro','srok') #array('category', 'post_tag')

    );
    register_post_type('jk', $args);
}
add_action('init', 'cw_post_type_jk');

add_action('wp_ajax_jkfilter', 'jk_filter_function'); // wp_ajax_{ACTION HERE}
add_action('wp_ajax_jkfilter_loadmore', 'jk_filter_function'); // wp_ajax_{ACTION HERE}
add_action('wp_ajax_nopriv_jkfilter', 'jk_filter_function');

function jk_filter_function(){

    if (!isset($_POST['loadmore_numpage'])) {
        $args = array(
            'post_type' => 'jk',
            'posts_per_page' => 12,
            'post_status' => 'publish',
        );
    } else {
        $args = array(
            'post_type' => 'jk',
            'posts_per_page' => 12,
            'post_status' => 'publish',
            'paged' => $_POST['loadmore_numpage']
        );
    }

    //var_dump($_POST['loadmore_numpage']);
    //var_dump($_POST['uslugi']);

    //$taxquery = array();
    $taxquery = array('relation' => 'AND');
    // metro
    if( !empty( $_POST['metro_filter'] && !isset($_POST['metro_filter_any']) ) )
        array_push ($taxquery, array(
                'taxonomy' => 'metro',
                'field' => 'id',
                'terms' => $_POST['metro_filter'],
                //'operator' => 'AND',
            )
        );

    if( !empty( $_POST['srok_filter'] ) && $_POST['srok_filter'] != 'any' )
        array_push ($taxquery, array(
                'taxonomy' => 'srok',
                'field' => 'id',
                'terms' => $_POST['srok_filter'],
                //'operator' => 'AND',
            )
        );

    //sizeof($_POST['classjk_filter']) != 4  - когда все эелементы выбраны, тоже самое что не выбраны
    if( !empty( $_POST['classjk_filter'] && isset($_POST['classjk_filter']) && sizeof($_POST['classjk_filter']) != 4 ) )
        array_push ($taxquery, array(
                'taxonomy' => 'classjk',
                'field' => 'id',
                'terms' => $_POST['classjk_filter'],
                //'operator' => 'AND',
            )
        );

    if( !empty( $_POST['main_filter'] ) )
        array_push ($taxquery, array(
                'taxonomy' => 'main_functions',
                'field' => 'id',
                'terms' => $_POST['main_filter'],
                'operator' => 'AND',
            )
        );

    if( !empty( $_POST['additional_filter'] ) )
        array_push ($taxquery, array(
                'taxonomy' => 'additional_functions',
                'field' => 'id',
                'terms' => $_POST['additional_filter'],
                'operator' => 'AND', //условие AND
            )
        );

    if( !empty( $_POST['uslugi_filter'] ) )
        array_push ($taxquery, array(
                'taxonomy' => 'uslugi',
                'field' => 'id',
                'terms' => $_POST['uslugi_filter'],
                'operator' => 'AND', //условие AND
            )
        );

    // if $taxquery has array;
    if(!empty($taxquery)){
        $args['tax_query'] = $taxquery;
    }

    //var_dump($_POST['loadmore_numpage']);
    //var_dump($_POST['check_loadmore_numpage']);
    //var_dump($query->post_count);


    $query = new WP_Query( $args );
    if (!isset($_POST['check_loadmore_numpage'])) {
        if( $query->have_posts() ) :
            while( $query->have_posts() ): $query->the_post();
                echo '<li class="page-loop__item wow animate__ animate__fadeInUp animated" data-wow-duration="0.8s">'; ?>
                <a class="page-loop__item-link" href="<?php the_permalink() ?>">
                    <div class="page-loop__item-image">
                        <?php the_post_thumbnail();?>
                        <div class="page-loop__item-badges">
                            <?php if (isset(get_the_terms( get_the_ID(), 'uslugi' )[0]->name)) { ?> <span class="badge"><?php echo get_the_terms( get_the_ID(), 'uslugi' )[0]->name; } ?></span>
                            <?php if (isset(get_the_terms( get_the_ID(), 'classjk' )[0]->name)) { ?> <span class="badge"><?php echo get_the_terms( get_the_ID(), 'classjk' )[0]->name; } ?></span>
                        </div>
                    </div>
                    <div class="page-loop__item-info">
                        <h3 class="page-title-h3"><?php the_title(); ?></h3>
                        <div class="page-text to-metro">
                            <span class="icon-metro icon-metro--red"></span>
                            <span class="page-text"><?php echo get_post_meta(get_the_ID(), 'metro1' )[0]; ?>
                                                            <span> <?php echo get_post_meta(get_the_ID(), 'walk_time' )[0]; ?>.</span>
                                                        </span>
                            <span class="icon-walk-icon"></span>
                        </div>
                        <span class="page-text text-desc"><?php echo get_post_meta(get_the_ID(), 'ulitsa' )[0]; ?></span>
                    </div>
                </a>
                </li> <?php
            endwhile;
            wp_reset_postdata();
        else :
            echo 'ЖК не найдены';
        endif;
    } else {
        if ($query->post_count == 12)
            echo 'loadmore_allowed';
        else echo 'loadmore_forbidden';
    }
    die();
}

/* Автоматическое добавление произвольных полей - метро, улица, время пути от метро */
function auto_acf_adding( $post_id ) {
    if ( get_post_type($post_id) == 'jk' ) {
        // If this is just a revision, don't send the email.
        if ( wp_is_post_revision( $post_id ) )
            return;
        if(!metadata_exists('post', $post_id, 'ulitsa'))
            update_post_meta($post_id, 'ulitsa', '');
        if(!metadata_exists('post', $post_id, 'metro1'))
            update_post_meta($post_id, 'metro1', '');
        if(!metadata_exists('post', $post_id, 'walk_time'))
            update_post_meta($post_id, 'walk_time', '');
    }
}
add_action( 'save_post', 'auto_acf_adding', 10, 2 );

//хлебные крошки для custom post_type
function get_hansel_and_gretel_breadcrumbs()
{
    // Set variables for later use
    $here_text        = __( 'You are currently here!' );
    $home_link        = home_url('/');
    $home_text        = __( 'Home' );
    $link_before      = '<span typeof="v:Breadcrumb">';
    $link_after       = '</span>';
    $link_attr        = ' rel="v:url" property="v:title"';
    $link             = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
    $delimiter        = ' > ';              // Delimiter between crumbs
    $before           = '<span class="current">'; // Tag before the current crumb
    $after            = '</span>';                // Tag after the current crumb
    $page_addon       = '';                       // Adds the page number if the query is paged
    $breadcrumb_trail = '';
    $category_links   = '';

    /**
     * Set our own $wp_the_query variable. Do not use the global variable version due to
     * reliability
     */
    $wp_the_query   = $GLOBALS['wp_the_query'];
    $queried_object = $wp_the_query->get_queried_object();

    // Handle single post requests which includes single pages, posts and attatchments
    if ( is_singular() )
    {
        /**
         * Set our own $post variable. Do not use the global variable version due to
         * reliability. We will set $post_object variable to $GLOBALS['wp_the_query']
         */
        $post_object = sanitize_post( $queried_object );

        // Set variables
        $title          = apply_filters( 'the_title', $post_object->post_title );
        $parent         = $post_object->post_parent;
        $post_type      = $post_object->post_type;
        $post_id        = $post_object->ID;
        $post_link      = $before . $title . $after;
        $parent_string  = '';
        $post_type_link = '';

        if ( 'post' === $post_type )
        {
            // Get the post categories
            $categories = get_the_category( $post_id );
            if ( $categories ) {
                // Lets grab the first category
                $category  = $categories[0];

                $category_links = get_category_parents( $category, true, $delimiter );
                $category_links = str_replace( '<a',   $link_before . '<a' . $link_attr, $category_links );
                $category_links = str_replace( '</a>', '</a>' . $link_after,             $category_links );
            }
        }

        if ( !in_array( $post_type, ['post', 'page', 'attachment'] ) )
        {
            $post_type_object = get_post_type_object( $post_type );
            $archive_link     = esc_url( get_post_type_archive_link( $post_type ) );

            $post_type_link   = sprintf( $link, $archive_link, $post_type_object->labels->singular_name );
        }

        // Get post parents if $parent !== 0
        if ( 0 !== $parent )
        {
            $parent_links = [];
            while ( $parent ) {
                $post_parent = get_post( $parent );

                $parent_links[] = sprintf( $link, esc_url( get_permalink( $post_parent->ID ) ), get_the_title( $post_parent->ID ) );

                $parent = $post_parent->post_parent;
            }

            $parent_links = array_reverse( $parent_links );

            $parent_string = implode( $delimiter, $parent_links );
        }

        // Lets build the breadcrumb trail
        if ( $parent_string ) {
            $breadcrumb_trail = $parent_string . $delimiter . $post_link;
        } else {
            $breadcrumb_trail = $post_link;
        }

        if ( $post_type_link )
            $breadcrumb_trail = $post_type_link . $delimiter . $breadcrumb_trail;

        if ( $category_links )
            $breadcrumb_trail = $category_links . $breadcrumb_trail;
    }

    // Handle archives which includes category-, tag-, taxonomy-, date-, custom post type archives and author archives
    if( is_archive() )
    {
        if (    is_category()
            || is_tag()
            || is_tax()
        ) {
            // Set the variables for this section
            $term_object        = get_term( $queried_object );
            $taxonomy           = $term_object->taxonomy;
            $term_id            = $term_object->term_id;
            $term_name          = $term_object->name;
            $term_parent        = $term_object->parent;
            $taxonomy_object    = get_taxonomy( $taxonomy );
            $current_term_link  = $before . $taxonomy_object->labels->singular_name . ': ' . $term_name . $after;
            $parent_term_string = '';

            if ( 0 !== $term_parent )
            {
                // Get all the current term ancestors
                $parent_term_links = [];
                while ( $term_parent ) {
                    $term = get_term( $term_parent, $taxonomy );

                    $parent_term_links[] = sprintf( $link, esc_url( get_term_link( $term ) ), $term->name );

                    $term_parent = $term->parent;
                }

                $parent_term_links  = array_reverse( $parent_term_links );
                $parent_term_string = implode( $delimiter, $parent_term_links );
            }

            if ( $parent_term_string ) {
                $breadcrumb_trail = $parent_term_string . $delimiter . $current_term_link;
            } else {
                $breadcrumb_trail = $current_term_link;
            }

        } elseif ( is_author() ) {

            $breadcrumb_trail = __( 'Author archive for ') .  $before . $queried_object->data->display_name . $after;

        } elseif ( is_date() ) {
            // Set default variables
            $year     = $wp_the_query->query_vars['year'];
            $monthnum = $wp_the_query->query_vars['monthnum'];
            $day      = $wp_the_query->query_vars['day'];

            // Get the month name if $monthnum has a value
            if ( $monthnum ) {
                $date_time  = DateTime::createFromFormat( '!m', $monthnum );
                $month_name = $date_time->format( 'F' );
            }

            if ( is_year() ) {

                $breadcrumb_trail = $before . $year . $after;

            } elseif( is_month() ) {

                $year_link        = sprintf( $link, esc_url( get_year_link( $year ) ), $year );

                $breadcrumb_trail = $year_link . $delimiter . $before . $month_name . $after;

            } elseif( is_day() ) {

                $year_link        = sprintf( $link, esc_url( get_year_link( $year ) ),             $year       );
                $month_link       = sprintf( $link, esc_url( get_month_link( $year, $monthnum ) ), $month_name );

                $breadcrumb_trail = $year_link . $delimiter . $month_link . $delimiter . $before . $day . $after;
            }

        } elseif ( is_post_type_archive() ) {

            $post_type        = $wp_the_query->query_vars['post_type'];
            $post_type_object = get_post_type_object( $post_type );

            $breadcrumb_trail = $before . $post_type_object->labels->singular_name . $after;

        }
    }

    // Handle the search page
    if ( is_search() ) {
        $breadcrumb_trail = __( 'Search query for: ' ) . $before . get_search_query() . $after;
    }

    // Handle 404's
    if ( is_404() ) {
        $breadcrumb_trail = $before . __( 'Error 404' ) . $after;
    }

    // Handle paged pages
    if ( is_paged() ) {
        $current_page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
        $page_addon   = $before . sprintf( __( ' ( Page %s )' ), number_format_i18n( $current_page ) ) . $after;
    }

    $breadcrumb_output_link  = '';
    $breadcrumb_output_link .= '<div class="breadcrumb">';
    if (    is_home()
        || is_front_page()
    ) {
        // Do not show breadcrumbs on page one of home and frontpage
        if ( is_paged() ) {
            //$breadcrumb_output_link .= $here_text . $delimiter;
            $breadcrumb_output_link .= '<a href="' . $home_link . '">' . $home_text . '</a>';
            $breadcrumb_output_link .= $page_addon;
        }
    } else {
        //$breadcrumb_output_link .= $here_text . $delimiter;
        $breadcrumb_output_link .= '<a href="' . $home_link . '" rel="v:url" property="v:title">' . $home_text . '</a>';
        $breadcrumb_output_link .= $delimiter;
        $breadcrumb_output_link .= $breadcrumb_trail;
        $breadcrumb_output_link .= $page_addon;
    }
    $breadcrumb_output_link .= '</div><!-- .breadcrumbs -->';

    return $breadcrumb_output_link;
}

/* multi image для post_type ЖК (jk) */
if (class_exists('MultiPostThumbnails')) {
    new MultiPostThumbnails(
        array(
            'label' => 'Secondary Image',
            'id' => 'secondary-image', // unique id
            'post_type' => 'jk' // or 'page' or your custom post type id
        )
    );
}

/* MY CUSTOM JK end */

?>