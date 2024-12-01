jQuery(function ($) {
    function updateTracksPreview() {
        const quantityField = $('.qty');
        const quantity = parseInt(quantityField.val(), 10) || 0;
        const maxTracks = parseInt($('#max-tracks').val(), 10) || 1;

        if (maxTracks > 0 && quantity > 0) {
            const tracks = Math.ceil(quantity / maxTracks);
            $('#tracks-preview').html(`<strong>Tracks:</strong> ${tracks}`);
        } else {
            $('#tracks-preview').html('<strong>Tracks:</strong> 0');
        }
    }

    function handleVariationChange() {
        const variationId = $('.variation_id').val();
        console.log('variationId: ',variationId);
        if (variationId && tracksData.variationMaxTracks[variationId]) {
            $('#max-tracks').val(tracksData.variationMaxTracks[variationId]);
        } else {
            $('#max-tracks').val(1);
        }
        updateTracksPreview();
    }

    $(document).on('found_variation', '.variations_form', handleVariationChange);

    $(document).on('change', '.variation_id', handleVariationChange);

    $(document).on('input', '.qty', updateTracksPreview);

    updateTracksPreview();
});
