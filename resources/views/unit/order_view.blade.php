<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title">Order {{ $order->id }}</h4>
</div>
<div class="modal-body">
    <div class="form-group">
        <h2>Products</h2>
    </div>
    <div class="row">
        <table id="" class="table table-striped table-bordered table-hover" >
            <thead>
            <tr>
                <th>Qty</th>
                <th>Product</th>
                <th>SKU</th>
            </tr>
            </thead>
            <tbody>
                <td>{{ $order->quantity }}</td>
                <td>
                    <p>{{ isset($order->unit->name) ? $order->unit->name : 'Unit has deleted' }} (key : {{ $order->license_key }})</p>
                    <small>
                        <ul>
                            <li>Serial Number : {{ (isset($order->machine->nick_name)) ? $order->machine->nick_name.'('.$order->machine->prefix.'-'.$order->machine->serial_number.')' : '' }}</li>
                            <li>Machine Date : {{ $order->machine_date->format('m/d/Y') }}</li>
                        </ul>
                    </small>
                </td>
                <td>
                    {{ isset($order->unit->sku) ? $order->unit->sku : 'Unit has deleted' }}
                </td>
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
</div>