jQuery(function($) {
    function updateTracksPreview() {
        const quantity = $('#quantity').val();
        const maxTracks = $('#quantity').data('max-tracks');
        const tracks = Math.ceil(quantity / maxTracks);
        $('#tracks-preview').html(`<strong>Tracks:</strong> ${tracks}`);
    }

    $('#quantity').on('change', updateTracksPreview);
    updateTracksPreview(); 
});
