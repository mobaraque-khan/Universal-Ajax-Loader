jQuery(document).ready(function($) {
    let currentPage = 0;

    function loadPosts() {
        currentPage++;
        $.ajax({
            type: 'POST',
            url: ual_ajax.ajax_url,
            data: {
                action: 'ual_load_posts',
                paged: currentPage
            },
            success: function(response) {
                if (response.trim() === '' || response.includes("আর কোনো পোস্ট নেই")) {
                    $('#ual-load-btn').hide();
                }
                $('#ual-output').append(response);
            }
        });
    }

    // First load
    loadPosts();

    // Button click
    $('#ual-load-btn').on('click', function() {
        loadPosts();
    });
});
