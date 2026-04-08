<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions - Play Game & Earn Coin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; }
        .policy-container { max-width: 800px; margin: 50px auto; padding: 20px; }
        h1 { font-weight: 700; margin-bottom: 30px; color: #1a1a1a; }
        h4 { font-weight: 600; margin-top: 25px; margin-bottom: 15px; color: #2c3e50; }
        p, ul { margin-bottom: 15px; }
        .footer { margin-top: 50px; text-align: center; color: #7f8c8d; font-size: 0.9em; }
        .section-title { border-bottom: 2px solid #f1f1f1; padding-bottom: 10px; margin-bottom: 20px; }
        .border-left-right { border-left: 1px solid #000000; border-right: 1px solid #000000;padding: 5px 30px; }
    </style>
</head>
<body>
    <div class="policy-container shadow-sm bg-white rounded">
        <h1 class="section-title">Terms and Conditions</h1>

        <h4>1. Introduction</h4>
        <p>These Terms and Conditions (the "Terms") are a binding contract between You ("User", "End User") and <strong>Play Game & Earn Coin</strong> ("Company", "we", "our" and "us"). You must agree to and accept all of the Terms or you don't have the right to use the Services. Your use of the Services implies that you agree to all of these Terms.</p>
        <p>You are not allowed to copy or modify the product/service or our trademarks in any way. You are not allowed to attempt to extract the source code of the product/service or make derivative versions. All intellectual property rights belong to the Company.</p>

        <h4>2. Eligibility</h4>
        <p>Our services are not for use by any minors (those who are not at least 18 years of age). You must not use or access our application if you are a minor.</p>

        <h4>3. Rewards & Coins</h4>
        <p>For every task completed, users are eligible to earn rewards ("Coins"). The Coins earned shall be based solely on the acceptable reports received from our partners. We are not liable for rewards lost due to internet connectivity issues, bugs, incorrect details provided by the user, or inactive wallets.</p>
        <p><strong>Expiration:</strong> Coins credited to the wallet will expire if not utilized within 60 days of accrual. You are advised to redeem your earnings promptly.</p>

        <h4>4. Limitation of Liability</h4>
        <p>In no event shall the Company nor any of its officers be liable to you for anything arising out of or in any way connected with your use of the application. The Company shall not be liable for any indirect, consequential or special liability arising out of or related to your use of the service.</p>

        <h4>5. Termination</h4>
        <p>We reserve the right to terminate your access to the service at any time for any reason, including but not limited to fraudulent activities, disruption of services, or breach of these terms. Suspicious activity found to be hindering the natural functioning of the app will result in immediate termination without notice.</p>

        <h4>6. Referral Program</h4>
        <p>Users are encouraged to use the referral program fairly. Misleading or spammy communication of any nature to promote referral codes on public properties like Facebook or Google Play may lead to account termination.</p>

        <h4>7. Governing Law</h4>
        <p>These terms are governed by the laws of the jurisdiction in which the Company operates. Any disputes shall be subject to the exclusive jurisdiction of the courts in that region.</p>

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
