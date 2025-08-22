<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 

    require __DIR__ . '/vendor/autoload.php';
    PostHog\PostHog::init('phc_GtXvRBEJPJgNjyrV3X0C9Kr3OtN7AJS03mkE6sp7kUm');
    if(empty($_COOKIE['ph_anonymous_user_id']) && empty($_COOKIE['ph_user_variant'])){
        $anonymous_user_id = bin2hex(random_bytes(16));	
        $assigned_variant_key = PostHog\PostHog::getFeatureFlag('subscription_higher_prices', $anonymous_user_id);
        setcookie('ph_anonymous_user_id', htmlspecialchars($anonymous_user_id, ENT_QUOTES), time() + 86400, '/'); 
        setcookie('ph_user_variant', $assigned_variant_key, time() + 86400, '/'); 
    }
    ?>

    <form action="">
        <input type="email" name="email" class="email">
        <button type="button" class="button-submit">submit</button>
    </form>

    <script src="../js/posthog-min.js"></script>
    <script src="../js/posthog.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
        const emailInput = document.querySelector(".email");
        const submitBtn = document.querySelector(".button-submit");

        submitBtn.addEventListener("click", function() {
                const email = emailInput.value.trim();

                if (email) {

                    const email = document.querySelector('.email').value.trim();
                    const anonId = posthog.get_distinct_id();
            
                    //1. Alias anon → databaseId (merge users)
                    posthog.alias(email, anonId);

                    //2. Identify the user with the same ID (so PostHog merges)
                    posthog.identify(databaseId, {
                        email: databaseId,
                    });

                    //3. (Optional) attach custom properties
                    posthog.register({ subscription_higher_prices: true });

                    // Wait for feature flags to load and log the result
                    posthog.onFeatureFlags(function() {
                        const flagValue = posthog.getFeatureFlag('subscription_higher_prices');
                        console.log(`DatabaseId: ${databaseId}, AnonymousId: ${anonId}, Email: ${email}, Current flag: ${flagValue}`);
                    });

                } else {
                    console.error('Email is required');
                }
            });
        });
    </script>
    </body>
</html>