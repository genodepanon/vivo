<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>VIVO V12020 · AUTHENTICATION</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* MODERN RED PALETTE — minimal, bold, no extra decorative fluff */
        body {
            background: #1a0406;   /* deep red-black base, gives modern edge */
            font-family: system-ui, 'Segoe UI', 'Inter', -apple-system, BlinkMacSystemFont, 'Roboto', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        /* CARD-LIKE CONTAINER – clean, sharp, red focus */
        .auth-panel {
            background: #0f0203;    /* extremely dark red background for depth */
            border: 1px solid #e03a3e;  /* vivid red border – modern accent */
            border-radius: 2rem;
            width: 100%;
            max-width: 560px;
            padding: 2.2rem 2rem 2.5rem 2rem;
            box-shadow: 0 20px 35px -12px rgba(220, 30, 40, 0.25);
            transition: all 0.2s ease;
        }

        /* STATUS HEADER – ACTIVE indicator, green with BLINKING effect */
        .device-header {
            display: flex;
            align-items: baseline;
            flex-wrap: wrap;
            justify-content: space-between;
            border-bottom: 2px solid #e03a3e;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }

        .device-model {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.3px;
            background: linear-gradient(135deg, #ff5a5f 0%, #e11d24 100%);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        /* GREEN ACTIVE BADGE with BLINKING animation */
        .active-badge {
            background: #0a5e1a;
            color: #e6ffed;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.3rem 0.9rem;
            border-radius: 40px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border: 1px solid #34d95b;
            backdrop-filter: blur(2px);
            box-shadow: 0 0 4px rgba(52, 217, 91, 0.3);
            animation: blinkGreen 1.2s infinite ease-in-out;
        }

        /* BLINKING EFFECT for ACTIVE status */
        @keyframes blinkGreen {
            0% {
                background-color: #0a5e1a;
                box-shadow: 0 0 2px #34d95b;
                opacity: 1;
            }
            50% {
                background-color: #20b843;
                box-shadow: 0 0 10px #4eff7a;
                color: #ffffff;
                border-color: #8effaa;
            }
            100% {
                background-color: #0a5e1a;
                box-shadow: 0 0 2px #34d95b;
                opacity: 1;
            }
        }

        /* INPUT FIELD — 40key authentication */
        .auth-field {
            margin-bottom: 2rem;
        }

        label {
            display: block;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            color: #f3a6aa;
            margin-bottom: 0.6rem;
        }

        .key-input {
            width: 100%;
            background: #1f070a;
            border: 1.5px solid #ac2c33;
            border-radius: 1.2rem;
            padding: 1rem 1.2rem;
            font-size: 1rem;
            font-family: 'SF Mono', 'Fira Code', monospace;
            color: #ffc9cc;
            transition: all 0.2s;
            outline: none;
            letter-spacing: 0.3px;
        }

        .key-input:focus {
            border-color: #ff4d54;
            box-shadow: 0 0 0 3px rgba(224, 58, 62, 0.3);
            background: #1f0508;
        }

        .key-input::placeholder {
            color: #ac5e63;
            font-family: system-ui, monospace;
            font-size: 0.85rem;
            letter-spacing: 0.3px;
        }

        /* MODERN RED SUBMIT BUTTON */
        .submit-btn {
            width: 100%;
            background: #d92b33;
            border: none;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            border-radius: 2.5rem;
            cursor: pointer;
            transition: 0.2s;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-family: inherit;
            box-shadow: 0 4px 10px rgba(210, 30, 40, 0.4);
        }

        .submit-btn:hover {
            background: #ff2a35;
            transform: scale(0.98);
            box-shadow: 0 6px 14px rgba(230, 40, 50, 0.5);
        }

        .submit-btn:active {
            transform: scale(0.97);
        }

        /* no extra text/hints — completely removed per request */
        
        input, button {
            -webkit-appearance: none;
            appearance: none;
        }

        @media (max-width: 480px) {
            .auth-panel {
                padding: 1.6rem;
            }
            .device-model {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>

<div class="auth-panel">
    <div class="device-header">
        <span class="device-model">VIVO V12020</span>
        <span class="active-badge">● ACTIVE</span>
    </div>
    
    <!-- removed all extra text: no "secure handshake" line, no hint footer -->
    
    <div class="auth-field">
        <label for="authKey">AUTHENTICATION KEY</label>
        <input type="text" id="authKey" class="key-input" 
               placeholder="40key authentication" 
               autocomplete="off" spellcheck="false">
    </div>

    <button type="button" id="submitAuthBtn" class="submit-btn">✓ SUBMIT</button>
    <!-- footer completely eliminated (no extra text) -->
</div>

<script>
    (function() {
        // DOM elements
        const inputField = document.getElementById('authKey');
        const submitButton = document.getElementById('submitAuthBtn');

        // Helper: validate 40-key authentication (exactly 40 characters, ignoring hyphens & spaces)
        // Based on specification: "40key authentication" — we enforce 40 valid chars after cleaning.
        // Clean strategy: remove all whitespace and hyphens, then check length === 40.
        function normalizeAndValidate(keyRaw) {
            if (!keyRaw || typeof keyRaw !== 'string') return false;
            // remove all whitespace (spaces, tabs, newlines, etc.)
            let noWhitespace = keyRaw.replace(/\s+/g, '');
            // remove hyphens (common separators in keys)
            let cleaned = noWhitespace.replace(/-/g, '');
            // check if cleaned length is exactly 40 characters
            return cleaned.length === 40;
        }

        // handle form submission: validate 40key and redirect to nextweb.html (updated target)
        function handleSubmit() {
            const rawValue = inputField.value;
            if (!rawValue || rawValue.trim() === "") {
                alert("⚠️ AUTHENTICATION REQUIRED · Enter 40‑key code");
                inputField.focus();
                return;
            }
            
            const isValid = normalizeAndValidate(rawValue);
            if (!isValid) {
                alert("❌ INVALID 40‑KEY AUTHENTICATION\n\nKey must contain exactly 40 characters (letters/numbers).\nHyphens and spaces are ignored.\nExample: 40-character code like a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0");
                inputField.classList.add('error-shake');
                setTimeout(() => {
                    inputField.classList.remove('error-shake');
                }, 400);
                inputField.select();
                return;
            }
            
            // ✅ VALID 40-key authentication — redirect to nextweb.html (as requested)
            window.location.href = "nextweb.html";
        }
        
        // attach click event
        submitButton.addEventListener('click', handleSubmit);
        
        // enable "Enter" key on input field for quick submission
        inputField.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                handleSubmit();
            }
        });
        
        // minimal error shake effect (modern red but not overdesign)
        const styleShake = document.createElement('style');
        styleShake.textContent = `
            .error-shake {
                animation: redShake 0.25s ease-in-out 0s 2;
                border-color: #ff3b3f !important;
                background-color: #2e0c0f !important;
            }
            @keyframes redShake {
                0% { transform: translateX(0px); }
                25% { transform: translateX(5px); }
                75% { transform: translateX(-5px); }
                100% { transform: translateX(0px); }
            }
        `;
        document.head.appendChild(styleShake);
        
        // auto-focus on input for better UX
        inputField.focus();
    })();
</script>
</body>
</html>
