@foreach($permissions as $p)
<div class="border p-2 mb-2">
<strong>{{ $p }}</strong>
<label><input type="checkbox" name="permissions[{{ $p }}][view]"> View</label>
<!--<label><input type="checkbox" name="permissions[{{ $p }}][add]"> Add</label>-->
<!--<label><input type="checkbox" name="permissions[{{ $p }}][edit]"> Edit</label>-->
<!--<label><input type="checkbox" name="permissions[{{ $p }}][delete]"> Delete</label>-->
</div>
@endforeach
