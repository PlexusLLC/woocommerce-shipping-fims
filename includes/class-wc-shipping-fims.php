<?php

if ( !defined( 'ABSPATH' ) ) exit;

class WC_Shipping_FIMS extends WC_Shipping_Method {

  /**
   * Constructor
   */
  public function __construct( $instance_id = 0 ) {  
    $this->id                 = 'fims';
    $this->instance_id        = absint( $instance_id );
    $this->method_title       = __( 'FIMS', 'woocommerce-shipping-fims' );
    $this->method_description = __( 'The FIMS extension calculates rates for FedEx International Mail Service dynamically during cart/checkout.', 'woocommerce-shipping-fims' );
    $this->supports           = array(
      'shipping-zones',
      'instance-settings',
    );
    $this->init();
  }
  
  /**
   * is_available function.
   *
   * @param array $package
   * @return bool
   */
  public function is_available( $package ) {
    if ( empty( $package['destination']['country'] ) ) {
      return false;
    }
    return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', true, $package );
  }

  /**
   * Initialize settings
   * @return void
   */
  private function set_settings() {
    // Define user set variables
    $this->title              = $this->get_option( 'title', $this->method_title );
    $this->label              = $this->get_option( 'label', 'FedEx International Mail Service' );
    $this->fims_rate          = floatval( $this->get_option('fims_rate') );
    $this->rating_method      = $this->get_option( 'rating_method', 'TENTH' );
    $this->fuel_surcharge     = floatval( $this->get_option('fuel_surcharge') );
    $this->flat_fee           = floatval( $this->get_option('flat_fee') );
    $this->flat_pkg_weight    = floatval( $this->get_option('flat_pkg_weight') );
    $this->pkg_weight_const   = floatval( $this->get_option('pkg_weight_const') );
    $this->pkg_weight_floor   = floatval( $this->get_option('pkg_weight_floor') );
    $this->pkg_weight_ceiling = floatval( $this->get_option('pkg_weight_ceiling') );
    $this->weight_above       = floatval( $this->get_option('weight_above') );
    $this->weight_below       = floatval( $this->get_option('weight_below') );
    $this->cost_above         = floatval( $this->get_option('cost_above') );
    $this->cost_below         = floatval( $this->get_option('cost_below') );
    $this->rate_min           = floatval( $this->get_option('rate_min') );
    $this->rate_max           = floatval( $this->get_option('rate_max') );    
  }
  
  public function load_admin_scripts() {

  }

  /**
   * init function.
   */
  private function init() {
    // Load the settings.
    $this->init_form_fields();
    $this->set_settings();

    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
    // add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );
  }

  /**
   * Process settings on save
   *
   * @access public
   * @return void
   */
  public function process_admin_options() {
    parent::process_admin_options();

    $this->set_settings();
  }
  
