@if(isset($categories[0]))
@foreach($categories as $key => $category)
@if($products->where('category_id', $category->id)->count())
<optgroup label="{{ $category->name }}">
    @foreach($products->where('category_id', $category->id) as $key => $product)
    <option value="{{ $product->id }}">{{ $product->name }} {{ getProductAttributesFaster($product) }}</option>
    @endforeach
</optgroup>
@endif
@endforeach
@endif