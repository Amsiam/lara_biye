<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            // Legal Pages
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'category' => 'legal',
                'content' => '<h2>Privacy Policy for Engineer\'s Matrimony</h2>
<p>Last updated: ' . date('F d, Y') . '</p>

<h3>Introduction</h3>
<p>Welcome to Engineer\'s Matrimony. We respect your privacy and are committed to protecting your personal data. This privacy policy will inform you about how we look after your personal data when you visit our website and tell you about your privacy rights and how the law protects you.</p>

<h3>Information We Collect</h3>
<p>We collect and process the following types of personal information:</p>
<ul>
    <li><strong>Personal Information:</strong> Name, email address, date of birth, gender, religion, NID, Student ID, and university name</li>
    <li><strong>Profile Information:</strong> Education details, location, physical attributes, family information, lifestyle preferences, hobbies, and partner expectations</li>
    <li><strong>Communication Data:</strong> Messages sent through our platform and connection requests</li>
    <li><strong>Technical Data:</strong> IP address, browser type and version, device information, and usage data</li>
</ul>

<h3>How We Use Your Information</h3>
<p>We use your personal data for the following purposes:</p>
<ul>
    <li>To create and manage your matrimonial profile</li>
    <li>To provide matchmaking services and show your profile to potential matches</li>
    <li>To facilitate communication between members</li>
    <li>To verify your identity and ensure platform security</li>
    <li>To send you updates about your account and new matches</li>
    <li>To improve our services and user experience</li>
    <li>To comply with legal obligations</li>
</ul>

<h3>Information Sharing</h3>
<p>Your profile information is shared with other registered users based on your privacy settings. We do not sell your personal information to third parties. We may share your data with:</p>
<ul>
    <li>Other users of the platform who match your criteria</li>
    <li>Service providers who help us operate our platform</li>
    <li>Law enforcement or regulatory authorities when required by law</li>
</ul>

<h3>Data Security</h3>
<p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction. All profile images and sensitive data are stored securely, and access is controlled.</p>

<h3>Your Rights</h3>
<p>You have the right to:</p>
<ul>
    <li>Access your personal data</li>
    <li>Correct inaccurate data</li>
    <li>Request deletion of your data</li>
    <li>Object to processing of your data</li>
    <li>Control your privacy settings</li>
    <li>Temporarily hide your profile</li>
</ul>

