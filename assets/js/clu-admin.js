jQuery(document).ready(function($) {
    var mediaUploader;

    $('#JB_login_upload_background_button').click(function(e) {
        e.preventDefault();

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Kies een achtergrondafbeelding',
            button: { text: 'Gebruik deze afbeelding' },
            multiple: false
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#JB_login_login_background_image').val(attachment.url);
            $('#JB_login_background_preview').attr('src', attachment.url).show();
            $('#JB_login_remove_background_button').show();
        });

        mediaUploader.open();
    });

    $('#JB_login_remove_background_button').click(function() {
        $('#JB_login_login_background_image').val('');
        $('#JB_login_background_preview').hide();
        $(this).hide();
    });
});
