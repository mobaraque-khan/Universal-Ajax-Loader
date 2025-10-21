// Universal Ajax Loader Admin Scripts
function ualCopyShortcode() {
    const shortcodeInput = document.getElementById('ual-shortcode');
    const feedback = document.getElementById('ual-copy-feedback');
    
    shortcodeInput.select();
    shortcodeInput.setSelectionRange(0, 99999);
    
    try {
        document.execCommand('copy');
        feedback.classList.add('show');
        setTimeout(() => {
            feedback.classList.remove('show');
        }, 2000);
    } catch (err) {
        console.error('Failed to copy shortcode:', err);
    }
}