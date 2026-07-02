document.querySelector("form").addEventListener("submit", function(e){
    e.preventDefault();
    alert("Thank you! We will contact you soon.");
});

// Single banner image preload
(function(){
    const banner = document.querySelector('.hero-right img');
    if(!banner) return;

    const src = banner.getAttribute('src');
    const pre = new Image();
    pre.src = src;
})();

// Copy address action
document.addEventListener('DOMContentLoaded', function(){
    const copyBtn = document.getElementById('copy-address');
    const addressEl = document.getElementById('company-address');
    if(!copyBtn || !addressEl) return;

    copyBtn.addEventListener('click', async function(){
        const text = addressEl.textContent.trim().replace(/\n\s+/g, '\n');

        // Update label helper (preserve SVG)
        const label = copyBtn.querySelector('.action-text') || copyBtn.querySelector('span');
        const oldLabel = label ? label.textContent : copyBtn.textContent;

        // Try Clipboard API first, then fallback to textarea+execCommand
        try{
            if(navigator.clipboard && navigator.clipboard.writeText){
                await navigator.clipboard.writeText(text);
            }else{
                throw new Error('no-clipboard-api');
            }
            copyBtn.classList.add('copied');
            if(label) label.textContent = 'Copied!';
            setTimeout(() => { copyBtn.classList.remove('copied'); if(label) label.textContent = oldLabel; }, 1800);
        }catch(err){
            // fallback: textarea + execCommand
            const textarea = document.createElement('textarea');
            textarea.value = text;
            // place off-screen
            textarea.style.position = 'fixed';
            textarea.style.left = '-9999px';
            textarea.style.top = '0';
            document.body.appendChild(textarea);
            textarea.focus();
            textarea.select();
            try{
                const ok = document.execCommand('copy');
                if(ok){
                    copyBtn.classList.add('copied');
                    if(label) label.textContent = 'Copied!';
                    setTimeout(() => { copyBtn.classList.remove('copied'); if(label) label.textContent = oldLabel; }, 1800);
                }else{
                    throw new Error('exec-failed');
                }
            }catch(e2){
                alert('Copy failed — please select the address text and copy manually.');
            }
            textarea.remove();
        }
    });
});


