jQuery(document).ready(function($) {
    $('#toggle-maintenance').click(function() {
        let button = $(this);
        let enabled = button.hasClass('button-primary') ? 0 : 1; 

        $.ajax({
            type: 'POST',
            url: maintenanceAjax.ajaxurl,
            data: {
                action: 'toggle_maintenance_mode',
                enabled: enabled,
                security: maintenanceAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    if (enabled) {
                        button.removeClass('button-secondary').addClass('button-primary').text('Onderhoudsmodus Uitschakelen');
                    } else {
                        button.removeClass('button-primary').addClass('button-secondary').text('Onderhoudsmodus Inschakelen');
                    }
                } else {
                    alert('Fout: ' + response.data.message);
                }
            },
            error: function() {
                alert('Er is iets misgegaan.');
            }
        });
    });
});