  /**
   * init_form_fields function.
   */
  public function init_form_fields() {
    $this->instance_form_fields = array(
      'basic'          => array(
        'title'        => __( 'Basic Settings', 'woocommerce-shipping-fims' ),
        'type'         => 'title',
        'description'  => '',
      ),
      'title'          => array(
        'title'        => __( 'Method Title', 'woocommerce-shipping-fims' ),
        'type'         => 'text',
        'description'  => __( 'This is the method title only seen in the admin area.', 'woocommerce-shipping-fims' ),
        'default'      => __( 'FIMS', 'woocommerce-shipping-fims' ),
        'desc_tip'     => true
      ), 
      'label'          => array(
        'title'        => __( 'Rate Label', 'woocommerce-shipping-fims' ),
        'type'         => 'text',
        'description'  => __( 'This controls the rate label which the user sees during checkout.', 'woocommerce-shipping-fims' ),
        'default'      => __( 'FedEx International Mail Service', 'woocommerce-shipping-fims' ),
        'desc_tip'     => true
      ),
      'fims_rate'      => array(
        'title'        => __( 'FIMS Rate ($)', 'woocommerce-shipping-fims' ),
        'type'         => 'decimal',
        'description'  => __( 'The FIMS rate you pay per pound.', 'woocommerce-shipping-fims' ),
        'default'      => '',
        'desc_tip'     => true
      ),
      'rating_method'  => array(
        'title'        => __( 'Apply Rate', 'woocommerce-shipping-fims' ),
        'type'         => 'select',
        'default'      => 'LIST',
        'class'        => '',
        'desc_tip'     => true,
        'options'      => array(
          'TENTH'      => __( 'Per 1/10 lb. (recommended)', 'woocommerce-shipping-fims' ),
          'WHOLE'      => __( 'Per whole lb', 'woocommerce-shipping-fims' ),
        ),
        'description'  => __( 'Choose whether to apply rate at whole pounds (rounded up nearest) or 1/10 rate per each 1/10 pound.', 'woocommerce-shipping-fims' )
      ),
      'min_max'        => array(
        'title'        => __( 'Minimum / Maximum', 'woocommerce-shipping-fims' ),
        'type'         => 'title',
        'description'  => __( 'Limit FIMS rate to no more and no less than the following:', 'woocommerce-shipping-fims' ),
      ),
      'rate_min'       => array(
        'title'        => __( 'Minimum ($)', 'woocommerce-shipping-fims' ),
        'type'         => 'decimal',
        'description'  => __( 'Minimum FIMS rate in USD. Leave blank for no lower limit.', 'woocommerce-shipping-fims' ),
        'default'      => '',
        'desc_tip'     => true
      ),
      'rate_max'       => array(
        'title'        => __( 'Maximum ($)', 'woocommerce-shipping-fims' ),
        'type'         => 'decimal',
        'description'  => __( 'Maximum FIMS rate in USD. Leave blank for no upper limit.', 'woocommerce-shipping-fims' ),
        'default'      => '',
        'desc_tip'     => true
      ),
      'upgrade'        => array(
        'title'        => __( 'Upgrade to Premium', 'woocommerce-shipping-fims' ),
        'type'         => 'title',
        'description'  => sprintf( __( 'All the options below are available in the %sPREMIUM version of this plugin%s.', 'woocommerce-shipping-fims' ), '<a href="https://store.plexusllc.com/product/woocommerce-shipping-fims/" target="_blank">', '</a>' ),
      ),
      'additions'      => array(
        'title'        => __( 'Additions', 'woocommerce-shipping-fims' ),
        'type'         => 'title',
        'description'  => __( 'Additional fees added to every FIMS rate quote.', 'woocommerce-shipping-fims' ),
      ),
      'fuel_surcharge' => array(
        'disabled'     => true,
        'title'        => __( 'Fuel Surcharge (%)', 'woocommerce-shipping-fims' ),
        'type'         => 'decimal',
        'description'  => sprintf( __( 'Find surcharge %shere%s. FIMS uses the FedEx Express Surcharge.', 'woocommerce-shipping-fims' ), '<a href="http://www.fedex.com/us/services/fuelsurcharge.html" target="_blank">', '</a>' ),
        'default'      => ''
      ),
      'flat_fee'       => array(
        'disabled'     => true,
        'title'        => __( 'Flat Fee ($)', 'woocommerce-shipping-fims' ),
        'type'         => 'decimal',
        'description'  => __( 'Flat amount in USD to add to FIMS calculated rate per order.', 'woocommerce-shipping-fims' ),
        'default'      => '',
        'desc_tip'     => true
      ),
      'pkg_weight'     => array(
        'title'        => __( 'Package Weight', 'woocommerce-shipping-fims' ),
        'type'         => 'title',
        'description'  => __( 'Add weight per order for shipping boxes & packing materials.<br>Tag any products that do not need packaging with <strong>fims-no-pkg</strong> and they will be ignored<br>for package weight calculations (works well e.g. for items that may ship in zero-weight tyvek envelopes).', 'woocommerce-shipping-fims' ),
      ),
      'flat_pkg_weight' => array(
        'disabled'     => true,
        'title'        => __( 'Flat Package Weight (lbs)', 'woocommerce-shipping-fims' ),
        'type'         => 'decimal',
        'description'  => __( 'Flat weight in lbs. to add to order for box and packing material. Any value here disables below pkg weight options.', 'woocommerce-shipping-fims' ),
        'default'      => '',
        'desc_tip'     => true
      ),
      'pkg_weight_const' => array(
        'disabled'     => true,
        'title'        => __( 'Pkg Weight Constant (lbs)', 'woocommerce-shipping-fims' ),
        'type'         => 'decimal',
        'description'  => __( 'Weight in lbs. to add to order for box and packing material per each linear inch of product dimension. Try starting with .01.', 'woocommerce-shipping-fims' ),
        'default'      => '',
        'desc_tip'     => true
      ),
      'pkg_weight_floor' => array(
        'disabled'     => true,
        'title'        => __( 'Min Pkg Weight (lbs)', 'woocommerce-shipping-fims' ),
        'type'         => 'decimal',
        'description'  => __( 'If using Pkg Weight Constant, minimum in lbs for orders that need packaging. Leave blank for no minimum.', 'woocommerce-shipping-fims' ),
        'default'      => '',
        'desc_tip'     => true
      ),
      'pkg_weight_ceiling' => array(
        'disabled'     => true,
        'title'        => __( 'Max Pkg Weight (lbs)', 'woocommerce-shipping-fims' ),
        'type'         => 'decimal',
        'description'  => __( 'If using Pkg Weight Constant, maximum in lbs for orders that need packaging. Leave blank for no maximum.', 'woocommerce-shipping-fims' ),
        'default'      => '',
        'desc_tip'     => true
      ),
      'weight_limit'   => array(
        'title'        => __( 'Weight Limits', 'woocommerce-shipping-fims' ),
        'type'         => 'title',
        'description'  => __( 'FIMS will only be available for orders between the following weights in lbs.', 'woocommerce-shipping-fims' ),
      ),
      'weight_above'   => array(
        'disabled'     => true,
        'title'        => __( 'Above (lbs)', 'woocommerce-shipping-fims' ),
        'type'         => 'decimal',
        'description'  => __( 'Minimum weight of order for FIMS. Leave blank for no lower limit.', 'woocommerce-shipping-fims' ),
        'default'      => '',
        'desc_tip'     => true
      ),
      'weight_below'   => array(
        'disabled'     => true,
        'title'        => __( 'Below (lbs)', 'woocommerce-shipping-fims' ),
        'type'         => 'decimal',
        'description'  => __( 'Maximum weight of order for FIMS. Leave blank for no upper limit.', 'woocommerce-shipping-fims' ),
        'default'      => '',
        'desc_tip'     => true
      ),
      'cost_limit'     => array(
        'title'        => __( 'Order Cost Limits', 'woocommerce-shipping-fims' ),
        'type'         => 'title',
        'description'  => __( 'FIMS will only be available for orders between the following costs in USD.', 'woocommerce-shipping-fims' ),
      ),
      'cost_above'   => array(
        'disabled'     => true,
        'title'        => __( 'Above ($)', 'woocommerce-shipping-fims' ),
        'type'         => 'decimal',
        'description'  => __( 'Minimum cost of order for FIMS. Leave blank for no lower limit.', 'woocommerce-shipping-fims' ),
        'default'      => '',
        'desc_tip'     => true
      ),
      'cost_below'   => array(
        'disabled'     => true,
        'title'        => __( 'Below ($)', 'woocommerce-shipping-fims' ),
        'type'         => 'decimal',
        'description'  => __( 'Maximum cost of order for FIMS. Leave blank for no upper limit.', 'woocommerce-shipping-fims' ),
        'default'      => '',
        'desc_tip'     => true
      ),
    );
  }
  
