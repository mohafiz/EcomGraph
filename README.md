## EcomGraph

<p>welcome to EcomGraph, a GraphQL API for an e-commerce platform.</p>

## Done so far

<ul>

<li>
    Authentication: register, verify account, login, and logout.
</li>

<li>
Main screen: list products, filter them, order them, add a product to cart, and remove it from cart.
</li>

<li>
Individual product screen.
</li>

<li>
User profile: get user info, update user info
</li>

<li>
Checkout process: create an order, calculate total price, proceed to checkout
</li>

<li>
Orders history.
</li>

<li>
Product Reviews and Ratings.
</li>

<li>
Wishlist.
</li>

<li>
Admin product management.
</li>

<li>
Promotions and Discounts.
</li>

<li>
Notifications (via Telegram): For this to work, I have used ngrok to publicly expose my local development server, so as to be able to register a webhook with the telegram API to receive any new messages to my bot. In addition, I am making sure that users cannot login to the system unless they have registered with their info in the system, verified their email address, and subscribed to the telegram bot.
</li>

<li>
Categories
</li>

<li>
Internationalization (part I): Added support for 4 languages (English, Arabic, Spanish, and French), with English being the default.
</li>

<li>
Internationalization (part II): Added support for 4 currencies (US dollar, Euro, Japanese yen, and Pound sterling), with the dollar being the default.
</li>

<li>
Performance Optimization: Utilized redis as the caching driver for caching the response from the exchange rate API, I am caching it for 1 hour, since the exchange rate is fluctuating. In addition, I have added full text indexes to products and categories table to make search operations more efficient, and lastly, I have used redis as a queue driver for sending Telegram notifications. I have monitored the performance before and after each step and it shows decrease in response times.
</li>

</ul>

## Future work

<ul>

<li>
Analytics.
</li>

<li>
Social Media Integration.
</li>

</ul>