    </div><!-- /.main-content -->
</div><!-- /#content-wrapper -->

<!-- Cookies Agreement Pop-up -->
<div id="cookie-banner" style="display: none; position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); width: 90%; max-width: 500px; background: rgba(15, 15, 20, 0.85); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px; padding: 20px; z-index: 9999; box-shadow: 0 10px 30px rgba(0,0,0,0.5); font-family: 'Inter', sans-serif;">
    <div style="display: flex; align-items: flex-start; gap: 16px;">
        <div style="background: rgba(255, 107, 0, 0.1); padding: 10px; border-radius: 12px;">
            <i class="fas fa-cookie-bite" style="color: #ff6b00; font-size: 24px;"></i>
        </div>
        <div style="flex: 1;">
            <h4 style="margin: 0 0 6px 0; font-size: 16px; font-weight: 600; color: #fff;">Cookies & Privacy</h4>
            <p style="margin: 0; font-size: 13px; color: #aaa; line-height: 1.5;">We use cookies to enhance your experience and ensure the portal works smoothly. By continuing, you agree to our use of cookies.</p>
        </div>
    </div>
    <div style="margin-top: 18px; display: flex; justify-content: flex-end;">
        <button id="accept-cookies" style="background: linear-gradient(135deg, #ff6b00, #ff9a00); color: #fff; border: none; border-radius: 10px; padding: 10px 24px; font-size: 13px; font-weight: 600; cursor: pointer; transition: opacity 0.2s;">Got it!</button>
    </div>
</div>

<script src="<?= BASEURL; ?>/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASEURL; ?>/js/script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const banner = document.getElementById('cookie-banner');
    const acceptBtn = document.getElementById('accept-cookies');

    if (!localStorage.getItem('cookies-accepted')) {
        setTimeout(() => {
            banner.style.display = 'block';
            banner.style.animation = 'slideUp 0.5s ease-out forwards';
        }, 1000);
    }

    acceptBtn.addEventListener('click', function() {
        localStorage.setItem('cookies-accepted', 'true');
        banner.style.animation = 'slideDown 0.4s ease-in forwards';
        setTimeout(() => { banner.style.display = 'none'; }, 400);
    });
});
</script>
<style>
@keyframes slideUp { from { transform: translate(-50%, 100%); opacity: 0; } to { transform: translate(-50%, 0); opacity: 1; } }
@keyframes slideDown { from { transform: translate(-50%, 0); opacity: 1; } to { transform: translate(-50%, 100%); opacity: 0; } }
</style>
</body>
</html>
