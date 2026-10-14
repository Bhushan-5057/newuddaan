<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class OrderItem extends Model

{

    use HasFactory;



    // ટેબલનું નામ (જો તમે અગાઉ આપેલું નામ વાપરતા હોવ)

    protected $table = 'order_items';



    // ટાઇમસ્ટેમ્પ ફિલ્ડ્સના નામ તમારા ટેબલ મુજબ સેટ કર્યા છે

    const CREATED_AT = 'added_on';

    const UPDATED_AT = 'updated_on';



    protected $fillable = [

        'order_id',

        'u_code',

        'product_id',

        'qty',

        'price',

        'bv',

        'pv',

        'item_status'

    ];



    /**

     * મેઈન ઓર્ડર સાથેનો સંબંધ (Belongs To Order)

     */

    public function order()

    {

        return $this->belongsTo(Order::class, 'order_id', 'id');

    }



    /**

     * પ્રોડક્ટ સાથેનો સંબંધ (Belongs To Product)

     */

    public function product()

    {

        return $this->belongsTo(Product::class, 'product_id', 'id');

    }
    
    



    /**

     * આઈટમ સ્ટેટસને ટેક્સ્ટમાં મેળવવા માટે (Helper)

     * 0:Pending, 1:Packaging, 2:Shipped, 3:Delivered, 4:Cancelled

     */

    public function getStatusTextAttribute()

    {

        $statuses = [

            0 => 'Pending',

            1 => 'Packaging',

            2 => 'Shipped',

            3 => 'Delivered',

            4 => 'Cancelled'

        ];



        return $statuses[$this->item_status] ?? 'Unknown';

    }

}