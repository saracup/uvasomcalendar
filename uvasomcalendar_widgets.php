<?php
/*
Plugin Name: UVA Health/School of Medicine Calendar
Plugin URI: http://technology.med.virginia.edu
Description: A calendar parser for the RSS calendar feed for the UVA Health System
Version: 0.1
Author: Cathy Finn-Derecki
Author URI: http://transparentuniversity.com
Copyright 2012  Cathy Finn-Derecki  (email : cad3r@virginia.edu)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class UVASOMCalendar {
        function format_title ( $title ) {
                $this->title = htmlspecialchars_decode( $title );
                $frags = explode( ' - ', $this->title );
                if ( isset( $frags[1] ) ) $title = str_replace( '"', "", $frags[1] );
                return $title;
        }

        function format_description ( $description, $limit ) {

                // remove title from description
                $description = str_replace( $this->title, '', $description );
				//remove brackets from location
 				$description= str_replace("[", "<span style=\"font-weight:700;\">Where:</span> ", $description);
				$description= str_replace("]", "<br />", $description);
               // split on space to get words
                $words = explode( ' ', $description );
                $total_words = count( $words );
                $description = array_slice( $words, 0, $limit );
                unset( $words );

                // add ellipses
                if ( $total_words > count( $description ) && count( $description ) > 1 ) $description[$limit-1] .= '...';
                $description = implode( ' ', $description );
                return $description;
        }

}

class UVASOMCalendarWidget extends WP_Widget {

	const nspace = 'uvasomcalendar';

        /**
        *Constructor
        *
        *@return void
        *@since 1.0
        */
      	function __construct() {
      		parent::__construct(
      			'UVASOMCalendarWidget', // Base ID
      			'UVASOM Calendar Widget', // Name
      			array( 'description' => 'UVASOM Calendar Widget' ) // Args
      		);
        //function UVASOMCalendarWidget() {
                //parent::WP_Widget( false, $name = 'UVASOM Calendar Widget' );
        }

        /**
        *Get namespace function
        *
        *@return void
        *@since 1.0
        */
	function get_name_space() {
		return self::nspace;
	}

        /**
        *Widget function
        *
        *@return void
        *@since 1.0
        */
        function widget( $args, $instance ) {
                extract( $args );
                         $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( '', $this->get_name_space() ) : $instance['title'], $instance, $this->id_base);
                echo $before_widget;
                         if (!empty($instance['title'])):echo $before_title; endif;
						 //echo htmlspecialchars_decode( $title );
                         echo $title;
						 if (!empty($instance['all_link'])) {
						 echo '<span class="widgettitle-right"><a href="'.$instance['all_link'].'">See all &gt;</a></span>';
						 }
                         if (!empty($instance['title'])):echo $after_title; endif;
                echo $this->content( $instance );
                echo $after_widget;
        }

        /**
        *Content function
        *
        *@return void
        *@since 1.0
        */
        function content ( $instance ) {
                include_once(ABSPATH . WPINC . '/feed.php');
                $feed_list = array();
                for ( $i = 1; $i <= 3; $i++ ) {
                        if ( strlen( $instance['url' . $i] ) ) $feed_list[] = $instance['url' . $i];
                }
                if ( ! count( $feed_list ) ) return;
                ob_start();
                if ( $rss = fetch_feed( $feed_list ) ) {
                        if ( ! is_wp_error( $rss ) ) {
                                $rss_items = $rss->get_items( 0, $rss->get_item_quantity() );
				$rss_items = array_reverse( $rss_items );
                                $limit = 5;
                                if ( $instance['qty'] > 0 ) $limit = $instance['qty'];
                                $title_limit = 500;
                                if ( $instance['title_limit'] > 0 ) $title_limit = $instance['title_limit'];
                                $desc_limit = 10;
                                if ( $instance['desc_limit'] > 0 ) $desc_limit = $instance['desc_limit'];
                                $uvasomcalendar = new UVASOMCalendar();
                                include_once (WP_PLUGIN_DIR.'/uvasomcalendar/calendar_widget.php');
                        }
                }
                return ob_get_clean();
        }

        /**
        *Update function
        *
        *@return void
        *@since 1.0
        */
        function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title'] = $new_instance['title'];
                $instance['title_limit'] = $new_instance['title_limit'];
                $instance['desc_limit'] = $new_instance['desc_limit'];
                $instance['all_link'] = $new_instance['all_link'];
                $instance['qty'] = $new_instance['qty'];
                $instance['url1'] = strip_tags( $new_instance['url1'] );
                $instance['url2'] = strip_tags( $new_instance['url2'] );
                $instance['url3'] = strip_tags( $new_instance['url3'] );
                return $instance;
        }

        /**
        *Form function
        *
        *@return void
        *@since 1.0
        */
        function form( $instance ) {
                $title = esc_attr( $instance['title'] );
                $title_limit = esc_attr( $instance['title_limit'] );
                $desc_limit = esc_attr( $instance['desc_limit'] );
                $all_link = esc_attr( $instance['all_link'] );
                $qty = esc_attr( $instance['qty'] );
                $url1 = esc_attr( $instance['url1'] );
                $url2 = esc_attr( $instance['url2'] );
                $url3 = esc_attr( $instance['url3'] );

?>
        <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', $this->get_name_space() ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>">
        </p>
        <p>
                <label for="<?php echo $this->get_field_id( 'title_limit' ); ?>"><?php _e( 'Title Limit (# characters):', $this->get_name_space() ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title_limit' ); ?>" name="<?php echo $this->get_field_name( 'title_limit' ); ?>" type="text" value="<?php echo $title_limit; ?>"><br>
                <em><?php _e( 'Only events with titles less than the number of characters above will be displayed.  Default is 500.', $this->get_name_space() ); ?></em>
        </p>
        <p>
                <label for="<?php echo $this->get_field_id( 'desc_limit' ); ?>"><?php _e( 'Description Limit (# words):', $this->get_name_space() ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'desc_limit' ); ?>" name="<?php echo $this->get_field_name( 'desc_limit' ); ?>" type="text" value="<?php echo $desc_limit; ?>"><br>
                <em><?php _e( 'Limit the number of words for descriptions.', $this->get_name_space() ); ?></em>
        </p>
        <p>
                <label for="<?php echo $this->get_field_id( 'qty' ); ?>"><?php _e( 'Number of Events:', $this->get_name_space() ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'qty' ); ?>" name="<?php echo $this->get_field_name( 'qty' ); ?>" type="text" value="<?php echo $qty; ?>">
        </p>
        <p>
                <label for="<?php echo $this->get_field_id( 'url1' ); ?>"><?php _e( 'RSS URL 1:', $this->get_name_space() ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'url1' ); ?>" name="<?php echo $this->get_field_name( 'url1' ); ?>" type="text" value="<?php echo $url1; ?>">
        </p>
        <p>
                <label for="<?php echo $this->get_field_id( 'url2' ); ?>"><?php _e( 'RSS URL 2:', $this->get_name_space() ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'url2' ); ?>" name="<?php echo $this->get_field_name( 'url2' ); ?>" type="text" value="<?php echo $url2; ?>">
        </p>
        <p>
                <label for="<?php echo $this->get_field_id( 'url3' ); ?>"><?php _e( 'RSS URL 13:', $this->get_name_space() ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'url3' ); ?>" name="<?php echo $this->get_field_name( 'url3' ); ?>" type="text" value="<?php echo $url3; ?>">
        </p>
        <p>
                <label for="<?php echo $this->get_field_id( 'all_link' ); ?>"><?php _e( 'Link to All Events (Optional):', $this->get_name_space() ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'all_link' ); ?>" name="<?php echo $this->get_field_name( 'all_link' ); ?>" type="text" value="<?php echo $all_link; ?>">
        </p>
<?php
        }
} // class UVASOMCalendarWidget