<h3>Contact Us</h3>
<p>If you have any questions about this Privacy Policy, please contact us through our Contact page.</p>',
                'meta_description' => 'Privacy Policy for Engineer\'s Matrimony - Learn how we collect, use, and protect your personal information',
                'meta_keywords' => 'privacy policy, data protection, personal information, matrimony privacy',
                'order' => 1,
                'is_active' => true,
                'show_in_footer' => true,
            ],
            [
                'title' => 'Terms of Use',
                'slug' => 'terms-of-use',
                'category' => 'legal',
                'content' => '<h2>Terms of Use</h2>
<p>Last updated: ' . date('F d, Y') . '</p>

<h3>Acceptance of Terms</h3>
<p>By accessing and using Engineer\'s Matrimony, you accept and agree to be bound by the terms and provisions of this agreement. If you do not agree to these terms, please do not use our services.</p>

<h3>Eligibility</h3>
<p>You must be at least 18 years old to use this service. By registering, you represent and warrant that:</p>
<ul>
    <li>You are legally eligible to marry according to applicable laws</li>
    <li>You are not currently married (unless legally allowed to remarry in your jurisdiction)</li>
    <li>All information you provide is accurate and truthful</li>
    <li>You possess valid NID and Student ID credentials</li>
</ul>

<h3>User Accounts</h3>
<p>When you create an account with us, you must provide accurate and complete information. You are responsible for:</p>
<ul>
    <li>Maintaining the security of your account</li>
    <li>All activities that occur under your account</li>
    <li>Updating your profile information to keep it current</li>
    <li>Notifying us immediately of any unauthorized use</li>
</ul>

<h3>User Conduct</h3>
<p>You agree not to:</p>
<ul>
    <li>Create fake or misleading profiles</li>
    <li>Harass, abuse, or harm other users</li>
    <li>Upload inappropriate or offensive content</li>
    <li>Use the service for commercial purposes</li>
    <li>Collect information about other users without permission</li>
    <li>Attempt to bypass security measures</li>
    <li>Impersonate another person or entity</li>
</ul>

<h3>Verification Requirements</h3>
<p>All profiles are subject to verification using NID and Student ID. We reserve the right to:</p>
<ul>
    <li>Request additional verification documents</li>
    <li>Suspend or terminate accounts with fake credentials</li>
    <li>Remove profiles that violate our terms</li>
</ul>

<h3>Premium Packages</h3>
<p>Premium packages offer additional features and benefits. Payment terms:</p>
<ul>
    <li>All payments are processed securely through authorized payment gateways</li>
    <li>Package validity starts from the date of purchase</li>
    <li>Refunds are subject to our refund policy</li>
    <li>Features may be modified with prior notice</li>
</ul>

<h3>Content Ownership</h3>
<p>You retain ownership of all content you upload. However, by uploading content, you grant us a license to use, display, and distribute it for the purpose of operating and promoting our service.</p>

<h3>Termination</h3>
<p>We reserve the right to suspend or terminate your account at any time for violation of these terms, without prior notice. You may also delete your account at any time through our Contact page.</p>

<h3>Limitation of Liability</h3>
<p>Engineer\'s Matrimony is a platform to connect people. We are not responsible for:</p>
<ul>
    <li>The accuracy of user-provided information</li>
    <li>The conduct of users offline</li>
    <li>Any disputes between users</li>
    <li>The success or failure of any relationship</li>
</ul>

<h3>Changes to Terms</h3>
<p>We may update these terms from time to time. Continued use of the service after changes constitutes acceptance of the new terms.</p>

<h3>Contact</h3>
<p>For questions about these Terms of Use, please contact us through our Contact page.</p>',
                'meta_description' => 'Terms of Use for Engineer\'s Matrimony - Rules and guidelines for using our matrimonial platform',
                'meta_keywords' => 'terms of use, terms and conditions, user agreement, matrimony rules',
                'order' => 2,
                'is_active' => true,
                'show_in_footer' => true,
            ],
            [
                'title' => 'Sales and Refunds',
                'slug' => 'sales-and-refunds',
                'category' => 'legal',
                'content' => '<h2>Sales and Refund Policy</h2>
<p>Last updated: ' . date('F d, Y') . '</p>

<h3>Premium Package Purchase</h3>
<p>Engineer\'s Matrimony offers premium packages with enhanced features. When you purchase a package:</p>
<ul>
    <li>Payment is processed immediately through our secure payment gateway</li>
    <li>Package validity begins from the date of successful payment</li>
    <li>You will receive a payment confirmation via email</li>
    <li>Premium features are activated within 5 minutes of payment</li>
</ul>

<h3>Payment Methods</h3>
<p>We accept the following payment methods:</p>
<ul>
    <li><strong>bKash:</strong> Secure mobile banking payment</li>
    <li>Other authorized payment gateways as displayed during checkout</li>
</ul>

<h3>Package Types and Duration</h3>
<p>Premium packages are available in different durations:</p>
<ul>
    <li><strong>Monthly Package:</strong> Valid for 30 days</li>
    <li><strong>Quarterly Package:</strong> Valid for 90 days</li>
    <li><strong>Annual Package:</strong> Valid for 365 days</li>
</ul>

<h3>Refund Policy</h3>
<p>We want you to be satisfied with our service. Our refund policy is as follows:</p>

<h4>Eligible for Refund:</h4>
<ul>
    <li>Technical issues preventing access to premium features (within 48 hours of purchase)</li>
    <li>Duplicate payment for the same package</li>
    <li>Payment errors or unauthorized charges</li>
    <li>Service downtime exceeding 7 consecutive days</li>
</ul>

<h4>Not Eligible for Refund:</h4>
<ul>
    <li>Change of mind after purchase</li>
    <li>Failure to find a suitable match</li>
    <li>Partially used package validity period</li>
    <li>Account suspension due to policy violations</li>
    <li>Package purchases older than 7 days</li>
</ul>

<h3>Refund Process</h3>
<p>To request a refund:</p>
<ol>
    <li>Contact us through our Contact page within 7 days of purchase</li>
    <li>Provide your payment transaction ID and order details</li>
    <li>Explain the reason for the refund request</li>
    <li>Our team will review your request within 3-5 business days</li>
    <li>Approved refunds will be processed to the original payment method within 7-10 business days</li>
</ol>

<h3>Cancellation Policy</h3>
<p>You may cancel your premium package at any time, but:</p>
<ul>
    <li>No refund will be provided for the unused period</li>
    <li>Premium features will remain active until the package expiry date</li>
    <li>You can resume using free features after expiry</li>
</ul>

<h3>Auto-Renewal</h3>
<p>Currently, we do not offer auto-renewal for packages. You will need to manually renew your package before expiry to continue enjoying premium features.</p>

<h3>Payment Disputes</h3>
<p>If you notice any unauthorized charges or payment discrepancies:</p>
<ul>
    <li>Contact us immediately through our Contact page</li>
    <li>Provide transaction details and evidence</li>
    <li>We will investigate and resolve the issue within 7 business days</li>
</ul>

<h3>Promotional Offers</h3>
<p>Promotional discounts and offers:</p>
<ul>
    <li>Are subject to specific terms and conditions</li>
    <li>Cannot be combined with other offers unless stated</li>
    <li>May be modified or withdrawn at any time</li>
    <li>Refund policies apply to the actual amount paid, not the original price</li>
</ul>

<h3>Contact for Sales Support</h3>
<p>For questions about payments, packages, or refunds, please contact us through our Contact page with your query details.</p>',
                'meta_description' => 'Sales and Refund Policy for Engineer\'s Matrimony - Information about premium packages, payments, and refunds',
                'meta_keywords' => 'sales policy, refund policy, payment, premium packages, cancellation',
                'order' => 3,
                'is_active' => true,
                'show_in_footer' => true,
            ],
            [
                'title' => 'Legal Information',
                'slug' => 'legal',
                'category' => 'legal',
                'content' => '<h2>Legal Information</h2>
<p>Last updated: ' . date('F d, Y') . '</p>

<h3>About Engineer\'s Matrimony</h3>
<p>Engineer\'s Matrimony is an online matrimonial platform designed to help engineering professionals and students find their life partners. We operate in accordance with the laws of Bangladesh and international data protection regulations.</p>

<h3>Compliance and Regulations</h3>
<p>Our platform complies with:</p>
<ul>
    <li>Bangladesh Digital Security Act</li>
    <li>Personal Data Protection regulations</li>
    <li>E-Commerce regulations for online services</li>
    <li>Payment gateway security standards</li>
    <li>International data protection best practices</li>
</ul>

<h3>Identity Verification</h3>
<p>All users are required to verify their identity using:</p>
<ul>
    <li><strong>National ID (NID):</strong> Valid Bangladesh National Identity Card</li>
    <li><strong>Student ID:</strong> Valid university or educational institution ID</li>
    <li><strong>University Information:</strong> Name of educational institution</li>
</ul>
<p>We verify this information to ensure authenticity and maintain a safe community.</p>

<h3>Intellectual Property</h3>
<p>All content on this website, including but not limited to:</p>
<ul>
    <li>Logo and branding materials</li>
    <li>Website design and layout</li>
    <li>Software and code</li>
    <li>Text, graphics, and images</li>
</ul>
<p>are the property of Engineer\'s Matrimony and are protected by copyright and trademark laws.</p>

<h3>User-Generated Content</h3>
<p>Users retain ownership of their uploaded content (photos, profile information, etc.). However, by uploading content, users grant Engineer\'s Matrimony a non-exclusive license to:</p>
<ul>
    <li>Display the content on our platform</li>
    <li>Use the content for marketing and promotional purposes</li>
    <li>Store and process the content as necessary for service operation</li>
</ul>

<h3>Prohibited Activities</h3>
<p>The following activities are strictly prohibited and may result in legal action:</p>
<ul>
    <li>Creating fake profiles or impersonation</li>
    <li>Harassment, stalking, or threatening behavior</li>
    <li>Fraud or financial scams</li>
    <li>Unauthorized data collection or scraping</li>
    <li>Distribution of inappropriate content</li>
    <li>Violation of other users\' privacy</li>
    <li>Any illegal activities</li>
</ul>

<h3>Dispute Resolution</h3>
<p>In case of disputes between users or with Engineer\'s Matrimony:</p>
<ul>
    <li>First, contact us through our Contact page to seek resolution</li>
    <li>We will investigate and mediate as necessary</li>
    <li>Legal disputes are subject to the jurisdiction of Bangladesh courts</li>
</ul>

<h3>Data Protection Officer</h3>
<p>For privacy and data protection concerns, you can contact our Data Protection Officer through the Contact page.</p>

<h3>Liability Disclaimer</h3>
<p>Engineer\'s Matrimony serves as a platform to connect individuals. We are not responsible for:</p>
<ul>
    <li>The accuracy of information provided by users</li>
    <li>User conduct outside the platform</li>
    <li>The outcome of any relationships formed</li>
    <li>Any disputes between users</li>
    <li>Third-party services linked from our platform</li>
</ul>

<h3>Reporting Violations</h3>
<p>If you encounter any violations of our terms or illegal activities, please report immediately through our Contact page with relevant details and evidence.</p>

<h3>Amendments</h3>
<p>We reserve the right to update this legal information at any time. Significant changes will be communicated to users via email or platform notifications.</p>

<h3>Contact Information</h3>
<p>For legal inquiries, please use our Contact page or write to us at the address provided on our website.</p>',
                'meta_description' => 'Legal Information for Engineer\'s Matrimony - Compliance, regulations, and legal terms',
                'meta_keywords' => 'legal information, compliance, regulations, intellectual property, liability',
                'order' => 4,
                'is_active' => true,
                'show_in_footer' => true,
            ],

            // Support Pages
            [
                'title' => 'Help Center',
                'slug' => 'help-center',
                'category' => 'support',
                'content' => '<h2>Help Center</h2>
<p>Welcome to the Engineer\'s Matrimony Help Center. Find answers to common questions and get support for your matrimonial journey.</p>

<h3>Getting Started</h3>
<h4>How do I create an account?</h4>
<p>Click the "Registration" button in the navigation menu, fill in your personal information, verification details (NID, Student ID, University), and complete the CAPTCHA verification.</p>

<h4>What documents do I need?</h4>
<p>You need a valid National ID (NID), Student ID, and university information to verify your profile.</p>

<h4>Is registration free?</h4>
<p>Yes, basic registration is free. We offer premium packages with additional features.</p>

<h3>Profile Management</h3>
<h4>How do I update my profile?</h4>
<p>Go to Settings → Profile after logging in. You can update your basic information, education, location, preferences, and more.</p>

<h4>Can I upload multiple photos?</h4>
<p>Yes, you can upload and manage multiple photos in your profile settings.</p>

<h4>How do I change my privacy settings?</h4>
<p>Navigate to Settings → Privacy to control who can see your profile and contact information.</p>

<h3>Finding Matches</h3>
<h4>How does the search work?</h4>
<p>Use the Search page to filter profiles by age, gender, religion, education, location, and other criteria to find compatible matches.</p>

<h4>How do I send a connection request?</h4>
<p>Visit a profile you\'re interested in and click "Send Connection Request". The other person will be notified and can accept or decline.</p>

<h4>What happens after a connection is accepted?</h4>
<p>You can view each other\'s full contact information (if shared) and communicate directly.</p>

<h3>Premium Features</h3>
<h4>What are the benefits of premium packages?</h4>
<p>Premium members get unlimited connection requests, priority profile listing, verified badge, and dedicated support.</p>

<h4>How do I purchase a premium package?</h4>
<p>Go to the Packages page, select your preferred package, and complete the payment through bKash or other available methods.</p>

<h3>Safety and Security</h3>
<h4>How do I report suspicious behavior?</h4>
<p>Use the Contact page to report any suspicious profiles or behavior. Provide the profile ID and details of the issue.</p>

<h4>Is my personal information safe?</h4>
<p>Yes, we use industry-standard encryption and security measures to protect your data.</p>

<h4>How do I block someone?</h4>
<p>Visit the user\'s profile and click the block option. They will no longer be able to contact you or view your profile.</p>

<h3>Account Issues</h3>
<h4>I forgot my password. What should I do?</h4>
<p>Click "Forgot Password" on the login page and follow the instructions to reset your password via email.</p>

<h4>How do I delete my account?</h4>
<p>Contact us through the Contact page with your deletion request. Your account will be permanently deleted within 48 hours.</p>

<h3>Payment Support</h3>
<h4>Which payment methods do you accept?</h4>
<p>We accept bKash and other authorized payment gateways displayed during checkout.</p>

<h4>Can I get a refund?</h4>
<p>Refunds are available within 7 days of purchase for specific reasons. See our Sales and Refunds policy for details.</p>

<h3>Technical Support</h3>
<h4>The website is not working properly. What should I do?</h4>
<p>Try clearing your browser cache and cookies. If the issue persists, contact us with details about the problem.</p>

<h4>I can\'t upload my profile picture. Why?</h4>
<p>Ensure your image is in JPG, JPEG, or PNG format and under 5MB. Clear your cache and try again.</p>

<h3>Still Need Help?</h3>
<p>Can\'t find the answer you\'re looking for? Visit our <a href="/faq">FAQ page</a> or <a href="/contact">Contact us</a> for personalized support.</p>',
                'meta_description' => 'Help Center - Get answers to common questions about Engineer\'s Matrimony',
                'meta_keywords' => 'help center, support, how to, guide, assistance, matrimony help',
                'order' => 1,
                'is_active' => true,
                'show_in_footer' => true,
            ],
            [
                'title' => 'Safety Information',
                'slug' => 'safety-information',
                'category' => 'support',
                'content' => '<h2>Safety Information</h2>
<p>Your safety is our top priority. Follow these guidelines to ensure a safe and positive experience on Engineer\'s Matrimony.</p>

<h3>Profile Safety</h3>
<h4>Verify Your Identity</h4>
<ul>
    <li>Complete the NID and Student ID verification process</li>
    <li>Use recent, authentic photos</li>
    <li>Provide accurate information in your profile</li>
</ul>

<h4>Protect Your Personal Information</h4>
<ul>
    <li>Don\'t share sensitive information (bank details, passwords) with anyone</li>
    <li>Be cautious about sharing your home address too early</li>
    <li>Use the platform\'s messaging system initially</li>
    <li>Control your privacy settings to manage who can see your information</li>
</ul>

<h3>Communication Safety</h3>
<h4>Online Interactions</h4>
<ul>
    <li>Be respectful and courteous in all communications</li>
    <li>Don\'t respond to inappropriate or offensive messages</li>
    <li>Report any harassment or suspicious behavior immediately</li>
    <li>Trust your instincts - if something feels wrong, it probably is</li>
</ul>

<h4>Red Flags to Watch For</h4>
<ul>
    <li>Requests for money or financial assistance</li>
    <li>Inconsistent or vague information</li>
    <li>Refusal to video chat or meet in person (after reasonable time)</li>
    <li>Pressure to move communication off the platform quickly</li>
    <li>Stories that seem too good to be true</li>
    <li>Aggressive or inappropriate behavior</li>
</ul>

<h3>Meeting in Person</h3>
<h4>Before Meeting</h4>
<ul>
    <li>Get to know the person well through messages and video calls</li>
    <li>Verify their identity and information</li>
    <li>Inform family members or friends about your plans</li>
    <li>Choose a public place for the first meeting</li>
</ul>

<h4>During the Meeting</h4>
<ul>
    <li>Meet in a public, well-lit place (café, restaurant, mall)</li>
    <li>Arrive and leave independently</li>
    <li>Keep your phone charged and with you</li>
    <li>Tell someone where you\'re going and expected return time</li>
    <li>Stay sober and alert</li>
    <li>Don\'t feel pressured to stay if you\'re uncomfortable</li>
</ul>

<h3>Financial Safety</h3>
<h4>Never Send Money</h4>
<ul>
    <li>Never send money to someone you haven\'t met in person</li>
    <li>Be wary of sob stories or emergency requests</li>
    <li>Legitimate matches will never ask for financial help</li>
    <li>Report any financial solicitation immediately</li>
</ul>

<h4>Protect Your Financial Information</h4>
<ul>
    <li>Don\'t share bank account details, credit card numbers, or passwords</li>
    <li>Only make payments through our official platform</li>
    <li>Verify payment confirmations through official channels</li>
</ul>

<h3>Photo and Content Safety</h3>
<ul>
    <li>Only upload photos you\'re comfortable being public</li>
    <li>Avoid photos with personal information in the background</li>
    <li>Don\'t share inappropriate or explicit content</li>
    <li>Report misuse of your photos immediately</li>
</ul>

<h3>Account Security</h3>
<ul>
    <li>Use a strong, unique password</li>
    <li>Don\'t share your login credentials with anyone</li>
    <li>Log out when using public or shared devices</li>
    <li>Enable two-factor authentication if available</li>
    <li>Report any unauthorized access immediately</li>
</ul>

<h3>Recognizing Scams</h3>
<h4>Common Scam Types</h4>
<ul>
    <li><strong>Romance Scams:</strong> Building emotional connection to request money</li>
    <li><strong>Identity Theft:</strong> Collecting personal information for fraudulent purposes</li>
    <li><strong>Catfishing:</strong> Using fake photos and information</li>
    <li><strong>Investment Scams:</strong> Offering fake business opportunities</li>
</ul>

<h3>Family Involvement</h3>
<ul>
    <li>Involve your family in important decisions</li>
    <li>Conduct background verification through families</li>
    <li>Meet families before making serious commitments</li>
    <li>Verify educational and professional credentials</li>
</ul>

<h3>Reporting and Blocking</h3>
<h4>When to Report</h4>
<p>Report immediately if you encounter:</p>
<ul>
    <li>Fake or suspicious profiles</li>
    <li>Harassment or abusive behavior</li>
    <li>Financial solicitation</li>
    <li>Inappropriate content</li>
    <li>Identity theft or impersonation</li>
    <li>Any violation of our terms</li>
</ul>

<h4>How to Report</h4>
<ul>
    <li>Use the Contact page with profile ID and details</li>
    <li>Provide screenshots or evidence if available</li>
    <li>We investigate all reports within 24-48 hours</li>
    <li>Serious violations result in immediate account suspension</li>
</ul>

<h3>Emergency Situations</h3>
<p>If you feel threatened or in danger:</p>
<ul>
    <li>Leave the situation immediately</li>
    <li>Contact local authorities (999 in Bangladesh)</li>
    <li>Report the incident to us</li>
    <li>Seek support from family or friends</li>
</ul>

<h3>Safe Dating Checklist</h3>
<ol>
    <li>✓ Profile verified with NID and Student ID</li>
    <li>✓ Information verified through multiple conversations</li>
    <li>✓ Video calls completed</li>
    <li>✓ Families informed and involved</li>
    <li>✓ Public place selected for first meeting</li>
    <li>✓ Someone knows your whereabouts</li>
    <li>✓ No money exchanged</li>
    <li>✓ Trust your instincts</li>
</ol>

<h3>Need Help?</h3>
<p>If you have safety concerns or need assistance, <a href="/contact">contact us</a> immediately. Your safety is our priority.</p>',
                'meta_description' => 'Safety Information - Stay safe while using Engineer\'s Matrimony platform',
                'meta_keywords' => 'safety tips, online safety, dating safety, matrimony safety, protection',
                'order' => 2,
                'is_active' => true,
                'show_in_footer' => true,
            ],
            [
                'title' => 'Cancellation & Returns',
                'slug' => 'cancellation-returns',
                'category' => 'support',
                'content' => '<h2>Cancellation & Returns Policy</h2>
<p>Last updated: ' . date('F d, Y') . '</p>

<h3>Premium Package Cancellation</h3>
<p>You can cancel your premium package at any time. Here\'s what you need to know:</p>

<h4>Cancellation Process</h4>
<ol>
    <li>Log in to your account</li>
    <li>Go to Payment History</li>
    <li>Select the active package you want to cancel</li>
    <li>Click "Cancel Package" or contact us through the Contact page</li>
    <li>Confirm your cancellation request</li>
</ol>

<h4>What Happens After Cancellation</h4>
<ul>
    <li>Your premium features will remain active until the current package expiry date</li>
    <li>No refund will be issued for the unused portion of the package</li>
    <li>After expiry, your account will automatically revert to free membership</li>
    <li>You can upgrade to premium again at any time</li>
    <li>Your profile and data will remain intact</li>
</ul>

<h3>Account Cancellation</h3>
<h4>Temporary Deactivation</h4>
<p>If you want to take a break without losing your data:</p>
<ul>
    <li>Go to Settings → Privacy</li>
    <li>Select "Hide My Profile"</li>
    <li>Your profile will be hidden from searches</li>
    <li>You can reactivate anytime by unhiding your profile</li>
    <li>Your premium package validity continues during this period</li>
</ul>

<h4>Permanent Account Deletion</h4>
<p>If you wish to permanently delete your account:</p>
<ol>
    <li>Contact us through the Contact page</li>
    <li>Request account deletion with your registered email</li>
    <li>Confirm your identity</li>
    <li>We will process the deletion within 48 hours</li>
    <li>All your data will be permanently removed</li>
    <li>This action cannot be undone</li>
</ol>

<p><strong>Important:</strong> Account deletion does not automatically cancel premium packages or entitle you to a refund.</p>

<h3>Refund Eligibility</h3>
<h4>You May Be Eligible for a Refund If:</h4>
<ul>
    <li>Technical issues prevented access to premium features within 48 hours of purchase</li>
    <li>You were charged twice for the same package (duplicate payment)</li>
    <li>There was a payment processing error</li>
    <li>Service was unavailable for more than 7 consecutive days</li>
    <li>Features advertised were not delivered</li>
</ul>

<h4>You Are NOT Eligible for a Refund If:</h4>
<ul>
    <li>You changed your mind after purchasing</li>
    <li>You didn\'t find suitable matches</li>
    <li>You\'ve already used the premium features</li>
    <li>More than 7 days have passed since purchase</li>
    <li>Your account was suspended for violating terms</li>
    <li>You voluntarily cancelled the package</li>
</ul>

<h3>Refund Request Process</h3>
<ol>
    <li><strong>Contact Us:</strong> Use the Contact page within 7 days of purchase</li>
    <li><strong>Provide Details:</strong>
        <ul>
            <li>Transaction ID</li>
            <li>Order/Payment confirmation number</li>
            <li>Date of purchase</li>
            <li>Package name</li>
            <li>Detailed reason for refund</li>
        </ul>
    </li>
    <li><strong>Review:</strong> Our team will review within 3-5 business days</li>
    <li><strong>Decision:</strong> You\'ll receive email notification about approval/rejection</li>
    <li><strong>Processing:</strong> Approved refunds processed within 7-10 business days</li>
    <li><strong>Receipt:</strong> Refund credited to original payment method</li>
</ol>

<h3>Refund Timeline</h3>
<ul>
    <li><strong>bKash:</strong> 5-7 business days</li>
    <li><strong>Bank Transfer:</strong> 7-10 business days</li>
    <li><strong>Other Methods:</strong> 7-14 business days</li>
</ul>
<p>Note: Processing times may vary depending on your bank or payment provider.</p>

<h3>Partial Refunds</h3>
<p>In exceptional circumstances, we may offer partial refunds:</p>
<ul>
    <li>Service disruptions affecting premium features</li>
    <li>Technical issues partially limiting functionality</li>
    <li>Partial refund amount calculated based on unused days</li>
</ul>

<h3>Dispute Resolution</h3>
<p>If your refund request is denied and you disagree:</p>
<ol>
    <li>Reply to the rejection email with additional information</li>
    <li>Request escalation to senior management</li>
    <li>We will conduct a second review within 5 business days</li>
    <li>Final decision will be communicated via email</li>
</ol>

<h3>Promotional Package Cancellations</h3>
<ul>
    <li>Packages purchased during promotions follow the same cancellation policy</li>
    <li>Refunds (if eligible) are based on the actual amount paid, not the original price</li>
    <li>Promotional offers cannot be transferred or exchanged</li>
</ul>

<h3>Auto-Renewal Cancellation</h3>
<p>We currently do not offer auto-renewal. However, if this feature is introduced:</p>
<ul>
    <li>You can cancel auto-renewal anytime before the next billing date</li>
    <li>Cancellation must be done at least 48 hours before renewal</li>
    <li>You will receive reminder emails before auto-renewal</li>
</ul>

<h3>Exceptions and Special Cases</h3>
<h4>Medical Emergency</h4>
<p>In case of serious medical emergencies (with proof), we may consider special refund arrangements on a case-by-case basis.</p>

<h4>Bereavement</h4>
<p>We understand difficult times. Contact us with documentation for compassionate consideration.</p>

<h3>Package Modifications</h3>
<ul>
    <li>You cannot downgrade or modify an active package</li>
    <li>You can purchase additional packages anytime</li>
    <li>Multiple packages cannot be combined</li>
    <li>Wait for current package expiry before purchasing a different tier</li>
</ul>

<h3>Terms and Conditions</h3>
<ul>
    <li>All refund decisions are at Engineer\'s Matrimony\'s discretion</li>
    <li>Refund policy may be updated without prior notice</li>
    <li>Continued use implies acceptance of updated terms</li>
    <li>All refund communications should be through official channels</li>
</ul>

<h3>Contact for Cancellation Support</h3>
<p>For cancellation or refund assistance:</p>
<ul>
    <li>Visit our <a href="/contact">Contact page</a></li>
    <li>Provide all required information for faster processing</li>
    <li>Check your spam folder for our responses</li>
    <li>Response time: 24-48 hours for initial acknowledgment</li>
</ul>

<h3>FAQ</h3>
<h4>Q: Can I cancel after using premium features?</h4>
<p>A: Yes, you can cancel anytime, but no refund will be issued for used features.</p>

<h4>Q: What if I accidentally purchased the wrong package?</h4>
<p>A: Contact us within 24 hours with proof of accidental purchase. We\'ll review on a case-by-case basis.</p>

<h4>Q: Will I lose my matches if I cancel?</h4>
<p>A: No, your existing connections remain, but you may lose access to unlimited connection requests.</p>

<h4>Q: Can I transfer my package to another account?</h4>
<p>A: No, packages are non-transferable and account-specific.</p>

<p>For more questions, visit our <a href="/faq">FAQ page</a> or <a href="/contact">contact us</a>.</p>',
                'meta_description' => 'Cancellation and Returns Policy - Information about cancelling packages and requesting refunds',
                'meta_keywords' => 'cancellation policy, returns, refund policy, package cancellation, account deletion',
                'order' => 3,
                'is_active' => true,
                'show_in_footer' => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
