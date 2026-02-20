<?php
/**
 * Custom Nav Walker — handles Icons, Badges, and Mega Menus
 *
 * Mega Menu is detected AUTOMATICALLY:
 *   Any top-level menu item that has grandchildren (depth-2 items) is
 *   treated as a mega-menu parent — no CSS class needed in WP Admin.
 *   You can also manually add the CSS class "mega-menu" to a menu item
 *   in Appearance → Menus → Screen Options → CSS Classes to force it.
 */
class SMAN1_Nav_Walker extends Walker_Nav_Menu {

    /** IDs of top-level items that should render as mega menus */
    private $mega_menu_ids = [];

    /** ID of the mega-menu parent currently being walked */
    private $current_mega_parent_id = 0;

    /**
     * Override walk() to pre-scan the flat items array and detect
     * top-level items that have grandchildren → auto mega-menu.
     */
    public function walk( $elements, $max_depth, ...$args ) {

        // Build a lookup: parent_id → [child_ids]
        $parent_map = [];
        foreach ( $elements as $elem ) {
            $pid = (int) $elem->menu_item_parent;
            if ( $pid ) {
                $parent_map[ $pid ][] = (int) $elem->ID;
            }
        }

        // Any top-level item whose direct children themselves have children
        // → mark it as a mega-menu parent
        foreach ( $elements as $elem ) {
            if ( (int) $elem->menu_item_parent === 0 ) {
                if ( isset( $parent_map[ (int) $elem->ID ] ) ) {
                    foreach ( $parent_map[ (int) $elem->ID ] as $child_id ) {
                        if ( isset( $parent_map[ $child_id ] ) ) {
                            $this->mega_menu_ids[] = (int) $elem->ID;
                            break;
                        }
                    }
                }
            }
        }

        return parent::walk( $elements, $max_depth, ...$args );
    }

    public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
        $indent = str_repeat( "\t", $depth );

        $classes   = empty( $item->classes ) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        if ( $depth === 0 ) {
            $classes[] = 'nav-item';
        }

        // ---- Mega-menu detection: manual CSS class OR auto-detected ----
        $is_mega_parent = in_array( 'mega-menu', $classes )
                          || in_array( (int) $item->ID, $this->mega_menu_ids );

        if ( $depth === 0 ) {
            // Track which top-level item we are currently inside
            $this->current_mega_parent_id = $is_mega_parent ? (int) $item->ID : 0;
        }

        // Are we rendering inside a mega-menu (at any depth > 0)?
        $inside_mega = ( $this->current_mega_parent_id !== 0 );

        if ( $is_mega_parent && $depth === 0 ) {
            $classes[] = 'has-mega-menu';
        }

        // Dropdown marker (has direct children)
        if ( $args->walker->has_children ) {
            $classes[] = 'has-dropdown';
        }

        // Strip FA icon classes from <li> — icons are added inside <a>
        $classes = array_filter( $classes, function ( $c ) {
            return strpos( $c, 'fa-' ) === false
                && strpos( $c, 'fas' ) === false
                && strpos( $c, 'fab' ) === false
                && strpos( $c, 'far' ) === false;
        } );

        $class_names = esc_attr(
            join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) )
        );
        $class_attr = $class_names ? ' class="' . $class_names . '"' : '';
        $id_attr    = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id_attr . $class_attr . '>';

        // ---- Link attributes ----
        $atts            = [];
        $atts['title']   = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target']  = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']     = ( '_blank' === $atts['target'] ) ? 'noopener noreferrer' : ( ! empty( $item->xfn ) ? $item->xfn : '' );
        $atts['href']    = ! empty( $item->url )         ? $item->url        : '#';

        if ( $depth === 0 ) {
            $atts['class'] = 'nav-link';
            if ( $args->walker->has_children ) {
                $atts['aria-haspopup'] = 'true';
                $atts['aria-expanded'] = 'false';
            }
        } elseif ( $inside_mega && $depth === 1 ) {
            // Column header inside mega menu — styled as section title
            $atts['class'] = 'nav-link mega-col-header';
            if ( $args->walker->has_children ) {
                $atts['aria-haspopup'] = 'true';
                $atts['aria-expanded'] = 'false';
            }
        } else {
            $atts['class'] = 'dropdown-item';
        }

        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( $value !== '' && $value !== null && $value !== false ) {
                $value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        // ---- Icon (from CSS class on the menu item) ----
        $original_classes = empty( $item->classes ) ? [] : (array) $item->classes;
        $icon_classes     = [];
        foreach ( $original_classes as $c ) {
            $c = trim( $c );
            if ( strpos( $c, 'fa-' ) !== false
                || strpos( $c, 'fas' ) === 0
                || strpos( $c, 'fab' ) === 0
                || strpos( $c, 'far' ) === 0 ) {
                $icon_classes[] = $c;
            }
        }
        $icon_html = ! empty( $icon_classes )
            ? '<i class="' . esc_attr( implode( ' ', $icon_classes ) ) . '" aria-hidden="true"></i> '
            : '';

        // ---- Badge ----
        $badge_html = '';
        if ( in_array( 'badge-info', $original_classes ) ) {
            $badge_html = ' <span class="badge badge-info">Info</span>';
        } elseif ( in_array( 'badge-new', $original_classes ) ) {
            $badge_html = ' <span class="badge badge-new">Baru</span>';
        }

        $title = apply_filters( 'nav_menu_item_title',
            apply_filters( 'the_title', $item->title, $item->ID ),
            $item, $args, $depth
        );

        $item_output  = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $icon_html . $args->link_before . $title . $args->link_after;
        $item_output .= $badge_html;

        // Chevron arrow — top-level with children, OR mega column header with children
        if ( $args->walker->has_children && $depth === 0 ) {
            $item_output .= ' <i class="fas fa-chevron-down dropdown-icon" aria-hidden="true"></i>';
        }

        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    public function start_lvl( &$output, $depth = 0, $args = [] ) {
        $indent = str_repeat( "\t", $depth );
        // depth 0 → main dropdown; depth 1+ → sub-list inside mega column
        $class = ( $depth === 0 ) ? 'dropdown-menu' : 'dropdown-menu sub-menu';
        $output .= "\n$indent<ul class=\"" . esc_attr( $class ) . "\">\n";
    }

    public function end_lvl( &$output, $depth = 0, $args = [] ) {
        $indent  = str_repeat( "\t", $depth );
        $output .= "$indent</ul>\n";
    }

    public function end_el( &$output, $item, $depth = 0, $args = [] ) {
        $output .= "</li>\n";
    }
}

