{{-- {{ $surveys }} --}}
{{-- @foreach ($surveys as $survey)
    <p>{{ $survey->id }} - {{ $survey->user->name }} - {{ $survey->created_at }}</p>
    <p>{{ $survey->email_feedback }}</p>
@endforeach --}}


{{-- {{ $orders }} --}}
@foreach ($orders as $order)
    <p>{{ $order->order_id }} - {{ $order->created_at }}</p>
    @foreach ($order->items as $item)
        <p>-- {{ $item->product->name }} - {{ $item->quantity }}</p>
    @endforeach
@endforeach

