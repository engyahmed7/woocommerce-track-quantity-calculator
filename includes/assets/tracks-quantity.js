
jQuery(function($) {
    function updateTracksPreview() {
        const quantity = parseInt($('#quantity').val(), 10) || 0;
        const maxTracks = parseInt($('#max-tracks').val(), 10) || 1;

        if (maxTracks > 0 && quantity > 0) {
            const tracks = Math.ceil(quantity / maxTracks);
            $('#tracks-preview').html(`<strong>Tracks:</strong> ${tracks}`);
        } else {
            $('#tracks-preview').html('<strong>Tracks: 0</strong>');
        }
    }

    $('#quantity').on('change keyup', updateTracksPreview);

    updateTracksPreview();
});
