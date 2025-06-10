<?php
// Basic site header for Neofit
?>
<header>
    <div class="header-container">
        <a href="landing_page.php" class="logo">NEOFIT</a>
        <nav>
            <ul>
                <li><a href="landing_page.php">Home</a></li>
                <li><a href="our_story.php">About</a></li>
                <li><a href="faqs.php">FAQs</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </div>
</header>

<style>
    header {
        background: #fff;
        border-bottom: 1px solid #eee;
        padding: 10px 0;
    }
    .header-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
    }
    .logo {
        font-size: 24px;
        font-weight: bold;
        color: #000;
        text-decoration: none;
    }
    nav ul {
        list-style: none;
        display: flex;
        gap: 20px;
        margin: 0;
        padding: 0;
    }
    nav a {
        color: #333;
        text-decoration: none;
        font-size: 16px;
        transition: color 0.2s;
    }
    nav a:hover {
        color: #55a39b;
    }
</style> 