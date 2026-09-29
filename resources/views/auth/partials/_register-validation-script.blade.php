<script>
    function togglePwd(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = icon.className.replace('ph-eye', 'ph-eye-slash');
        } else {
            input.type = 'password';
            icon.className = icon.className.replace('ph-eye-slash', 'ph-eye');
        }
    }

    function showError(id, msg) {
        const el = document.getElementById(id);
        if (!el) return;
        if (msg) {
            el.textContent = '⚠️ ' + msg;
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
            el.textContent = '';
        }
    }

    function valName(value, prefix) {
        const errId = prefix + '_name_error';
        if (!value) {
            showError(errId, '');
            return;
        }
        if (value.length < 3) {
            showError(errId, 'Nama minimal 3 karakter');
            return;
        }
        if (/\d/.test(value)) {
            showError(errId, 'Nama tidak boleh mengandung angka');
            return;
        }
        showError(errId, '');
    }

    function valEmail(value, prefix) {
        const errId = prefix + '_email_error';
        if (!value) {
            showError(errId, '');
            return;
        }
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!re.test(value)) {
            showError(errId, 'Format email tidak valid. Contoh: nama@email.com');
            return;
        }
        showError(errId, '');
    }

    function valWA(value, prefix) {
        const errId = prefix + '_wa_error';
        const checkEl = document.getElementById(prefix + '_wa_check');
        if (!value) {
            showError(errId, '');
            if (checkEl) checkEl.classList.add('hidden');
            return;
        }
        const digits = value.replace(/\D/g, '');
        const fullRegex = /^(\+62|62|0)8[1-9][0-9]{7,10}$/;

        if (!/^(\+62|62|0)8/.test(value)) {
            showError(errId, 'Format nomor tidak valid. Gunakan format: 08xxxxxxxxxx');
            if (checkEl) checkEl.classList.add('hidden');
            return;
        }
        if (digits.length < 10) {
            showError(errId, 'Nomor WhatsApp minimal 10 digit');
            if (checkEl) checkEl.classList.add('hidden');
            return;
        }
        if (digits.length > 13) {
            showError(errId, 'Nomor WhatsApp maksimal 13 digit');
            if (checkEl) checkEl.classList.add('hidden');
            return;
        }
        if (!fullRegex.test(value)) {
            showError(errId, 'Format nomor tidak valid. Gunakan format: 08xxxxxxxxxx');
            if (checkEl) checkEl.classList.add('hidden');
            return;
        }
        showError(errId, '');
        if (checkEl) checkEl.classList.remove('hidden');
    }

    function valPassword(value, prefix) {
        const checklist = document.getElementById(prefix + '_pwd_checklist');
        if (!checklist) return;

        if (!value) {
            checklist.classList.add('hidden');
            return;
        }
        checklist.classList.remove('hidden');

        const chkMin = document.getElementById(prefix + '_pwd_chk_minlen');
        const chkMax = document.getElementById(prefix + '_pwd_chk_maxlen');
        const chkUpper = document.getElementById(prefix + '_pwd_chk_upper');
        const chkLower = document.getElementById(prefix + '_pwd_chk_lower');

        if (chkMin) toggleCheck(chkMin, value.length >= 9);
        if (chkMax) toggleCheck(chkMax, value.length <= 20);
        if (chkUpper) toggleCheck(chkUpper, /[A-Z]/.test(value));
        if (chkLower) toggleCheck(chkLower, /[a-z]/.test(value));

        const confirmId = (prefix === 'm' ? 'm_pwd_conf' : 'd_pwd_conf');
        const confirmEl = document.getElementById(confirmId);
        if (confirmEl && confirmEl.value) {
            valConfirm(confirmEl.value, (prefix === 'm' ? 'm_pwd' : 'd_pwd'), prefix);
        }
    }

    function toggleCheck(el, met) {
        if (met) {
            el.classList.add('text-green-500');
            el.querySelectorAll('.circle-icon').forEach(i => i.classList.add('hidden'));
            el.querySelectorAll('.check-icon').forEach(i => i.classList.remove('hidden'));
        } else {
            el.classList.remove('text-green-500');
            el.querySelectorAll('.circle-icon').forEach(i => i.classList.remove('hidden'));
            el.querySelectorAll('.check-icon').forEach(i => i.classList.add('hidden'));
        }
    }

    function valConfirm(value, pwdId, prefix) {
        const errId = prefix + '_pwd_conf_error';
        const checkEl = document.getElementById(prefix + '_pwd_conf_check');
        const pwdEl = document.getElementById(pwdId);

        if (!value) {
            showError(errId, '');
            if (checkEl) checkEl.classList.add('hidden');
            return;
        }

        const pwdValue = pwdEl ? pwdEl.value : '';
        if (value !== pwdValue) {
            showError(errId, 'Kata sandi tidak cocok, silakan periksa kembali');
            if (checkEl) checkEl.classList.add('hidden');
            return;
        }

        showError(errId, '');
        if (checkEl) checkEl.classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const mPwd = document.getElementById('m_pwd');
        const dPwd = document.getElementById('d_pwd');
        const mConf = document.getElementById('m_pwd_conf');
        const dConf = document.getElementById('d_pwd_conf');

        if (mPwd && mConf) {
            mPwd.addEventListener('input', function () {
                if (mConf.value) valConfirm(mConf.value, 'm_pwd', 'm');
            });
        }
        if (dPwd && dConf) {
            dPwd.addEventListener('input', function () {
                if (dConf.value) valConfirm(dConf.value, 'd_pwd', 'd');
            });
        }
    });
</script>
