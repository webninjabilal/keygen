<div class="table-responsive">
    <table id="" class="table table-striped table-bordered table-hover" >
        <thead>
        <tr>
            <th>Date</th>
            <th>Order #</th>
            <th>Status</th>
            <th>Quantity</th>
        </tr>
        </thead>
        <tbody>
        @if(count($orders) > 0)
            @foreach($orders AS $order)
                <tr class="machine_row" data-id="{{ $order->id }}">
                    <td>{{ $order->created_at->format('m/d/Y') }}</td>
                    <td><a href="javascript:void(0)" class="order_quick_view">{{ $order->id }}</a></td>
                    <td></td>
                    <td>{{ $order->quantity }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    <div class="col-sm-12 pull-right">
        {!! $orders->render() !!}
    </div>
</div>