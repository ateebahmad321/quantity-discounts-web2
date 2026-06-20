<?php
if (isset($_GET['success'])) : ?>
    <div class="notice notice-success is-dismissible">
        <p><strong>Thank you for signing up! Check your email for the discount code!!!!</strong></p>
    </div>
<?php
endif; ?>

<style>
    .wrap {
        background: #f9f9f9;
        border: 1px solid #ddd;
        padding: 20px;
        max-width: 600px;
        margin: 20px auto;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    .form-container {
        background: white;
        padding: 20px;
        border-radius: 5px;
    }

    .form-container input,
    .form-container textarea {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .form-container input[type="submit"] {
        background: #0073aa;
        color: white;
        font-size: 16px;
        border: none;
        cursor: pointer;
    }

    .form-container input[type="submit"]:hover {
        background: #005177;
    }
</style>

<div class="wrap">
    <h1>🎁 GIFT 🎁</h1>
    <h3>Sign up now and get 15% off our premium plugin!</h3>
    <p>Unlock the full potential of your WooCommerce store with Qauntity Discounts plugin!</p>
    <small>We have a limited number of coupons!!!</small>
    <div class="form-container">
        <form method="post" action="<?php
        echo esc_html(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="submit_form">

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <p class="form-instructions">
                By providing your information and clicking 'Sign Up', you consent to receive periodic product updates, tailored pricing strategies, and insider tips to maximize your WooCommerce store's potential. You can unsubscribe at any time.
            </p>
            <input type="submit" value="Sign Up">
        </form>

    </div>
</div>

