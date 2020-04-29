<?php
/**
 * Template Name: Daily Roundup Feed
 * Copied from https://raw.githubusercontent.com/pastpages/Largo/master/feed-mailchimp.php
 * A feed with thumbnail images for MailChimp import that pulls in all saved links from 12PM to the following day 12PM
 * Feed address to use for MailChimp import will be http://myurl.com/?feed=daily-roundup
 *
 * @package Largo
 * @since 0.2
 */

$numposts = -1;

function rss_date( $timestamp = null ) {
  $timestamp = ($timestamp==null) ? time() : $timestamp;
  echo date(DATE_RSS, $timestamp);
}

/**
 * Function to see if we want to grab posts from today after 12PM
 * or from yesterday after 12PM until today before 12PM
 * 
 * @return Arr $query_time An array of arguments to use in the query_posts date_query arg.
 */
function rss_posts_date_query() {

    if( current_time( 'H' ) < 12 ) {

        $query_time = array(
            array(
                'before' => date( 'm/d/Y 12:00:00' ),
                'after' => date( 'm/d/Y 12:00:00', strtotime( '-1 days' ) ),
                'inclusive' => false,
            )
        );

    } else {

        $query_time = array(
            array(
                'after' => date( 'm/d/Y 12:00:00' ),
                'inclusive' => true,
            )
        );

    }

    return $query_time;

}

$posts = query_posts( array(
  'showposts' => $numposts,
  'post_type' => 'rounduplink',
  'date_query' => rss_posts_date_query(),
) );

$lastpost = $numposts - 1;

header("Content-Type: application/rss+xml; charset=UTF-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:media="http://search.yahoo.com/mrss/" xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel>
  <title><?php bloginfo_rss('name'); ?></title>
  <link><?php bloginfo_rss('url'); ?></link>
  <description><?php bloginfo_rss('description'); ?></description>
  <language><?php bloginfo('language'); ?></language>
  <pubDate><?php rss_date( strtotime($ps[$lastpost]->post_date_gmt) ); ?></pubDate>
  <lastBuildDate><?php rss_date( strtotime($ps[$lastpost]->post_date_gmt) ); ?></lastBuildDate>
  <managingEditor><?php bloginfo_rss('admin_email'); ?></managingEditor>

<?php foreach ($posts as $post) { ?>
  <item>
    <title><?php the_title_rss(); ?></title>
    <link><?php the_permalink(); ?></link>
    <description><?php echo '<![CDATA[' . largo_excerpt( $post, 5, false, '', false ) . ']]>';  ?></description>
    <pubDate><?php rss_date( strtotime( $post->post_date_gmt ) ); ?></pubDate>
    <guid><?php the_permalink(); ?></guid>
    <source><?php echo get_post_meta( $post->ID, 'lr_source', true ); ?></source>
    <dc:creator><?php $curuser = get_user_by( 'id', $post->post_author ); echo $curuser->first_name . ' ' . $curuser->last_name; ?></dc:creator>
	<?php if( get_the_post_thumbnail( $post->ID ) ): $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) ); ?>
    <media:content url="<?php echo esc_url( $image[0] ); ?>" medium="image" />
	<?php endif; ?>
  </item>
<?php } // foreach ?>
</channel>
</rss>