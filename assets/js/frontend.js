jQuery(document).ready(function($) {
    let currentPage = 1;
    let loading = false;
    
    const container = $('#ual-container');
    const postsContainer = $('#ual-posts');
    const loadMoreBtn = $('#ual-load-more');
    const loadingDiv = $('#ual-loading');
    
    const postType = container.data('post-type');
    const postsPerPage = container.data('posts-per-page');
    const layout = container.data('layout');

    // Load initial posts
    loadPosts();

    // Load more button click
    loadMoreBtn.on('click', function() {
        currentPage++;
        loadPosts();
    });

    function loadPosts() {
        if (loading) return;
        
        loading = true;
        loadMoreBtn.hide();
        loadingDiv.show();

        $.ajax({
            url: ual_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'ual_load_posts',
                post_type: postType,
                posts_per_page: postsPerPage,
                paged: currentPage,
                layout: layout,
                nonce: ual_ajax.nonce
            },
            success: function(response) {
                if (response.trim()) {
                    postsContainer.append(response);
                    loadMoreBtn.show();
                } else {
                    loadMoreBtn.text('No more posts').prop('disabled', true);
                }
            },
            complete: function() {
                loading = false;
                loadingDiv.hide();
            }
        });
    }
});