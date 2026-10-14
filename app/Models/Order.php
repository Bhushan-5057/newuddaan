<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;



class Order extends Model

{

    protected $table = 'orders';



    protected $primaryKey = 'id';



    // તમે મેન્યુઅલી ટાઈમસ્ટેમ્પ્સ હેન્ડલ કરો છો

    public $timestamps = false;



    protected $fillable = [

        'u_code',

        'active_id',

        'tx_type',

        'tx_id',

        'tx_user_id',

        'order_mrp',

        'order_amount',

        'package_name',

        'order_bv',

        'pv',

        'roi',

        'times',

        'roi_bv',

        'order_notes',

        'admin_notes',

        'delivery_schedule',

        'expire_date',

        'order_details',

        'order_address',

        'status',

        'payment_status',

        'payment_response',

        'payout_id',

        'payout_status',

        'tx_hash',

        'closing_status',

        'invoice_no',

        'date_accept',

        'date_dispatch',

        'date_approve',

        'date_reject',

        'added_on',

        'expire_on',

        'payment_method',  
        'payment_details', 
        'next_roi_date',
        'shipping_charge ',
        'shipping_status',
        'awb_code',
        'courier_name ',
        'shipment_id',
        'tracking_url ',
        

        'updated_on',

    ];




    protected $casts = [

        'order_mrp'         => 'float',

        'order_amount'      => 'float',

        'order_bv'          => 'float',

        'pv'                => 'float',

        'roi'               => 'float',

        'times'             => 'float',
         'payment_details' => 'array',

        'roi_bv'            => 'integer',

        'status'            => 'integer',

        'payment_status'    => 'integer',


        'payout_status'     => 'integer',

        'closing_status'    => 'integer',

        'invoice_no'        => 'integer',

        'expire_date'       => 'datetime',

        'expire_on'         => 'datetime',

        'date_accept'       => 'datetime',

        'date_dispatch'     => 'datetime',

        'date_approve'      => 'datetime',

        'date_reject'       => 'datetime',

        'added_on'          => 'datetime',

        'updated_on'        => 'datetime',

    ];



    /**

     * યુઝર સાથેનો સંબંધ

     */



    /**

     * Get all orders for the user.

     */







    public function user()

    {

        return $this->belongsTo(User::class, 'u_code', 'id');

    }



    /**

     * ઓર્ડર આઈટમ્સ સાથેનો સંબંધ (One to Many)

     */

    public function items()

    {

        return $this->hasMany(OrderItem::class, 'order_id', 'id');

    }





    /**

     * ઓર્ડર સ્ટેટસ ટેક્સ્ટ મેળવવા માટે (Helper)

     * 0: Received, 1: On the Way, 2: Completed, 3: Cancelled

     */

    public function getStatusLabelAttribute()

    {

        $statusLabels = [

            0 => 'Received',

            1 => 'On the Way',

            2 => 'Completed',

            3 => 'Cancelled'

        ];



        return $statusLabels[$this->status] ?? 'Unknown';

    }

}

