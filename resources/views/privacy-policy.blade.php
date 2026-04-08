<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Play Game & Earn Coin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; }
        .policy-container { max-width: 800px; margin: 50px auto; padding: 20px; }
        h1 { font-weight: 700; margin-bottom: 30px; color: #1a1a1a; }
        h4 { font-weight: 600; margin-top: 25px; margin-bottom: 15px; color: #2c3e50; }
        p, ul { margin-bottom: 15px; }
        .footer { margin-top: 50px; text-align: center; color: #7f8c8d; font-size: 0.9em; }
        .border-left-right { border-left: 1px solid #000000; border-right: 1px solid #000000;padding: 5px 30px; }
    </style>
</head>
<body>
    <div class="policy-container shadow-sm bg-white rounded">
        <h1>Privacy Policy</h1>
        <p>As we update, improve and expand <strong>Play Game & Earn Coin</strong> services, this policy may change, so please refer to it periodically. By accessing the website/app or otherwise using our services, you give us your consent to collect, store, and use the information you provide for any of the services that we offer.</p>
        
        <p><strong>Note:</strong> Our privacy policy may change at any time without prior notification. To make sure that you are aware of any changes, kindly review the policy periodically.</p>

        <h4>Information you give us to avail our services</h4>
        <p>You are required to provide the following information for the registration/login process:</p>
        <ul>
            <li><strong>Mobile Number:</strong> We verify and validate the mobile number provided by you as all rewards are given based on this number. It is also used for account-related queries and communicating custom offers.</li>
            <li><strong>Email Address:</strong> Your email address is used to communicate service alerts and marketing messages relevant to you. We verify email addresses through third-party services to detect fraud.</li>
            <li><strong>Name:</strong> Your name is used to verify your identity and ensure genuine usage of the application.</li>
            <li><strong>Bank Account Information:</strong> Used to ensure payout is provided only to accounts that match your verified identity.</li>
        </ul>
        <p>This information is stored in an encrypted format. All required information is service-dependent and we may use this info to maintain, protect, and improve our services and for developing new services.</p>

        <h4>Information we capture/track</h4>
        <p>With your permission, we capture information to provide customized services and reduce fraud:</p>
        <ul>
            <li><strong>Device Information:</strong> Hardware model, operating system version, unique device identifiers, and mobile network information. Used primarily to detect unauthorized usage and track app activity.</li>
            <li><strong>Log Information:</strong> Details of how you used our service, IP address, system crashes, and cookies that identify your browser or account. SMS messages are only read for OTP verification purposes.</li>
            <li><strong>Location Information:</strong> We may collect and process information about your location using GPS, IP address, and other sensors to offer tailored rewards.</li>
        </ul>

        <h4>Information we share</h4>
        <p>We do not share your personal information with companies, organizations, and individuals outside of <strong>Play Game & Earn Coin</strong> unless one of the following circumstances applies:</p>
        <ul>
            <li><strong>With your consent:</strong> Sharing device-level information for analytics and marketing purposes after you agree to this policy.</li>
            <li><strong>For legal reasons:</strong> Sharing info if it's reasonably necessary to meet any applicable law, regulation, or legal process.</li>
        </ul>

        <h4>Cookies and Local Storage</h4>
        <p>We use cookies and local storage (caches) to maintain your login session and sync data with our servers. These technologies help us improve your user experience and the overall quality of our services.</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Play Game & Earn Coin. All rights reserved.</p>
            <p>
                <a href="{{ route('privacy-policy') }}" class="text-decoration-none me-3">Privacy Policy</a>
                <a href="{{ route('faqs') }}" class="text-decoration-none me-3 border-left-right">FAQs</a>
                <a href="{{ route('terms-conditions') }}" class="text-decoration-none">Terms & Conditions</a>
            </p>
        </div>
    </div>
</body>
</html>
