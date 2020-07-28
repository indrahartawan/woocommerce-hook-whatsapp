add_action('woocommerce_thankyou', 'send_message', 10, 1);

function send_message( $order_id ) {
    if ( ! $order_id )
        return;

    // Allow code execution only once 
    if( ! get_post_meta( $order_id, '_thankyou_action_done', true ) ) {

        // Get an instance of the WC_Order object
        $order = wc_get_order( $order_id );

        if($order->is_paid())
            $paid = __('Yes');
        else
            $paid = __('No');
        $payment_method = ucfirst($order->get_payment_method());
        $payment_method_title = ucfirst($order->get_payment_method_title());
        $detail_order = "Order ID: ". $order_id . "\n";
        $produk_name = "";
        $no = 1;
        // Loop through order items
        foreach ( $order->get_items() as $item ) {

                if ( $item->is_type( 'line_item' ) ) {
                        $produk = $item->get_product();

                        $produk_name .= $no.". ".$produk->get_name()."\n";
                }
                $no++;
        }
        $detail_order .= $produk_name . "\nOrder Status: " . ucfirst($order->get_status()) . "\nOrder is paid: " . $paid . "\nPayment Method: ".$payment_method . "(". $payment_method_title .")\n";

        $order_received_url = wc_get_endpoint_url( 'order-received', $order->get_id(), wc_get_page_permalink( 'checkout' ) );

        if ( 'yes' === get_option( 'woocommerce_force_ssl_checkout' ) || is_ssl() ) {
                $order_received_url = str_replace( 'http:', 'https:', $order_received_url );
        }

        $order_received_url = add_query_arg( 'key', $order->get_order_key(), $order_received_url );


        date_default_timezone_set("Asia/Jakarta");
        $nama = strtolower($order->get_billing_first_name());
        $tanggal = date("M d, Y H:i:s");
        $orderid = $order_id;
        $produk_name = $produk_name;
        $no_tujuan = $order->get_billing_phone();
        $total_amount = "Rp. ".number_format($order->get_total(),2);
        $url_invoice = $order_received_url;
        
        // set your configuration here
        $whatsapp_api_url = "your_whatsapp_api_url";
        $whatsapp_api_key = "your_whatsapp_api_key_or_token"; 
        $whatsapp_api_license = "your_whatsapp_api_license";
        $shop_whatsapp_number = "shop_owners_whatsapp_number";

        
        $message = "Hi ".ucfirst($nama).", \nThank you for your order at yourshop.id on ".$tanggal.".\n\nThis is your detail order :\n". $detail_order ."\nYour Whatsapp Number : ".$no_tujuan."\nTotal Amount
 : ".$total_amount."\nDetail Invoice : ".$url_invoice."\n\n*Please finish your payment with yourshop.id within 24 hours.*\n\nIf you have any questions, please click\nhttps://wa.me/".$shop_whatsapp_number."\nThank you."
;
        $phone_no = $no_tujuan;
        
        $message = preg_replace( "/(\n)/", "<ENTER>", $message );
        $message = preg_replace( "/(\r)/", "<ENTER>", $message );
        
        $phone_no = preg_replace( "/(\n)/", ",", $phone_no );
        $phone_no = preg_replace( "/(\r)/", "", $phone_no );

        
        $data = array("phone_no" => $phone_no, "key" => $whatsapp_api_key, "message" => $message);
        $data_string = json_encode($data);
        $ch = curl_init($whatsapp_api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string)) 
        ); 
        $result = curl_exec($ch);


        // Output some data, for debuging purpose
        //echo '<p>Order ID: '. $order_id . ' — Order Status: ' . $order->get_status() . ' — Order is paid: ' . $paid . '</p>';
        // Flag the action as done (to avoid repetitions on reload for example)
        $order->update_meta_data( '_thankyou_action_done', true );
        $order->save();
    }
}
