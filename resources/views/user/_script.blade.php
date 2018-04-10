<script>
    $(document).ready(function () {
        dateGroupDiv('#machine_generate_code .date');
        $('.edit_machine').on('click', function () {
            var machine_id = $(this).closest('tr').data('id');
            $.ajax({
                method: 'GET',
                url: '{{ url('user/update-machine') }}/'+machine_id
            }).done(function(data) {
                $('#update_machine .modal-body').html(data);
                $('#update_machine').modal('show');
            });
        });
        $('.delete_machine').on('click', function () {
            if(confirm('Are you sure to delete machine, that will be no accessible once it deleted?')) {
                var machine_id = $(this).closest('tr').data('id');
                $.ajax({
                    method: 'POST',
                    url: '{{ route('user_machine_delete')}}',
                    data: 'machine_id=' + machine_id + '&_token=' + csrf_token,
                    dataType: 'json',
                }).done(function (data) {
                    if(data.success) {
                        location.reload();
                    } else {
                        alert(data.errors);
                    }
                });
            }
        });
        $('.order_quick_view').on('click', function() {
            var order_id = $(this).closest('tr').data('id');
            $.ajax({
                method: 'GET',
                url: '{{ url('user/order') }}/'+order_id
            }).done(function(data) {
                $('#order_quick_view .modal-content').html(data);
                $('#order_quick_view').modal('show');
            });
        });

    });
    function saveMachine() {
        return reloadAjaxSubmit('create_machine',"{{ route('user_machine_create') }}",'nick_name','Submit');
    }
    function generateMachineCode() {
        return reloadAjaxSubmit('machine_generate_code',"{{ route('user_machine_generate_code') }}",'machine_id','Submit');
    }
    function updateMachine() {
        return reloadAjaxSubmit('update_machine',"{{ url('user/machine-update') }}",'nick_name','Update');
    }
</script>