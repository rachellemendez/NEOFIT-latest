<?php
// Get the current year dynamically
$currentYear = date('Y');
?>

<!-- Font Awesome CDN for social icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<footer>
    <div class="footer-container" style="grid-template-columns: repeat(4, 1fr);">
        <div class="footer-column">
            <h3>About Us</h3>
            <ul>
                <li><a href="our_story.php">Our Story</a></li>
                <li><a href="mission_vision.php">Mission and Vision</a></li>
            </ul>
        </div>
        <div class="footer-column">
            <h3>Support</h3>
            <ul>
                <li><a href="faqs.php">FAQs</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </div>
        <div class="footer-column">
            <h3>Legal</h3>
            <ul>
                <li><a href="terms_conditions.php">Terms and Conditions</a></li>
                <li><a href="privacy_policy.php">Privacy Policy</a></li>
            </ul>
        </div>
        <div class="footer-column">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© <?php echo $currentYear; ?> Neofit. All rights reserved.</p>
    </div>
</footer>

<style>
    footer {
        padding: 40px 20px;
        background-color: #f5f5f5;
        color: #000;
    }
    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
    }
    .footer-column h3 {
        font-size: 18px;
        margin-bottom: 20px;
    }
    .footer-column ul {
        list-style: none;
    }
    .footer-column li {
        margin-bottom: 10px;
    }
    .footer-column a {
        color: #666;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.3s;
    }
    .footer-column a:hover {
        color: #000;
    }
    .social-icons {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }
    .social-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s;
    }
    .social-icon:hover {
        background-color: #bbb;
    }
    .footer-bottom {
        max-width: 1200px;
        margin: 30px auto 0;
        padding-top: 20px;
        border-top: 1px solid #ddd;
        text-align: right;
        font-size: 12px;
        color: #999;
    }
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .footer-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 480px) {
        .footer-container {
            grid-template-columns: 1fr;
        }
    }
</style> 