  public function calculate_shipping( $package = array() ){
    //get the order weight and dimensions
    $weight = 0;
    $dimensions = 0;
    $needs_packaging = false;
    foreach ( $package['contents'] as $item_id => $values ) {
      $_product = $values['data'];
      $_qty     = $values['quantity'];
      $weight  += $_product->get_weight() * $_qty;
    }
        
    $fims_rate = $this->rating_method === 'TENTH' ? $this->fims_rate / 10 : $this->fims_rate;
        
    if ($this->rating_method === 'TENTH') {
      $weight = $this->round_up( floatval( sprintf("%.3f",$weight*10) ) );
      $fims_cost = $fims_rate * $weight;
    } else {
      $fims_cost = $fims_rate * $this->round_up($weight);
    }
    
    // truncate to minimum / maximum
    if (isset($this->rate_min) && $fims_cost < $this->rate_min) {
      $fims_cost = $this->rate_min;
    }
    if (isset($this->rate_max) && $this->rate_max != 0 && $fims_cost > $this->rate_max) {
      $fims_cost = $this->rate_max;
    }
    
    $this->add_rate( array(
      'id'    => $this->id,
      'label' => $this->label,
      'cost'  => $fims_cost
    ));
  }
  
  private function round_up ($number, $precision = 0) {
    $fig = pow(10, $precision);
    return (ceil($number * $fig) / $fig);
  }
}