add_action( 'widgets_init', create_function( '', 'return register_widget( "UVASOMCalendarWidget" );' ) );
function return_1800( $seconds )
{
  return 1800;
}



function uvasomcalendar_get_calendar( $atts ) {
    include_once(ABSPATH . WPINC . '/feed.php');
	add_filter( 'wp_feed_cache_transient_lifetime' , 'return_1800' );
        if ( ! strlen( $atts['url'] ) ) return;
        $url = htmlspecialchars_decode( $atts['url'] );
        ob_start();
        if ( $rss = fetch_feed( $url ) ) {
                if ( ! is_wp_error( $rss ) ) {
                        $rss_items = $rss->get_items( 0, $rss->get_item_quantity() );
			$rss_items = array_reverse( $rss_items );
                        $limit = $atts['numberevents'];
                        $title_limit = 2000;
                        $uvasomcalendar = new UVASOMCalendar();
                        include_once (WP_PLUGIN_DIR.'/uvasomcalendar/calendar_widget.php');
                }
        }
        return ob_get_clean();
	remove_filter( 'wp_feed_cache_transient_lifetime' , 'return_1800' );
}
add_shortcode( 'UVASOMCAL', 'uvasomcalendar_get_calendar' );

function uvasomcalendar_styles() {
?>
<link rel="stylesheet" id="uvasomcalendar" href="<?php echo plugin_dir_url(__FILE__ );?>style.css" type="text/css" media="screen" />
<?php
}
add_action('wp_head', 'uvasomcalendar_styles',18);
/*********add jquery for megamenu toggle **************/
function uvasomcalendar_scripts() {
	wp_enqueue_script( 'uvasomcalendar_ical',plugins_url().'/uvasomcalendar/uvasomcalendar_ical.js',array('jquery'),'',false );
}
//add_action('wp_enqueue_scripts', 'uvasomcalendar_scripts');
/*********load the plugin stylesheets*************/
