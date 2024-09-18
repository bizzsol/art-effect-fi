<option value="0">All Assets</option>
@if(isset($categories[0]))
@foreach($categories as $key => $category)
@if($products->where('category_id', $category->id)->count() > 0)
<optgroup label="{{ $category->name }}">
    @foreach($products->where('category_id', $category->id) as $key => $product)
    <option value="{{ $product->id }}">{{ $product->name }} {{ getProductAttributesFaster($product) }}</option>
    @endforeach
</optgroup>
@endif
@endforeach
@endif