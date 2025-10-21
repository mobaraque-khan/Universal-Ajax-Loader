jQuery(document).ready(function($) {
    let currentPage = 0;
    let isLoading = false;
    let hasMorePosts = true;
    const $container = $('.ual-container');
    const isInfiniteScroll = $container.data('infinite') == '1';

    function createSkeleton() {
        return `
            <div class="ual-skeleton">
                <div class="ual-skeleton-image"></div>
                <div class="ual-skeleton-content">
                    <div class="ual-skeleton-title"></div>
                    <div class="ual-skeleton-text"></div>
                    <div class="ual-skeleton-text"></div>
                    <div class="ual-skeleton-meta">
                        <div class="ual-skeleton-meta-item"></div>
                        <div class="ual-skeleton-meta-item"></div>
                    </div>
                </div>
            </div>
        `;
    }

    function loadPosts() {
        if (isLoading || !hasMorePosts) return;
        
        isLoading = true;
        currentPage++;
        
        const $btn = $('#ual-load-btn');
        const $output = $('#ual-output');
        
        // Show skeletons
        const skeletonCount = currentPage === 1 ? 6 : 3;
        const skeletons = Array(skeletonCount).fill().map(() => createSkeleton()).join('');
        $output.append(skeletons);
        
        if (!isInfiniteScroll) {
            $btn.addClass('loading').prop('disabled', true);
        }
        
        $.ajax({
            type: 'POST',
            url: ual_ajax.ajax_url,
            data: {
                action: 'ual_load_posts',
                paged: currentPage
            },
            success: function(response) {
                // Remove skeletons
                $('.ual-skeleton').remove();
                
                if (response.trim() === '' || response.includes("Did not find any posts")) {
                    hasMorePosts = false;
                    if (!isInfiniteScroll) {
                        $btn.addClass('no-more').find('.ual-btn-text').text('No More Items');
                        setTimeout(() => $btn.fadeOut(), 2000);
                    }
                } else {
                    $output.append(response);
                }
                
                if (!isInfiniteScroll) {
                    $btn.removeClass('loading').prop('disabled', false);
                }
                isLoading = false;
            },
            error: function() {
                $('.ual-skeleton').remove();
                if (!isInfiniteScroll) {
                    $btn.removeClass('loading').prop('disabled', false);
                }
                isLoading = false;
            }
        });
    }

    // First load
    loadPosts();

    // Button click (only if not infinite scroll)
    if (!isInfiniteScroll) {
        $('#ual-load-btn').on('click', function() {
            loadPosts();
        });
    }

    // Infinite scroll
    if (isInfiniteScroll) {
        let scrollTimeout;
        $(window).on('scroll', function() {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(function() {
                const scrollTop = $(window).scrollTop();
                const windowHeight = $(window).height();
                const documentHeight = $(document).height();
                
                if (scrollTop + windowHeight >= documentHeight - 200) {
                    loadPosts();
                }
            }, 100);
        });
    }
});
