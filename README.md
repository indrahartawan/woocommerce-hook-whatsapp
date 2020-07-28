# woocommerce-hooks-whatsapp

https://indrahartawan.com

The codes will add a hooks interaction to send a thank you message via WhatsApp gateway provider

# Installation

Just copy the codes to end of the functions.php file in $WordPress_ROOT/public_html/wp-content/themes/$Themes directory.

# Configuration 

Edit below lines in the codes and change the parameters i.e. `whatsapp_url` to your preferred WhatsApp API like Woo-Wa (http://api.woo-wa.com/).

```
 $whatsapp_api_url = "your_whatsapp_api_url";
 $whatsapp_api_key = your_whatsapp_api_key_or_token"; 
 $whatsapp_api_license = "your_whatsapp_api_license"; 
```

Explanation

`whatsapp_api_url`: your whatsapp API provider's URL

`whatsapp_api_key`: your whatsapp API provider's KEY or TOKEN

`whatsapp_api_license`: your whatsapp API provider's license

# Done
Now, try create an new order on your woocommerce website. You should receive notification from WooCommerce every time new order created.
