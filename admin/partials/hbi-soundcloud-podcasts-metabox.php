<?php 
$podcast_shows = get_terms( 'podcast_show', array( 'hide_empty' => false ) ); 
$current_podcast_show = get_post_meta( $object->ID, 'podcast-show', TRUE );
?>
<p>
    <input type="checkbox" name="use-soundcloud" value="1" <?php if( get_post_meta( $object->ID, 'use-soundcloud', TRUE ) ) echo 'checked="checked"'; ?> />
    <label style="font-weight: bold;" for="use-soundcloud">Use SoundCloud Embed</label>
</p>

<p>
    <label style="font-weight: bold;" style="font-weight: bold;" for="soundcloud-track-id">SoundCloud File ID: </label>
    <input class="widefat" type="text" name="soundcloud-track-id" value="<?php echo get_post_meta( $object->ID, 'soundcloud-track-id', TRUE ); ?>" />
</p>

<p>
    <label style="font-weight: bold;" for="soundcloud-shortcode">SoundCloud ShortCode: </label>
    <input class="widefat" type="text" name="soundcloud-shortcode" value="<?php echo esc_attr( get_post_meta( $object->ID, 'soundcloud-shortcode', TRUE ) ); ?>" />
</p>

<p>
    <label style="font-weight: bold;" for="soundcloud-file">SoundCloud File URL: </label>
    <input class="widefat" type="text" name="soundcloud-file" value="<?php echo get_post_meta( $object->ID, 'soundcloud-file', TRUE ); ?>" />
</p>
<p>
    <label style="font-weight: bold;" for="podcast-show">Podcast Show Slug</label>
    <select name="podcast-show">
        <option value=""></option>
        <?php foreach( $podcast_shows as $show ) : ?>
        <option <?php selected( $show->slug, $current_podcast_show ); ?> value="<?php echo $show->slug; ?>"><?php echo $show->name; ?></option>
        <?php endforeach; ?>
    </select>
</p>