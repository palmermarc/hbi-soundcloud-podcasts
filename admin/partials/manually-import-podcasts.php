<div class="wrap">
    <h2>Manually Import SoundCloud Audio</h2>
    <?php if( $_SERVER['REQUEST_METHOD'] == 'POST' ) :
        if ( ! empty( $_POST ) && check_admin_referer( 'manually_import', 'hbi_soundcloud_podcasts' ) ) {
            do_action( 'import_podcasts_from_soundcloud' );
            echo '<h1>The import process has been started. Depending on the amount of files being imported, this could take a while.</h1>';
        } 
    endif; ?>
    <p>Looking to force the plugin to pull in the audio? Click the button below, and the process will begin!</p>
    <form action="" method="post">
        <?php wp_nonce_field( 'manually_import', 'hbi_soundcloud_podcasts' ); ?>
        <input class="button button-primary" type="submit" name="submit" value="Start Importing" />
    </form>
</div>
