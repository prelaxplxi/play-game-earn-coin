<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs - Play Game & Earn Coin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; background-color: #f8f9fa; }
        .faq-container { max-width: 800px; margin: 50px auto; padding: 20px; }
        h1 { font-weight: 700; margin-bottom: 30px; color: #1a1a1a; text-align: center; }
        .accordion-item { border: none; margin-bottom: 15px; border-radius: 12px !important; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .accordion-button { font-weight: 600; padding: 20px; background-color: #fff; color: #2c3e50; }
        .accordion-button:not(.collapsed) { background-color: #fff; color: #0d6efd; box-shadow: none; }
        .accordion-button:focus { box-shadow: none; border-color: rgba(0,0,0,.125); }
        .accordion-body { padding: 20px; background-color: #fff; border-top: 1px solid #f1f1f1; color: #555; }
        .category-title { font-weight: 700; font-size: 1.1rem; margin-top: 30px; margin-bottom: 15px; color: #6c757d; text-transform: uppercase; letter-spacing: 1px; }
        .footer { margin-top: 50px; text-align: center; color: #7f8c8d; font-size: 0.9em; padding-bottom: 30px; }
        .border-left-right { border-left: 1px solid #000000; border-right: 1px solid #000000;padding: 5px 30px; }   
    </style>
</head>
<body>
    <div class="faq-container">
        <h1>Frequently Asked Questions</h1>

        <div class="category-title"><i class="fas fa-user-plus me-2"></i> Getting Started</div>
        <div class="accordion" id="accordionGeneral">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#q1">
                        How can I sign up on the app?
                    </button>
                </h2>
                <div id="q1" class="accordion-collapse collapse show" data-bs-parent="#accordionGeneral">
                    <div class="accordion-body">
                        You can sign up using two methods:
                        <ul>
                            <li><strong>Normal Registration:</strong> Enter your name, email, and password.</li>
                            <li><strong>Gmail Registration:</strong> Fast sign-in using your Google account.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q2">
                        Why should I provide my phone number?
                    </button>
                </h2>
                <div id="q2" class="accordion-collapse collapse" data-bs-parent="#accordionGeneral">
                    <div class="accordion-body">
                        Phone number verification helps us secure your account and ensures that rewards are delivered correctly to your linked payment methods (like UPI or Bank).
                    </div>
                </div>
            </div>
        </div>

        <div class="category-title"><i class="fas fa-coins me-2"></i> Earnings & Rewards</div>
        <div class="accordion" id="accordionEarnings">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q3">
                        What are the ways to earn coins?
                    </button>
                </h2>
                <div id="q3" class="accordion-collapse collapse" data-bs-parent="#accordionEarnings">
                    <div class="accordion-body">
                        You can earn coins by completing simple offers, playing games, and referring the app to your friends.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q4">
                        How many coins make 1 Rupee?
                    </button>
                </h2>
                <div id="q4" class="accordion-collapse collapse" data-bs-parent="#accordionEarnings">
                    <div class="accordion-body">
                        The current conversion rate is <strong>10 coins = 1 Rupee</strong>.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q5">
                        When will coins be added to my wallet?
                    </button>
                </h2>
                <div id="q5" class="accordion-collapse collapse" data-bs-parent="#accordionEarnings">
                    <div class="accordion-body">
                        Coins are usually added instantly after we verify task completion. In some cases, it may take up to 24 hours for final verification.
                    </div>
                </div>
            </div>
        </div>

        <div class="category-title"><i class="fas fa-share-nodes me-2"></i> Referrals</div>
        <div class="accordion" id="accordionReferrals">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q6">
                        How can I refer my friends?
                    </button>
                </h2>
                <div id="q6" class="accordion-collapse collapse" data-bs-parent="#accordionReferrals">
                    <div class="accordion-body">
                        Go to your profile section to find your unique 6-character referral code. You can copy and share this code with your friends via any messaging app.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q7">
                        What rewards do I get for referring?
                    </button>
                </h2>
                <div id="q7" class="accordion-collapse collapse" data-bs-parent="#accordionReferrals">
                    <div class="accordion-body">
                        When your friend completes their first 3 offers, you will receive a bonus equal to <strong>50% of the coins</strong> they earned from those offers.
                    </div>
                </div>
            </div>
        </div>

        <div class="category-title"><i class="fas fa-wallet me-2"></i> Withdrawals</div>
        <div class="accordion" id="accordionWithdraw">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q8">
                        How long does it take to transfer money to my bank?
                    </button>
                </h2>
                <div id="q8" class="accordion-collapse collapse" data-bs-parent="#accordionWithdraw">
                    <div class="accordion-body">
                        Transfers are usually instant. However, depending on bank processing times, it might occasionally take up to 72 hours.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q9">
                        Why was my withdrawal failed?
                    </button>
                </h2>
                <div id="q9" class="accordion-collapse collapse" data-bs-parent="#accordionWithdraw">
                    <div class="accordion-body">
                        Common reasons include:
                        <ul>
                            <li>Incorrect UPI ID or Bank details provided.</li>
                            <li>Your bank account is inactive or has limits.</li>
                            <li>Suspicious activity detected on your account.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Play Game & Earn Coin. All rights reserved.</p>
            <p>
                <a href="{{ route('privacy-policy') }}" class="text-decoration-none me-3">Privacy Policy</a>
                <a href="{{ route('faqs') }}" class="text-decoration-none me-3 border-left-right">FAQs</a>
                <a href="{{ route('terms-conditions') }}" class="text-decoration-none">Terms & Conditions</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
