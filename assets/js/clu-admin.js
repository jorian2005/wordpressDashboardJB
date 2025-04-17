jQuery(document).ready(function($) {
    var mediaUploader;

    $('#clu_upload_background_button').click(function(e) {
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
            $('#clu_login_background_image').val(attachment.url);
            $('#clu_background_preview').attr('src', attachment.url).show();
            $('#clu_remove_background_button').show();
        });

        mediaUploader.open();
    });

    $('#clu_remove_background_button').click(function() {
        $('#clu_login_background_image').val('');
        $('#clu_background_preview').hide();
        $(this).hide();
    });
});
